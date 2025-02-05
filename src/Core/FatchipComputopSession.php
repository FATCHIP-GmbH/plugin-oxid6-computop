<?php
namespace Fatchip\ComputopPayments\Core;

use Fatchip\CTPayment\CTPaymentService;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class FatchipComputopSession extends FatchipComputopSession_parent {
    protected $fatchipComputopConfig;
    protected $fatchipComputopSession;
    protected $fatchipComputopShopConfig;
    protected $fatchipComputopPaymentId;
    protected $fatchipComputopPaymentClass;
    protected $fatchipComputopShopUtils;
    protected $fatchipComputopLogger;
    public $fatchipComputopSilentParams;
    protected $fatchipComputopPaymentService;


        protected function _allowSessionStart()
        {
            $len = Registry::getRequest()->getRequestParameter('Len');
            $data = Registry::getRequest()->getRequestParameter('Data');
            $paymentClass = Registry::getRequest()->getRequestParameter('cl');
            if ($len && $data && $paymentClass === 'fatchip_computop_redirect' && $_SERVER['HTTP_REFERER'] === 'https://www.computop-paygate.com/') {
                $config = new Config();
                $this->fatchipComputopConfig = $config->toArray();
                $this->fatchipComputopPaymentService = new CTPaymentService($this->fatchipComputopConfig);
                $response = $this->fatchipComputopPaymentService->getRequest();
                if ($response && $response->getSessionId()) {
                    return false;
                }
            }
          return  parent::_allowSessionStart();

        }
    public function unsetSessionVars()
    {

        $sessionVars = [
            'FatchipComputopErrorCode',
            'FatchipComputopErrorMessage',
            'paymentid',
            'sess_challenge',
            Constants::CONTROLLER_PREFIX . 'DirectResponse',
            Constants::CONTROLLER_PREFIX . 'RedirectResponse',
            Constants::CONTROLLER_PREFIX . 'DirectRequest',
            Constants::CONTROLLER_PREFIX . 'RedirectUrl',
            Constants::CONTROLLER_PREFIX . 'PpeFinished',
        ];

        foreach ($sessionVars as $var) {
            $this->deleteVariable($var);
        }
    }

    public function cleanUpPPEOrder()
    {
        $orderId = $this->getVariable('sess_challenge');

        if ($orderId) {
            $oOrder = oxNew(Order::class);
            $oOrder->delete($orderId);
        }

        $this->deleteVariable(Constants::CONTROLLER_PREFIX . 'PpeOngoing');
        $this->initNewSession();
    }

    public function handlePaymentSession()
    {
        if ($this->getVariable(Constants::CONTROLLER_PREFIX . 'DirectResponse')) {
            $this->unsetSessionVars();
        }

        if ($this->getVariable(Constants::CONTROLLER_PREFIX . 'PpeFinished') === 1 && $this->getVariable(Constants::CONTROLLER_PREFIX . 'PpeOngoing')) {
            $this->unsetSessionVars();
        }

        if ($this->getVariable(Constants::CONTROLLER_PREFIX . 'RedirectUrl')) {
            $this->cleanUpPPEOrder();
            $this->unsetSessionVars();
            Registry::getUtilsView()->addErrorToDisplay('FATCHIP_COMPUTOP_PAYMENTS_PAYMENT_CANCEL');
        }

        $this->deleteVariable(Constants::CONTROLLER_PREFIX . 'RedirectResponse');
        $this->deleteVariable(Constants::CONTROLLER_PREFIX . 'DirectRequest');

        if ($errorCode = $this->getVariable('FatchipComputopErrorCode')) {
            $errorMessage = $this->getVariable('FatchipComputopErrorMessage');
            $this->unsetSessionVars();
            $errorText = $errorCode === 22890703 ? 'FATCHIP_COMPUTOP_PAYMENTS_PAYMENT_CANCEL' : "$errorCode-$errorMessage";
            Registry::getUtilsView()->addErrorToDisplay($errorText);
        }
    }
}
