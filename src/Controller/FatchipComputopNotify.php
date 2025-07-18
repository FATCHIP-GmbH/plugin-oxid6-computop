<?php

/**
 * The Computop Oxid Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Computop Oxid Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Computop Oxid Plugin. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 8.1, 8.2
 *
 * @category   Payment
 * @package    fatchip-gmbh/computop_payments
 * @author     FATCHIP GmbH <support@fatchip.de>
 * @copyright  2024 Computop
 * @license    <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link       https://www.computop.com
 */

namespace Fatchip\ComputopPayments\Controller;

use Exception;
use Fatchip\ComputopPayments\Core\Constants;
use Fatchip\ComputopPayments\Core\Logger;
use Fatchip\ComputopPayments\Helper\Config;
use Fatchip\ComputopPayments\Helper\Payment;
use Fatchip\ComputopPayments\Model\Method\AmazonPay;
use Fatchip\ComputopPayments\Model\Method\PayPalExpress;
use Fatchip\CTPayment\CTOrder\CTOrder;
use Fatchip\CTPayment\CTPaymentService;
use Fatchip\CTPayment\CTResponse;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;
use Fatchip\CTPayment\CTEnums\CTEnumStatus;
use OxidEsales\Eshop\Application\Model\Order;

/**
 * Class DispatchController
 *
 */
class FatchipComputopNotify extends FrontendController
{
    protected $paymentClass;
    protected $paymentService;
    protected $fatchipComputopLogger;

    /**
     * @return void
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws Exception
     */
    public function init()
    {
        $this->paymentService = new CTPaymentService(Config::getInstance()->getConnectionConfig());
        $this->fatchipComputopLogger = new Logger();

        parent::init();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function render()
    {
        $this->notifyAction();
    }

    /**
     * Notify action method
     *
     * Called if Computop sends notifications to NotifyURL,
     * used to update payment status info
     *
     * @return void
     * @throws Exception
     */
    public function notifyAction()
    {
        $len = Registry::getRequest()->getRequestParameter('Len');
        $data = Registry::getRequest()->getRequestParameter('Data');
        $custom = Registry::getRequest()->getRequestParameter('Custom');
        $customParams = explode('&', base64_decode($custom));
        $session = explode('=', $customParams[0])[1];
        $transId = explode('=', $customParams[1])[1];
        $PostRequestParams = [
            'Len' => $len,
            'Data' => $data,
            'SessionId' => $session,
            'TransId' => $transId,
        ];
        $response = $this->paymentService->getDecryptedResponse($PostRequestParams);
        $oOrder = oxNew(Order::class);
        if ($oOrder->load($session) !== true) {
            exit;
        }
        $paymentName = $this->getPaymentName($oOrder);
        $ctPayment = Payment::getInstance()->getComputopPaymentModel($paymentName);
        $this->fatchipComputopLogger->logRequestResponse([], $ctPayment->getLibClassName(), 'NOTIFY', $response);

        // Custom notify handling specific to payment method
        $ctPayment->handleNotifySpecific($oOrder, $response);

        switch ($response->getStatus()) {
            case CTEnumStatus::OK:
            case CTEnumStatus::AUTHORIZED:
            case CTEnumStatus::AUTHORIZE_REQUEST:
                $orderOxId = $response->getSessionId();
                $order = oxNew(Order::class);
                if ($order->load($orderOxId)) {
                    $order->updateOrderAttributes($response);
                    // $order->customizeOrdernumber($response);
                    // $responseRefNr =  $this->updateRefNrWithComputop($order);
                    // $order->autoCapture();
                }
                /* $this->inquireAndupdatePaymentStatus(
                        $order,
                        $paymentName,
                        json_decode($response->getOrderVars(), true)
                    );
                }
                */
                break;
            default:
                exit(0);
        }
        exit(0);
    }

    /**
     * @param $oOrder
     * @return string
     */
    protected function getPaymentName($oOrder)
    {
        if ( $paymentName = $oOrder->getFieldData('oxorder__oxpaymenttype')) {
            return $paymentName;
        }
        exit;
    }

    private function updateRefNrWithComputop($order)
    {
        if (!$order) {
            return null;
        }
        $config = Registry::getConfig();

        $ctOrder = $this->createCTOrder($order);

        $ctPayment = Payment::getInstance()->getComputopPaymentModel($order->getFieldData('oxpaymenttype'));
        if ($ctPayment->isIframeLibMethod() === true) {
            $payment = $this->paymentService->getIframePaymentClass($ctPayment->getLibClassName(), Config::getInstance()->getConnectionConfig(), $ctOrder);
        } else {
            $payment = $this->paymentService->getPaymentClass($ctPayment->getLibClassName());
        }

        $payID = $order->getFieldData('fatchip_computop_payid');

        $RefNrChangeParams = $payment->getRefNrChangeParams($payID, $order->getFieldData('oxordernr'));
        $RefNrChangeParams['EtiId'] = $this->getUserDataParam($config);

        return $order->callComputopService(
            $RefNrChangeParams,
            $payment,
            'REFNRCHANGE',
            $payment->getCTRefNrChangeURL()
        );
    }

    protected function createCTOrder($oOrder)
    {
        $ctOrder = new CTOrder();
        $config = Registry::getConfig();
        $oUser = $oOrder->getUser();

        $ctOrder->setAmount((int)(round($oOrder->getFieldData('oxtotalordersum') * 100)));
        $ctOrder->setCurrency($oOrder->getFieldData('oxcurrency'));
        // try catch in case Address Splitter returns exceptions
        try {
            $ctOrder->setBillingAddress($oOrder->getCTAddress());
            $delAddressExists = !empty($oOrder->getFieldData('oxdelstreet'));
            $ctOrder->setShippingAddress($oOrder->getCTAddress($delAddressExists));

        } catch (Exception $e) {
            Registry::getUtilsView()->addErrorToDisplay('FATCHIP_COMPUTOP_PAYMENTS_PAYMENT_ERROR_ADDRESS');
            $sShopUrl = $config->getShopUrl();
            $returnUrl = $sShopUrl . 'index.php?cl=payment';
            Registry::getUtils()->redirect($returnUrl, false, 301);
        }
        $ctOrder->setEmail($oUser->oxuser__oxusername->value);
        $ctOrder->setCustomerID($oUser->oxuser__oxcustnr->value);
        // Mandatory for paypalStandard
        $orderDesc = $config->getActiveShop()->oxshops__oxname->value . ' '
            . $config->getActiveShop()->oxshops__oxversion->value;
        if(Config::getInstance()->getConfigParam('creditCardTestMode')) {
            $ctOrder->setOrderDesc('Test:0000');
        } else {
            $ctOrder->setOrderDesc($orderDesc);

        }
        return $ctOrder;
    }

    /**
     * Sets the userData paramater for Computop calls to Oxid Version and Module Version
     *
     * @return string
     * @throws Exception
     */
    public function getUserDataParam($config)
    {
        return $config->oxshops__oxname->value . ' '
            . $config->getActiveShop()->oxshops__oxversion->value;
    }
}
