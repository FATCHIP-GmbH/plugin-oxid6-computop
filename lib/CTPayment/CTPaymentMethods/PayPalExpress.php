<?php
/** @noinspection PhpUnused */

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
 * PHP version 5.6, 7.0, 7.1
 *
 * @category   Payment
 * @package    FatchipCTPayment
 * @subpackage CTPaymentMethods
 * @author     FATCHIP GmbH <support@fatchip.de>
 * @copyright  2018 Computop
 * @license    <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link       https://www.computop.com
 */

namespace Fatchip\CTPayment\CTPaymentMethods;

use Fatchip\ComputopPayments\Core\Blowfish;
use Fatchip\ComputopPayments\Core\Constants;
use Fatchip\ComputopPayments\Helper\Encryption;
use Fatchip\CTPayment\CTPaymentMethod;
use Fatchip\CTPayment\CTAddress\CTAddress;
use Fatchip\CTPayment\CTOrder\CTOrder;
use Fatchip\CTPayment\CTPaymentService;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class PaypalExpress
 * @package Fatchip\CTPayment\CTPaymentMethods
 */
class PayPalExpress extends CTPaymentMethod
{
    const paymentClass = 'PayPalExpress';

    const CREATE_ORDER_URL = 'https://www.computop-paygate.com/ExternalServices/paypalorders.aspx';
    const ON_APPROVE_URL = 'https://www.computop-paygate.com/cbPayPal.aspx';
    const ON_CANCEL_URL = 'https://www.computop-paygate.com/cbPayPal.aspx';
    const ON_ERROR_URL = 'https://www.computop-paygate.com/cbPayPal.aspx';

    /*
     * URL for initiating a PayPal Express "shortcut" payment.
     * @return string
     */
    public function getCTPaymentURL()
    {
        return 'https://www.computop-paygate.com/paypal.aspx';
    }

    /**
     * URL for completing a PayPal Express payment.
     * @return string
     */
    public function getCTPaymentCompleteURL()
    {
        return 'https://www.computop-paygate.com/paypalComplete.aspx';
    }

    public function getCTPaymentShortcut()
    {
        return 'https://www.computop-paygate.com/ExternalServices/paypalorders.aspx';
    }

    /**
     * Returns the request parameters for initializing a PayPal Express "shortcut" payment.
     *
     * @param CTOrder $order
     * @param string $urlSuccess
     * @param string $urlFailure
     * @param string $urlBack
     * @param string $shippingProfile (Optional)
     * @param string $locale (Optional)
     * @param string $PayPalAuthorization (Optional)
     * @return array
     */
    public function getPaypalExpressShortcutParams(CTOrder $order, $urlSuccess, $urlFailure, $urlBack, $shippingProfile = '', $locale = 'de_DE')
    {
        /** @var \Fatchip\CTPayment\CTAddress\CTAddress $BillingAddress */
        // $BillingAddress = $this->getBillingAddress($order->getBillingAddress());
        // $ShippingAddress = $this->getShippingAddress($order->getShippingAddress());

        $params = [
            'MerchantID' => $this->merchantID,
            'TransID' => $order->getTransID(),
            'Amount' => $order->getAmount(),
            'Currency' => $order->getCurrency(),
            'refnr' => $order->getPayId(), //should be the orderId, but order is not set up yet, first step is paypal express
            'PayID' => $order->getPayId() //should be the orderId, but order is not set up yet, first step is paypal express
//            'URLFailure' => $urlFailure,
//            'URLBack' => $urlBack,
//            'URLSuccess' => $urlSuccess,
//            'OrderDesc' => $order->getOrderDesc(),
//            'ShippingProfile' => $shippingProfile,
//            'FirstName' => $BillingAddress->getFirstName(),
//            'LastName' => $BillingAddress->getLastName(),
//            'AddrStreet' => $BillingAddress->getStreet(),
//            'AddrCity' => $BillingAddress->getCity(),
//            'AddrState' => $BillingAddress->getState(),
//            'AddrZip' => $BillingAddress->getZip(),
//            'AddrCountryCode' => $BillingAddress->getFirstName() ?? $locale,
//            'Phone' => 000000000,
//            'Custom' => $this->getCustomParam($order),
        ];

        return $params;
    }

    /**
     * Returns the parameters for completing a PayPal Express payment.
     *
     * @param string $payID
     * @param string $transID
     * @param int $amount
     * @param string $currency
     * @return array
     */
    public function getPaypalExpressCompleteParams($payID, $transID, $amount, $currency)
    {
        $params = [
            'PayID' => $payID,
            'TransID' => $transID,
            'MerchantID' => $this->merchantID,
            'Amount' => $amount,
            'Currency' => $currency,
        ];

        return $params;
    }

    /**
     * Returns encoded billing address.
     *
     * @param CTAddress $address
     * @return string
     */
    protected function getBillingAddress(CTAddress $address)
    {
        return base64_encode(json_encode($this->declareAddress($address)));
    }

    /**
     * Returns encoded shipping address.
     *
     * @param CTAddress $address
     * @return string
     */
    protected function getShippingAddress(CTAddress $address)
    {
        return base64_encode(json_encode($this->declareAddress($address)));
    }

    /**
     * Declares address details in an array.
     *
     * @param CTAddress $address
     * @return array
     */
    protected function declareAddress(CTAddress $address)
    {
        return [
            'City' => $address->getCity(),
            'Country' => $address->getCountryCodeIso3(),
            'Street' => $address->getStreet(),
            'StreetNumber' => $address->getStreetNr(),
            'PostalCode' => $address->getZip(),
        ];
    }

    /**
     * Custom parameter to send additional data.
     *
     * @param CTOrder $order
     * @return string
     */
    protected function getCustomParam(CTOrder $order)
    {
        return base64_encode(json_encode(['OrderID' => $order->getPayId()]));
    }

    /**
     * Sets and returns request parameters for a refund.
     *
     * @param string $payID
     * @param string $transID
     * @param int $amount
     * @param string $currency
     * @return array
     */
    public function getRefundParams($payID, $amount, $currency, $transID = null, $xID = null, $orderDesc = null, $klarnaInvNo = null, $schemeReferenceID = null, $orderAmount = null)
    {
        $params = [
            'PayID' => $payID,
            'TransID' => $transID,
            'Amount' => $amount,
            'Currency' => $currency,
            'MerchantID' => $this->merchantID,
        ];

        return $params;
    }

    public function generateFrontendRequestParams(CTOrder $oOrder, $config)
    {
        $params = [];
        $params['Capture'] = 'Manual';//$this->config->getPaypalExpressCaption() === 'AUTO' ? 'Auto' : 'Manual';
        $params['Currency'] = $oOrder->getCurrency();
        $params['Amount'] = $this->formatAmount($oOrder->getAmount());
        $params['TransID'] = $oOrder->getTransID();
        $params['orderDesc'] = $params['TransID'];
        $params['ReqId'] = $this->generateRequestId();
        $params['EtiID'] = $this->getEtiID();
        $params['TxType'] = 'Auth';

        /**
         * Referenznummer des Händlers: hier kann eine separate Referenznummer übertragen werden, z.B. eine Rechnungsnummer
         */
        $params['RefNr'] = mt_rand(92333, 243000) . mt_rand(100000, 999999);

        $oConfig = Registry::getConfig();
        $sShopUrl = $oConfig->getShopUrl();

        $sCallBackUrlBase = $sShopUrl . 'computop/paypalexpress/';
        $params['URLSuccess'] = $sCallBackUrlBase . 'success/';
        $params['URLFailure'] = $sCallBackUrlBase . 'failure/';
        $params['URLNotify'] = $sCallBackUrlBase . 'notify/';
        $params['UserData'] = json_encode([
            'sid' => Registry::getSession()->getId(),
        ]);

        return $params;
    }

    public function generateRequestId(): string
    {
        return md5(bin2hex(openssl_random_pseudo_bytes(12)) . mt_rand(100000, 999999));
    }

    public function getEtiID(): string
    {
        //TODO: make it configurable
        return 'OXID7-EXPERIMENTAL-DEV1';
    }

    public function isActive(): bool
    {
        $oBasket = Registry::getSession()->getBasket();
        $oPayment = oxNew(Payment::class);

        try {
            if (($oPayment->load('fatchip_computop_paypal_express') == false) || ($oPayment->oxpayments__oxactive && $oPayment->oxpayments__oxactive->value === 0)) {
                return false;
            }

            $bIsPaymentValid = $oPayment->isValidPayment(
                null,
                \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId(),
                $oBasket->getUser(),
                $oBasket->getPriceForPayment(),
                $oBasket->getShippingId()
            );

            return $bIsPaymentValid;

        } catch (\Exception $exception) {
            Registry::getLogger()->error('PaypalExpress: isActive: ' . $exception->getMessage());
        }

        return false;
    }
}
