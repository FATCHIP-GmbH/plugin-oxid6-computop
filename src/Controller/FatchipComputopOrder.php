<?php

namespace Fatchip\ComputopPayments\Controller;

use Exception;
use Fatchip\ComputopPayments\Core\Logger;
use Fatchip\ComputopPayments\Model\ApiLog;
use Fatchip\ComputopPayments\Repository\ApiLogRepository;
use Fatchip\CTPayment\CTAddress\CTAddress;
use Fatchip\CTPayment\CTEnums\CTEnumEasyCredit;
use Fatchip\CTPayment\CTPaymentMethods\AmazonPay;
use Fatchip\CTPayment\CTPaymentService;
use Fatchip\CTPayment\CTResponse;
use OxidEsales\Eshop\Application\Model\Address;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;
use Fatchip\ComputopPayments\Core\Config;
use Fatchip\ComputopPayments\Core\Constants;
use Fatchip\CTPayment\CTOrder\CTOrder;
use Fatchip\CTPayment\CTEnums\CTEnumStatus;
use Fatchip\CTPayment\CTPaymentMethodsIframe\CreditCard;
use OxidEsales\EshopCommunity\Core\Di\ContainerFacade;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Exception\ModuleConfigurationNotFoundException;

/**
 * Class OrderController
 * @mixin \OxidEsales\Eshop\Application\Controller\OrderController
 */
class FatchipComputopOrder extends FatchipComputopOrder_parent
{

    protected $fatchipComputopConfig;
    /** @var Basket */
    protected $fatchipComputopBasket;
    protected $fatchipComputopSession;
    /** @var \OxidEsales\Eshop\Core\Config */
    protected $fatchipComputopShopConfig;
    protected $fatchipComputopPaymentId;
    protected $fatchipComputopPaymentClass;
    protected $fatchipComputopShopUtils;
    /** @var Logger */
    protected $fatchipComputopLogger;
    public $fatchipComputopSilentParams;
    /** @var CTPaymentService fatchipComputopPaymentService */
    protected $fatchipComputopPaymentService;


    /**
     * @return void
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     * @throws Exception
     */
    public function init()
    {
        $config = new Config();
        $this->fatchipComputopConfig = $config->toArray();
        $this->fatchipComputopSession = Registry::getSession();
        $this->fatchipComputopBasket = $this->getBasket();
        $this->fatchipComputopShopConfig = Registry::getConfig();
        $this->fatchipComputopPaymentId = $this->fatchipComputopBasket->getPaymentId() ?: '';
        $this->fatchipComputopShopUtils = Registry::getUtils();
        $this->fatchipComputopLogger = new Logger();
        $this->fatchipComputopPaymentService = new CTPaymentService($this->fatchipComputopConfig);
        parent::init();
    }

    public function render()
    {
        Registry::getSession()->handlePaymentSession();
        $paymentId = $this->fatchipComputopBasket->getPaymentId();
        if ($this->fatchipComputopPaymentId === 'fatchip_computop_klarna') {
            $this->fatchipComputopPaymentClass = Constants::getPaymentClassfromId($paymentId);
        }

        $len = Registry::getRequest()->getRequestParameter('Len');
        $data = Registry::getRequest()->getRequestParameter('Data');
        if ($this->fatchipComputopPaymentId === 'fatchip_computop_easycredit') {
            if (!empty($len) && !empty($data)) {
                $PostRequestParams = [
                    'Len' => $len,
                    'Data' => $data,
                ];
                $response = $this->fatchipComputopPaymentService->getDecryptedResponse($PostRequestParams);
                $this->fatchipComputopLogger->logRequestResponse($PostRequestParams, $this->fatchipComputopPaymentClass, 'AUTHORIZE_REQUEST', $response);

                $status = $response->getStatus();
                $this->easyCreditHandle($response);
            } else {
                $dynValue = Registry::getSession()->getVariable('dynvalue');
                if ($dynValue) {
                    $this->handleEasycreditRedirect($this->fatchipComputopPaymentId);
                }
            }
        }
        if ($this->fatchipComputopPaymentId === 'fatchip_computop_amazonpay') {
            $this->fatchipComputopPaymentClass = 'AmazonPay';
            /** @todo button "click" should generate a unfinished order **/
            $this->amazonPayButtonAction();
        }

        return parent::render();
    }
    public
    function handleEasycreditRedirect(
        $paymentId
    ) {

        $this->fatchipComputopPaymentClass = Constants::getPaymentClassfromId($paymentId);
        $dynValue = Registry::getSession()->getVariable('dynvalue');
        $oUser = $this->getUser();
        $payment = $this->getPaymentClassForGatewayAction();

        $UrlParams = $this->getUrlParams();

        $ctOrder = $this->createCTOrder();
        $redirectParams = $payment->getRedirectUrlParams();
        $payment->setBillToCustomer($ctOrder);
        if ($payment instanceof PaypalStandard) {
            //   $payment->setPayPalMethod('shortcut');
        }
        $paymentParams = $this->getPaymentParams($oUser, $dynValue);
        $paymentParams['billToCustomer'] = $payment->getBillToCustomer();
        $customParam = $this->getCustomParam($payment->getTransID());
        $params = array_merge($redirectParams, $paymentParams, $customParam, $UrlParams);
        $this->fatchipComputopSession->setVariable(Constants::CONTROLLER_PREFIX . 'RedirectUrlRequestParams', $params);
        if ($this->fatchipComputopConfig['debuglog'] === 'extended') {
            $sessionID = $this->fatchipComputopSession->getId();
            $customerId = $oUser->getFieldData('oxcustnr');
            $order = var_export('', true);
            $paymentName = $this->fatchipComputopPaymentClass;
            $this->fatchipComputopLogger->log(
                'DEBUG',
                'Calling ' . $payment->getCTPaymentURL($params),
                [
                    'payment' => $paymentName,
                    'UserID' => $customerId,
                    'SessionID' => $sessionID,
                    'params' => $params
                ]
            );
        }
        $response = $payment->getHTTPGetURL($params);
        $parts = parse_url($response);
        parse_str($parts['query'], $query);
        $len = $query['Len'];
        $data = $query['Data'];
        $PostRequestParams = [
            'Len'    => $len,
            'Data'   => $data,
        ];
        $responseDec = $this->fatchipComputopPaymentService->getDecryptedResponse($PostRequestParams);
        $this->fatchipComputopLogger->logRequestResponse($params, $this->fatchipComputopPaymentClass, 'AUTHORIZE_REQUEST_REDIRECT', $responseDec);
        // $this->fatchipComputopLogger->logRequestResponse($params, $this->fatchipComputopPaymentClass, 'REDIRECT-STANDARD', $payment);
        $this->fatchipComputopSession->setVariable(Constants::CONTROLLER_PREFIX . 'RedirectUrl', $response);
        $this->fatchipComputopSession->setVariable(Constants::CONTROLLER_PREFIX . 'RedirectResponse', $responseDec);
        Registry::getUtils()->redirect($response, false);

        // return true;
    }
    /**
     * @param CTResponse $response
     * @return void
     */
    protected function easyCreditHandle($response) {
        $dynValue = $this->fatchipComputopSession->getVariable('dynvalue');
        $status = $response->getStatus();
        if (CTEnumStatus::AUTHORIZE_REQUEST === $status) {
            $this->fatchipComputopPaymentClass = 'EasyCredit';

            $oUser = $this->getUser();
            $UrlParams = $this->getUrlParams();
            $payment = $this->getPaymentClassForGatewayAction();
            $redirectParams = $payment->getRedirectUrlParams();
            $paymentParams = $this->getPaymentParams($oUser, $dynValue);
            $customParam = $this->getCustomParam($payment->getTransID());
            $redirectParams = $payment->getRedirectUrlParams();
            $params = array_merge($redirectParams, $paymentParams, $customParam, $UrlParams);
            $ctOrder = $this->createCTOrder();

            $payment = $this->fatchipComputopPaymentService->getIframePaymentClass(
                $this->fatchipComputopPaymentClass,
                $this->fatchipComputopConfig,
                $ctOrder,
                '',
                '',
                '',
                'Test',
                $this->getUserDataParam(),
                CTEnumEasyCredit::EVENTTOKEN_GET
            );

            $oDelivery = $this->fatchipComputopBasket->getCosts('oxdelivery');
            $sDeliveryCosts = $oDelivery === null ? 0.0 : (int)($oDelivery->getBruttoPrice() * 100);

            $sDeliveryCosts = (double)str_replace(
                ',',
                '.',
                $sDeliveryCosts
            );
            $amount = ($this->fatchipComputopBasket->getBruttoSum() * 100)  + ($sDeliveryCosts);
            $amount = (int)($amount);

            $redirectResponse = Registry::getSession()->getVariable('FatchipComputopRedirectResponse');
            $decisionParams = $payment->getDecisionParams($response->getPayID(), $response->getTransID(), $amount, $this->fatchipComputopBasket->getBasketCurrency()->name);
            $mac = $redirectResponse->getMAC();
            $decisionParams['mac'] = $this->fatchipComputopConfig['mac'];
            $responseObject = $this->callComputopService($decisionParams, $payment, 'GET', $payment->getCTCreditCheckURL());

            $decision = json_decode($responseObject->getFinancing(), true);

            if (!($decision['decision']['decisionOutcome'] === 'POSITIVE')) {
                die(var_dump($decision));
            } else {

                $this->addTplParam('FatchipComputopEasyCreditInformation', $this->getConfirmPageInformation($responseObject));
                Registry::getSession()->setVariable('FatchipComputopEasyCreditInformation', $this->getConfirmPageInformation($responseObject));
                Registry::getSession()->setVariable('fatchipComputopEasyCreditPayId',$responseObject);
            }
        } else {
            die(var_dump($response));
        }
    }
    public function creditCardSilent() {
        try {
            $ctOrder = $this->createCTOrder();
            $amount = $this->calculateTotalAmount();

            $paymentId = $this->fatchipComputopBasket->getPaymentId();
            $paymentClass = Constants::getPaymentClassfromId($paymentId);
            /** @var CreditCard $payment */
            $payment = $this->initializePayment($ctOrder, $paymentClass);
            $currency = $this->fatchipComputopBasket->getBasketCurrency()->name;
            $request = $this->createAuthorizeRequest($payment, $paymentClass, $amount, $currency, $ctOrder);
            $this->fatchipComputopLogger->logRequestResponse($request, $paymentClass, 'AUTH-REQUEST', []);

            $this->fatchipComputopSession->setVariable(Constants::CONTROLLER_PREFIX . 'DirectRequest', $request);
            $jsonEncoded = $payment->getPaynowURLasJson($request);
            echo $jsonEncoded;
            exit;
        } catch (Exception $e) {
            error_log('Error in creditCardSilent: ' . $e->getMessage());
            echo json_encode(['error' => 'An error occurred while processing the payment.']);
            exit;
        }
    }

    private function calculateTotalAmount() {
        try {
            $oDelivery = $this->fatchipComputopBasket->getCosts('oxdelivery');
            $sDeliveryCosts = $oDelivery === null ? 0.0 : (int)($oDelivery->getBruttoPrice() * 100);
            $sDeliveryCosts = (double)str_replace(',', '.', $sDeliveryCosts);

            $totalAmount = (int)(($this->fatchipComputopBasket->getBruttoSum() * 100) + $sDeliveryCosts);
            return $totalAmount;
        } catch (Exception $e) {
            throw new Exception('Error calculating total amount: ' . $e->getMessage());
        }
    }

    private function initializePayment($ctOrder, $paymentClass) {
        try {
            $payment = $this->fatchipComputopPaymentService->getIframePaymentClass(
                $paymentClass,
                $this->fatchipComputopConfig,
                $ctOrder,
                '',
                '',
                '',
                'Test:0000',
                $this->getUserDataParam(),
                CTEnumEasyCredit::EVENTTOKEN_GET
            );
            if ($paymentClass !=='AmazonPay') {
                $payment->setCredentialsOnFile();
            }
            return $payment;
        } catch (Exception $e) {
            throw new Exception('Error initializing payment: ' . $e->getMessage());
        }
    }

    /**
     * @param CreditCard $payment
     * @param $paymentClass
     * @param $amount
     * @param $currency
     * @param $ctOrder
     * @return array
     * @throws Exception
     */
    private function createAuthorizeRequest($payment, $paymentClass, $amount, $currency, $ctOrder) {
        $captureMode = 'MANUAL';
        try {
            $request = $payment->getAuthorizeParams(
                $paymentClass,
                Registry::getSession()->getSessionChallengeToken(),
                $amount,
                $currency,
                $captureMode
            );

            $request = $this->addAdditionalRequestParameters($request, $payment, $ctOrder);

            $urlParams = $this->getUrlParams(true);
            $request = array_merge($request, $urlParams);

            return $request;
        } catch (Exception $e) {
            throw new Exception('Error creating authorize request: ' . $e->getMessage());
        }
    }

    /**
     * @param $request
     * @param $payment CreditCard
     * @param $ctOrder CTOrder
     * @return array
     */
    private function addAdditionalRequestParameters($request, $payment, $ctOrder) {
        $payment->setBillToCustomer($ctOrder);
        $request['EtiId'] = $payment->getEtiId();
        $request['ReqId'] = $payment->getTransID();
        $request['transID'] = $payment->getTransID();
        $customParam = $this->getCustomParam($payment->getTransID());
        $request['custom'] = $customParam['custom'];
        $request['billingAddress'] = $payment->getBillingAddress();
        $request['shippingAddress'] = $payment->getShippingAddress();
        $request['billToCustomer'] = $payment->getBillToCustomer();
        $request['msgVer'] = '2.0';
        $request['orderDesc'] = $payment->getOrderDesc();
        $request['credentialOnFile'] = $payment->getCredentialsOnFile();
        $request['template'] = 'ct_responsive';
        $request['Response'] = 'encrypt';
        return $request;
    }
    private function isAutoCaptureEnabled()
    {
        $autoCaptureConfigKey = 'creditCardCaption';
        $autoCaptureValue = $this->fatchipComputopConfig[$autoCaptureConfigKey] ?? null;
        if ($this->fatchipComputopPaymentId === 'fatchip_computop_amazonpay') {
            $autoCaptureConfigKey = 'amazonCaptureType';
        }

        return ($autoCaptureValue === 'AUTO');
    }
    public function execute() {
        $basket = Registry::getSession()->getBasket();
        if (empty($basket->getPaymentId())) {
        }
        $paymentId = $basket->getPaymentId();
        $ret = null;
        $lastschrift = false;
        if (Constants::isFatchipComputopRedirectPayment($paymentId) || $paymentId !== 'fatchip_computop_easycredit') {
            if($paymentId === 'fatchip_computop_lastschrift'){
                $lastschrift = true;
                $response =      $this->lastschriftAction();
            }
            if ($paymentId === 'fatchip_computop_amazonpay') {
                $_POST['stoken'] = Registry::getSession()->getSessionChallengeToken();
                $_POST['FatchipComputopLen'] = Registry::getRequest()->getRequestParameter('Len');
                $_POST['FatchipComputopData'] = Registry::getRequest()->getRequestParameter('Data');
                $_POST['sDeliveryAddressMD5'] = $this->getUser()->getEncodedDeliveryAddress();
            }
            $ret = parent::execute();
            // if order is validated and finalized complete Order on thankyou
            if ($ret === 'thankyou' || $ret === 'thankyou?mailerror=1') {
                $this->fatchipComputopSession->deleteVariable(Constants::CONTROLLER_PREFIX .'RedirectUrl');
                if($lastschrift === false){
                    $response = $this->fatchipComputopSession->getVariable(Constants::CONTROLLER_PREFIX . 'RedirectResponse');
                    $this->fatchipComputopSession->deleteVariable(Constants::CONTROLLER_PREFIX .'RedirectResponse');
                }
                if ($response) {
                    if($lastschrift || $paymentId === 'fatchip_computop_paypal_express'){
                        $orderOxId = Registry::getSession()->getVariable('sess_challenge');
                    } else {
                        $orderOxId = $response->getSessionId();
                    }
                    $order = oxNew(Order::class);
                    $oUser = $this->getUser();
                    if ($order->load($orderOxId)) {
                        /** @var \Fatchip\ComputopPayments\Model\Order $order */
                        if (empty($order->getFieldData('oxordernr'))) {
                            $orderNumber = $order->getFieldData('oxordernr');
                        } else {
                            $orderNumber = $order->getFieldData('oxordernr');
                        }
                        //     $order->customizeOrdernumber($response);
                        $order->updateOrderAttributes($response);
                        $order->updateComputopFatchipOrderStatus('FATCHIP_COMPUTOP_PAYMENTSTATUS_RESERVED');
                        $order->autocapture($oUser, false);
                        $this->updateRefNrWithComputop($order);
                    }
                }
            }
        }else{
            if($paymentId === 'fatchip_computop_paypal_express'){
                $ret = parent::execute();
                // if order is validated and finalized complete Order on thankyou
                if ($ret === 'thankyou' || $ret === 'thankyou?mailerror=1') {
                    /** @var CTResponse $oResponse */
                    $oResponse = $this->fatchipComputopSession->getVariable(Constants::CONTROLLER_PREFIX . 'RedirectResponse');
                    if ($oResponse) {
                        $oOrder = oxNew(Order::class);
                        if($oOrder->loadByTransID($oResponse->getTransID())){
                            //    $oOrder->customizeOrdernumber($oResponse);
                            $oOrder->updateOrderAttributes($oResponse);
                            $oOrder->updateComputopFatchipOrderStatus(Constants::PAYMENTSTATUSRESERVED);
                            $this->updateRefNrWithComputop($oOrder);
                            $oOrder->autocapture($oOrder->getUser(), false);
                        }
                    }
                }
            }
            if($paymentId === 'fatchip_computop_easycredit') {
                $ret = parent::execute();
                if ($ret === 'thankyou' || $ret === 'thankyou?mailerror=1') {
                    /** @var CTResponse $oResponse */
                    $oResponse = $this->fatchipComputopSession->getVariable(Constants::CONTROLLER_PREFIX . 'DirectResponse');
                    if ($oResponse) {
                        $params = $this->fatchipComputopSession->getVariable(Constants::CONTROLLER_PREFIX . 'DirectRequest');
                        $response =  $this->fatchipComputopSession->getVariable(Constants::CONTROLLER_PREFIX . 'DirectResponse');
                        $this->fatchipComputopLogger->logRequestResponse($params,'EasyCredit','AUTH_ACCEPT',$response);

                        $orderOxId = Registry::getSession()->getVariable('sess_challenge');
                        $oOrder = oxNew(Order::class);
                        if($oOrder->load($orderOxId)){
                            //  $oOrder->customizeOrdernumber($oResponse);
                            $oOrder->updateOrderAttributes($oResponse);
                            $oOrder->updateComputopFatchipOrderStatus(Constants::PAYMENTSTATUSRESERVED);
                            $this->updateRefNrWithComputop($oOrder);
                            $oOrder->autocapture($oOrder->getUser(), false);
                        }
                    }
                }
            }
        }

        // in all other cases return parent
        if (!$ret) {
            $ret = parent::execute();
        }
        if (Registry::getSession()->getVariable(Constants::CONTROLLER_PREFIX.'PpeOngoing')) {
            Registry::getSession()->deleteVariable(Constants::CONTROLLER_PREFIX.'PpeOngoing');
            Registry::getSession()->setVariable(Constants::CONTROLLER_PREFIX.'PpeFinished',1);
        }
        return $ret;
    }


    public
    function lastschriftAction()
    {
        $dynValue = $this->fatchipComputopSession->getVariable('dynvalue');
        $oUser = $this->getUser();
        $payment = $this->getPaymentClassForGatewayAction();
        $payment->setAccBank($dynValue['fatchip_computop_lastschrift_bankname']);
        $payment->setAccOwner($dynValue['fatchip_computop_lastschrift_bank_account_holder']);
        $payment->setIBAN($dynValue['fatchip_computop_lastschrift_iban']);
        $params = $payment->getRedirectUrlParams();
        $customParam = $this->getCustomParam($payment->getTransID());
        $params['custom'] = $customParam['custom'];
        if ($this->fatchipComputopConfig['debuglog'] === 'extended') {
            $sessionID = $this->fatchipComputopSession->getId();
            $customerId = $oUser->getId();
            $paymentName = $this->fatchipComputopPaymentClass;
            $this->fatchipComputopLogger->log(
                'DEBUG',
                'Redirecting to ' . $payment->getCTPaymentURL($params),
                [
                    'payment' => $paymentName,
                    'UserID' => $customerId,
                    'basket' => '',
                    'SessionID' => $sessionID,
                    'params' => $params
                ]
            );
        }
        $this->fatchipComputopSession->setVariable(Constants::CONTROLLER_PREFIX . 'DirectRequest', $params);
        $response = $payment->callComputop($params, $payment->getCTPaymentURL());

        $this->fatchipComputopLogger->logRequestResponse($params,$paymentName,'AUTH',$response);
        return $response;
    }

    /**
     * @param $className
     * @return CTPaymentMethod
     */
    public
    function getPaymentClass(
        $className
    ) {
        $class = 'Fatchip\\CTPayment\\CTPaymentMethods\\' . $className;
        return new $class();
    }

    /**
     * TODO : move to helper or order
     * @param Basket $basket
     * @return CTOrder
     */
    protected
    function createCTOrder()
    {
        $ctOrder = new CTOrder();
        $oUser = $this->getUser();
        $config = oxNew(Config::class);
        /** @var Basket $oBasket */
        $oBasket = $this->fatchipComputopBasket;

        $ctOrder->setAmount((int)($oBasket->getPrice()->getBruttoPrice() * 100));
        $ctOrder->setCurrency($this->fatchipComputopBasket->getBasketCurrency()->name);
        // try catch in case Address Splitter retrun exceptions
        try {
            $ctOrder->setBillingAddress($this->getCTAddress($oUser));
            $delAddressId = $this->fatchipComputopSession->getVariable('deladrid');
            if ($delAddressId) {
                $deliveryAddress = oxNew(Address::class);
                $deliveryAddress->load($delAddressId);
                $ctOrder->setShippingAddress($this->getCTAddress($oUser, $deliveryAddress));
            } else {
                $ctOrder->setShippingAddress($this->getCTAddress($oUser));
            }
        } catch (Exception $e) {
            $ctError = [];
            $ctError['CTErrorCode'] = $e->getMessage();
            return $this->forward('shippingPayment', 'checkout', null, ['CTError' => $ctError]);
        }
        $ctOrder->setEmail($oUser->oxuser__oxusername->value);
        $ctOrder->setCustomerID($oUser->oxuser__oxcustnr->value);
        // Mandatory for paypalStandard
        $orderDesc = $this->fatchipComputopShopConfig->getActiveShop()->oxshops__oxname->value . ' '
            . $this->fatchipComputopShopConfig->getActiveShop()->oxshops__oxversion->value;
        if($config->getCreditCardTestMode()) {
            $ctOrder->setOrderDesc('Test:0000');
        } else {
            $ctOrder->setOrderDesc($orderDesc);

        }
        return $ctOrder;
    }

    /**
     * creates a CTAddress object from a Oxid address array
     * @param $user
     * @return CTAddress
     * @throws \Exception
     */
    public
    function getCTAddress(
        $oUser,
        $deliveryAdress = null
    ) {
        $oxcountryid = $oUser->getFieldData('oxcountryid');
        $oCountry = oxNew(Country::class);
        $oCountry->load($oxcountryid);
        $oxisoalpha2 = $oCountry->getFieldData('oxisoalpha2');
        $oxisoalpha3 = $oCountry->getFieldData('oxisoalpha3');
        if ($deliveryAdress) {
            return new CTAddress(
                ($deliveryAdress->oxaddress__oxsal->value == 'MR') ? 'Herr' : 'Frau',
                $deliveryAdress->oxaddress__oxcompany->value,
                $deliveryAdress->oxaddress__oxfname->value,
                $deliveryAdress->oxaddress__oxlname->value,
                $deliveryAdress->oxaddress__oxstreet->value,
                $deliveryAdress->oxaddress__oxstreetnr->value,
                $deliveryAdress->oxaddress__oxzip->value,
                $deliveryAdress->oxaddress__oxcity->value,
                $oxisoalpha2,
                $oxisoalpha3,
                $deliveryAdress->oxaddress__oxaddinfo->value
            );
        } else {
            return new CTAddress(
                ($oUser->oxuser__oxsal->value == 'MR') ? 'Herr' : 'Frau',
                $oUser->oxuser__oxcompany->value,
                $oUser->oxuser__oxfname->value,
                $oUser->oxuser__oxlname->value,
                $oUser->oxuser__oxstreet->value,
                $oUser->oxuser__oxstreetnr->value,
                $oUser->oxuser__oxzip->value,
                $oUser->oxuser__oxcity->value,
                $oxisoalpha2,
                $oxisoalpha3,
                $oUser->oxuser__oxaddinfo->value
            );
        }
    }

    protected
    function getParamBrowserInfo(
        $browserData
    ) {
        // @see
        $acceptHeaders = $_SERVER['HTTP_ACCEPT'];
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $javaEnabled = $browserData['fatchip_computop_creditcard_javaEnabled'];
        $javaScriptEnabled = $browserData['fatchip_computop_creditcard_javascriptEnabled']; // see above
        $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $acceptedLangs = explode(',', $acceptLang);
        $language = array_shift($acceptedLangs);
        $colorDepth = $this->getParamColorDepth((int)$browserData['fatchip_computop_creditcard_colorDepth']);
        $screenHeight = $browserData['fatchip_computop_creditcard_screenHeight'];
        $screenWidth = $browserData['fatchip_computop_creditcard_screenWidth'];
        $timeZoneOffset = $browserData['fatchip_computop_creditcard_timeZoneOffset'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if ($browserData['javaScriptEnabled'] === "false") {
            $browserInfoParams = array(
                'acceptHeaders' => $acceptHeaders,
                'ipAddress' => $ipAddress,
                'javaScriptEnabled' => ($javaScriptEnabled === "true") ? true : false,
                'language' => $language,
                'userAgent' => $userAgent,
            );
        } else {
            $browserInfoParams = array(
                'acceptHeaders' => $acceptHeaders,
                'ipAddress' => $ipAddress,
                'javaEnabled' => ($javaEnabled === "true") ? true : false,
                'javaScriptEnabled' => ($javaScriptEnabled === "true") ? true : false,
                'language' => $language,
                'colorDepth' => (int)$colorDepth,
                'screenHeight' => (int)$screenHeight,
                'screenWidth' => (int)$screenWidth,
                'timeZoneOffset' => $timeZoneOffset,
                'userAgent' => $userAgent,
            );
        }
        return base64_encode(json_encode($browserInfoParams));
    }

    /**
     * The computop API only accepts the values
     * 1,4,8,15,16,24,32 and 48
     * This method returns the next higher fitting value
     * when there is no exect match
     *
     * @param int $colorDepth
     * @return int $apiColorDepth
     */
    private
    function getParamColorDepth(
        $colorDepth
    ) {
        $acceptedValues = [1, 4, 8, 15, 16, 24, 32, 48];

        if (in_array($colorDepth, $acceptedValues, true)) {
            $apiColorDepth = $colorDepth;
        } elseif ($colorDepth <= 1) {
            $apiColorDepth = 1;
        } elseif ($colorDepth <= 4) {
            $apiColorDepth = 4;
        } elseif ($colorDepth <= 8) {
            $apiColorDepth = 8;
        } elseif ($colorDepth <= 15) {
            $apiColorDepth = 15;
        } elseif ($colorDepth <= 24) {
            $apiColorDepth = 24;
        } elseif ($colorDepth <= 32) {
            $apiColorDepth = 32;
        } else {
            $apiColorDepth = 48;
        }
        return $apiColorDepth;
    }

    public
    function getSilentParams()
    {
        $test = $this->fatchipComputopSilentParams;
        return $test;
    }

    public
    function twintAction()
    {
        $payment = $this->getPaymentClassForGatewayAction();
        $params = $payment->getRedirectUrlParams();
        $oUser = $this->getUser();
        $dynValue = $this->fatchipComputopSession->getVariable('dynvalue');

        if ($this->fatchipComputopConfig['debuglog'] === 'extended') {
            $sessionID = $this->fatchipComputopSession->getId();
            $customerId = $oUser->getId();
            $paymentName = $this->fatchipComputopPaymentClass;
            $basketExport = var_export($this->fatchipComputopBasket, true);
            $this->fatchipComputopLogger->log(
                'DEBUG',
                'Redirecting to ' . $payment->getHTTPGetURL($params),
                [
                    'payment' => $paymentName,
                    'UserID' => $customerId,
                    'SessionID' => $sessionID,
                    'parmas' => $params
                ]
            );
        }
        $requestParams = $payment->getRedirectUrlParams();
        $response = $payment->prepareComputopRequest($requestParams, $payment->getCTPaymentURL());
        Registry::getUtils()->redirect($response, false, 302);
    }

    public function amazonPayButtonAction()
    {

        $dynValue = $this->fatchipComputopSession->getVariable('dynvalue');

        $oDelivery = $this->fatchipComputopBasket->getCosts('oxdelivery');
        $sDeliveryCosts = $oDelivery === null ? 0.0 : (int)($oDelivery->getBruttoPrice() * 100);

        $taxAmount = $this->fatchipComputopBasket->getBruttoSum() - $this->fatchipComputopBasket->getNettoSum();
        $amount = $this->fatchipComputopBasket->getPrice()->getBruttoPrice() * 100;
        $amount = (int)$amount;
        $currency = 'EUR';
        $shopUrl =  $this->fatchipComputopShopConfig->getShopUrl().'computop/amazonpay/return/';
        $oUser = $this->getUser();
        $UrlParams = $this->getUrlParams();
        $ctOrder = $this->createCTOrder();
        $payment = $this->initializePayment($ctOrder, 'AmazonPay');

        /** @var AmazonPay $payment */
        $params = $payment->getAmazonInitParams(
            $this->fatchipComputopConfig['merchantID'],
            CreditCard::generateTransID(),
            'EU',
            $amount,
            $currency,
            $UrlParams['UrlSuccess'],
            $UrlParams['UrlFailure'],
            $UrlParams['UrlNotify'],
            $UrlParams['UrlCancel'],
            $shopUrl
        );
        $params['shippingAddress'] = $payment->getShippingAddress();
        $params['SDZipcode'] =$ctOrder->getShippingAddress()->getZip();
        $params['SDStreet'] =$ctOrder->getShippingAddress()->getStreet();
        $params['SDCountryCode'] =$ctOrder->getShippingAddress()->getCountryCode();
        $params['SDCity'] =$ctOrder->getShippingAddress()->getCity();
        $params['SDPhone'] = $dynValue['telephone_number'];
        $params['Name'] =$ctOrder->getShippingAddress()->getFirstname()." ".$ctOrder->getShippingAddress()->getLastname();
        $params['checkoutMode'] = 'ProcessOrder';

        $params['Scope'] = ['Name'];
        if ($this->fatchipComputopConfig['debuglog'] === 'extended') {
            $sessionID = $this->fatchipComputopSession->getId();
            $customerId = $oUser->getId();
            $paymentName = $this->fatchipComputopPaymentClass;
            $this->fatchipComputopLogger->log(
                'DEBUG',
                'Redirecting to ' . $payment->getCTPaymentURL($params),
                [
                    'payment' => $paymentName,
                    'UserID' => $customerId,
                    'SessionID' => $sessionID,
                    'parmas' => $params
                ]
            );
        }
        $response = $this->requestAmazonpayInit($params, $payment);
        $this->fatchipComputopSession->setVariable('FatchipComputopResponse', $response);
    }

    public function getUrlParams($redirect = false)
    {
        $paymentClass = $this->fatchipComputopPaymentId;
        $sShopUrl = $this->fatchipComputopShopConfig->getShopUrl();

        if ($paymentClass === 'fatchip_computop_easycredit') {
            $sShopUrl = $this->fatchipComputopShopConfig->getShopUrl();
            $URLSuccess = $sShopUrl . 'index.php?cl=order&sid=' . Registry::getSession()->getId();
        } else {
            if ($redirect === true) {
                $paymentClass = Constants::GENERAL_PREFIX . 'redirect';
            }
            $URLSuccess = $sShopUrl . 'index.php?cl=' . $paymentClass . '&sid=' . Registry::getSession()->getId();
            $URLFailure = $sShopUrl . 'index.php?cl=' . $paymentClass . '&sid=' . Registry::getSession()->getId();
        }

        $URLCancel = $sShopUrl . 'index.php?cl=' . $paymentClass . '&sid=' . Registry::getSession()->getId();
        $URLNotify = $sShopUrl . 'index.php?cl=' . Constants::GENERAL_PREFIX . 'notify' . '&sid=' . Registry::getSession()->getId();
        if ($this->fatchipComputopConfig['creditCardMode'] === 'IFRAME') {
            return [
                'UrlSuccess' => $URLSuccess,
                'UrlFailure' => $URLFailure,
                'UrlNotify' => $URLNotify,
            ];
        }
        return [
            'UrlSuccess' => $URLSuccess,
            'UrlFailure' => $URLFailure,
            'UrlNotify' => $URLNotify,
            'UrlCancel' => $URLCancel,
            'UrlBack' => $URLCancel,
        ];
    }


    public
    function getCustomParam($transid)
    {
        $this->fatchipComputopSession->setVariable(Constants::GENERAL_PREFIX . 'TransId', $transid);
        $orderOxId = Registry::getSession()->getVariable('sess_challenge');
        if ($delAdressMd5 = Registry::getRequest()->getRequestParameter('sDeliveryAddressMD5') !== null) {
            $delAdressMd5 = Registry::getRequest()->getRequestParameter('sDeliveryAddressMD5');
        }
        $custom = base64_encode(
            'session='.$orderOxId
            . '&transid=' . $transid
            . '&stoken='.Registry::getSession()->getSessionChallengeToken()
            .'&delAdressMd5='.$delAdressMd5);
        return ['custom' => 'Custom='.$custom];
    }

    /**
     * Returns and brings basket positions into appropriate form
     *
     *
     * @return array<int, array{reference: mixed, name: mixed, quantity: mixed, unit_price: float|int, tax_rate: float|int, total_amount: float|int}>
     */
    public
    function getKlarnaOrderlinesParams(): string
    {
        foreach ($this->fatchipComputopBasket->getContents() as $oBasketItem) {
            $oArticle = $oBasketItem->getArticle();

            $articleListArray['order_lines'][] = [
                'reference' => $oArticle->oxarticles__oxartnum->value,
                'name' => $oBasketItem->getTitle(),
                'quantity' => (int)$oBasketItem->getAmount(),
                'unit_price' => (int)($oBasketItem->getUnitPrice()->getBruttoPrice() * 100),
                'tax_rate' => (int)($oBasketItem->getVatPercent() * 100),
                'total_amount' => (int)($oBasketItem->getPrice()->getBruttoPrice() * 100 * $oBasketItem->getAmount()),
                'total_tax_amount' => (int)round(
                    (($oBasketItem->getPrice()->getBruttoPrice() - $oBasketItem->getPrice()->getNettoPrice()) * 100)
                )
            ];
        }

        $oDelivery = $this->fatchipComputopBasket->getCosts('oxdelivery');
        $sDeliveryCosts = $oDelivery === null ? 0.0 : $oDelivery->getBruttoPrice();

        $sDeliveryCosts = (double)str_replace(
            ',',
            '.',
            $sDeliveryCosts
        );

        if ($sDeliveryCosts > 0) {
            $deliveryTax = (int)(round(($sDeliveryCosts / 1.19 * 0.19 * 100), 2));
            $articleListArray['order_lines'][] = [
                'name' => $oBasketItem->getTitle(),
                'quantity' => 1,
                'unit_price' => (int)($sDeliveryCosts * 100),
                'total_amount' => (int)($sDeliveryCosts * 100),
                'tax_rate' => (int)($oDelivery->getVat() * 100),
                'total_tax_amount' => $deliveryTax
            ];
        }

        $articleList = base64_encode(json_encode($articleListArray));

        return $articleList;
    }

    /**
     * @param $params
     * @return CTResponse
     */
    public
    function requestKlarna(
        $params,
        $payment
    ) {
        $response = $payment->prepareComputopRequest($params, $payment->getCTPaymentURL());

        return $response;
    }

    /**
     * @param $params
     * @return CTResponse
     */
    public
    function requestAmazonpayInit(
        $params,
        $payment
    ) {
        $response = $payment->callComputop($params, $payment->getCTPaymentURL());

        return $response;
    }

    /**
     * Calculates the Klarna tax amount by adding the tax amounts of each position in the article list.
     *
     * @param $articleList
     *
     * @return float
     */
    public
    static function calculateTaxAmount(
        $articleList
    ) {
        $taxAmount = 0;
        $articleList = json_decode(base64_decode($articleList), true);
        foreach ($articleList['order_lines'] as $article) {
            $itemTaxAmount = $article['total_tax_amount'];
            $taxAmount += $itemTaxAmount;
        }

        return $taxAmount;
    }

    public function getFatchipComputopShopConfigMode()
    {
        if (is_array($this->fatchipComputopConfig)) {
            return $this->fatchipComputopConfig['creditCardMode'];
        }
        return null;

    }

    /**
     * The RefNr for Computop has to be equal to the ordernumber.
     * Because the ordernumber is only known after successful payments
     * and successful saveOrder() call update the RefNr AFTER order creation
     *
     * @param Order $order Oxid order
     * @param string $paymentClass name of the payment class
     *
     * @return CTResponse
     * @throws Exception
     */
    private
    function updateRefNrWithComputop(
        $order,
    ) {
        if (!$order) {
            return null;
        }
        $paymentId = $order->getFieldData('oxpaymenttype');
        if ($this->fatchipComputopPaymentClass === null) {
            $paymentClass = Constants::getPaymentClassfromId($paymentId);
        } else {
            $paymentClass = $this->fatchipComputopPaymentClass;
        }
        $ctOrder = $this->createCTOrder();
        if ($paymentClass !== 'PayPalExpress'
            && $paymentClass !== 'AmazonPay'
        ) {
            $payment = $this->fatchipComputopPaymentService->getIframePaymentClass($paymentClass, $this->fatchipComputopConfig, $ctOrder);
        } else {
            $payment = $this->fatchipComputopPaymentService->getPaymentClass($paymentClass);
        }
        $payID = $order->getFieldData('fatchip_computop_payid');
        if ($paymentClass === 'KlarnaPayments') {
            $payment->setTransID($order->getFieldData('fatchip_computop_transid'));
        }
        $RefNrChangeParams = $payment->getRefNrChangeParams($payID, $order->getFieldData('oxordernr'));
        $RefNrChangeParams['EtiId'] = $this->getUserDataParam();


        return $this->callComputopService(
            $RefNrChangeParams,
            $payment,
            'REFNRCHANGE',
            $payment->getCTRefNrChangeURL()
        );
    }

    public function getUserDataParam()
    {
        $moduleVersion = '';
        $activeShop = $this->fatchipComputopShopConfig->getActiveShop();
        $shopName = $activeShop->getFieldData('oxname');
        $shopVersion = $activeShop->getFieldData('oxversion');

        return sprintf('%s %s %s', $shopName, $shopVersion, $moduleVersion);
    }


    protected
    function getPaymentClassForGatewayAction()
    {
        $ctOrder = $this->createCTOrder();

        if ($this->fatchipComputopConfig['debuglog'] === 'extended') {
            $sessionID = $this->fatchipComputopSession->getId();
            $oUser = $this->getUser();
            $customerId = $oUser->getId();
            $paymentClass = Constants::getPaymentClassfromId($this->fatchipComputopPaymentId);
            $order = var_export($ctOrder, true);
            $this->fatchipComputopLogger->log(
                'DEBUG',
                'creating Order : ',
                ['payment' => $paymentClass, 'UserID' => $customerId, 'order' => $order, 'SessionID' => $sessionID]
            );
        }

        $oUser = $this->getUser();
        $urlParams = $this->getUrlParams();
        $payment = $this->fatchipComputopPaymentService->getIframePaymentClass(
            Constants::getPaymentClassfromId($this->fatchipComputopPaymentId),
            $this->fatchipComputopConfig,
            $ctOrder,
            $urlParams['UrlSuccess'],
            $urlParams['UrlFailure'],
            $urlParams['UrlNotify'],
            $this->fatchipComputopShopConfig->getActiveShop()->oxshops__oxname->value . ' '
            . $this->fatchipComputopShopConfig->getActiveShop()->oxshops__oxversion->value,
            $this->getUserDataParam(),
            null,
            null,
            null,
            $urlParams['UrlCancel']
        );
        return $payment;
    }

    public
    function getPaymentParams(
        $oUser,
        $dynValue
    ) {

        switch ($this->fatchipComputopPaymentId) {
            case "fatchip_computop_lastschrift":
                return [
                    'AccBank' => $dynValue['fatchip_computop_lastschrift_bankname'],
                    'AccOwner' => $dynValue['fatchip_computop_lastschrift_bank_account_holder'],
                    'IBAN' => $dynValue['fatchip_computop_lastschrift_iban'],
                ];
            case "fatchip_computop_klarna";
                $aOrderlines = $this->getKlarnaOrderlinesParams();
                $taxAmount = $this->calculateTaxAmount($aOrderlines);
                $oxcountryid = $oUser->getFieldData('oxcountryid');
                $oCountry = oxNew(Country::class);
                $oCountry->load($oxcountryid);
                $oxisoalpha2 = $oCountry->getFieldData('oxisoalpha2');

                return [
                    'TaxAmount' => $taxAmount,
                    'ArticleList' => $aOrderlines,
                    'Account' => $this->fatchipComputopConfig['klarnaaccount'],
                    'bdCountryCode' => $oxisoalpha2,
                ];

            case "fatchip_computop_easycredit":
                return [
                    'DateOfBirth' => $dynValue['fatchip_computop_easycredit_birthdate_year'] . '-' . $dynValue['fatchip_computop_easycredit_birthdate_month'] . '-' . $dynValue['fatchip_computop_easycredit_birthdate_day'],
                    'EventToken' => CTEnumEasyCredit::EVENTTOKEN_INIT,
                ];

            case "fatchip_computop_paypal_standard":
                return [
                    'TxType' => 'Order',
                    'Account' => '',
                ];

            case "FATCHIP_COMPUTOP_PAYMENTSTATUS_PAID":
                break;
        }
        return [];
    }

    public
    function callComputopService(
        $requestParams,
        $payment,
        $requestType,
        $url
    ) {
        $repository = oxNew(ApiLogRepository::class);
        $paymentName = $payment::paymentClass;
        // $paymentName = Constants::getPaymentClassfromId($paymentName);
        $response = $payment->callComputop($requestParams, $url);
        $logMessage = oxNew(ApiLog::class);
        $logMessage->setPaymentName($paymentName);
        $logMessage->setRequest($requestType);
        $logMessage->setRequestDetails(json_encode($requestParams));
        $logMessage->setTransId($response->getTransID());
        $logMessage->setPayId($response->getPayID());
        $logMessage->setXId($response->getXID());
        $logMessage->setResponse($response->getStatus());
        $logMessage->setResponseDetails(json_encode($response->toArray()));
        $logMessage->setCreationDate(date('Y-m-d H:i:s'));

        $repository->saveApiLog($logMessage);
        try {
        } catch (Exception $e) {
            $logger = new Logger();
            $logger->logError('Unable to save API Log', [
                'error' => $e->getMessage()
            ]);
        }
        return $response;
    }

    /**
     * Gets inforation from response to be displayed on the order confirmation page
     *
     * @param \Fatchip\CTPayment\CTResponse $responseObject Easycredit financing information
     *
     * @return array
     */
    private function getConfirmPageInformation($responseObject)
    {
        $easyCreditInformation = [];
        $financing = json_decode($responseObject->getFinancing(), true);
        $easyCreditInformation['anzahlRaten'] = $financing['decision']['numberOfInstallments'];
        $easyCreditInformation['tilgungsplanText'] = $financing['decision']['amortizationPlanText'];
        $easyCreditInformation['bestellwert'] = $financing['decision']['orderValue'];
        $easyCreditInformation['anfallendeZinsen'] = $financing['decision']['interest'];
        $easyCreditInformation['gesamtsumme'] = $financing['decision']['totalValue'];
        $easyCreditInformation['effektivzins'] = $financing['decision']['effectiveInterest'];
        $easyCreditInformation['nominalzins'] = $financing['decision']['nominalInterest'];
        $easyCreditInformation['betragRate'] = $financing['decision']['installment'];
        $easyCreditInformation['betragLetzteRate'] = $financing['decision']['lastInstallment'];
        $easyCreditInformation['urlVorvertraglicheInformationen'] = $financing['decision']['urlPreContractualInformation'];
        return $easyCreditInformation;
    }

}
