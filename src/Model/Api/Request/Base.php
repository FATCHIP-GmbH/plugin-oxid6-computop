<?php

namespace Fatchip\ComputopPayments\Model\Api\Request;

use Fatchip\ComputopPayments\Helper\Api;
use Fatchip\ComputopPayments\Helper\Config;
use Fatchip\ComputopPayments\Helper\Encryption;
use Fatchip\ComputopPayments\Helper\Payment;
use Fatchip\ComputopPayments\Model\Api\Encryption\Blowfish;
use Fatchip\ComputopPayments\Model\Method\BaseMethod;
use OxidEsales\Eshop\Application\Model\Order;

class Base
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * Defines request type to be seen in API Log
     *
     * @var string
     */
    protected $requestType;

    /**
     * URL to Computop API
     *
     * @var string
     */
    protected $apiBaseUrl = "https://www.computop-paygate.com/";

    /**
     * Defines where API requests are sent to at the Comutop API
     *
     * @var string
     */
    protected $apiEndpoint;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var Blowfish
     */
    protected $blowfish;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blowfish = new Blowfish(Config::getInstance()->getConfigParam('blowfishPassword'));
        $this->initRequest();
    }

    /**
     * Initialize request
     * Set all default parameters
     *
     * @param bool $clearParams
     * @return void
     */
    protected function initRequest($clearParams = true)
    {
        if ($clearParams === true) {
            $this->parameters = []; // clear parameters
        }
        $this->addParameter('MerchantID', Config::getInstance()->getConfigParam('merchantID'));
    }

    /**
     * Returns transaction id for this request
     *
     * @param  Order|null $order
     * @return string
     */
    public function getTransactionId(Order $order = null)
    {
        if (empty($this->transactionId)) {
            if (!empty($order)) {
                $this->transactionId = $order->oxorder__oxordernr->value;
            } else {
                $this->transactionId = Payment::getInstance()->getTransactionId();
            }
        }
        return $this->transactionId;
    }

    /**
     * Set transaction id for later use with the request
     *
     * @param  string $transactionId
     * @return void
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Returns all parameters
     *
     * @return array
     */
    public function getParameters()
    {
        $return = $this->parameters;
        $return['MAC'] = $this->getHmac();
        return $return;
    }

    /**
     * Returns a certain parameter if set
     *
     * @param  string $paramName
     * @param  mixed $defaultEmptyReturn
     * @return string|mixed
     */
    public function getParameter($paramName, $defaultEmptyReturn = null)
    {
        if (isset($this->parameters[$paramName])) {
            return $this->parameters[$paramName];
        }
        return $defaultEmptyReturn;
    }

    /**
     * Adds parameter to parameters array
     *
     * @param  string $paramName
     * @param  string $paramValue
     * @return void
     */
    public function addParameter($paramName, $paramValue)
    {
        $this->parameters[$paramName] = $paramValue;
    }

    /**
     * Adds multiple parameters to parameters array
     *
     * @param  array $parameters
     * @return void
     */
    public function addParameters($parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * Removes certain parameter from parameters array
     *
     * @param  string $paramName
     * @return void
     */
    public function removeParameter($paramName)
    {
        if (isset($this->parameters[$paramName])) {
            unset($this->parameters[$paramName]);
        }
    }

    /**
     * Returns the API endpoint
     *
     * @param  bool $apiEndpoint
     * @return string
     */
    public function getFullApiEndpoint($apiEndpoint = false)
    {
        if ($apiEndpoint === false) {
            $apiEndpoint = $this->apiEndpoint;
        }
        return rtrim($this->apiBaseUrl, "/") . "/" .$apiEndpoint;
    }

    /**
     * Returns request type for API log
     *
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * Generates Hmac string and returns it
     *
     * @return string
     */
    protected function getHmac()
    {
        $hashParts = [
            $this->getParameter("PayID", ""), // may be empty, but that's ok - i.e. for Authorization
            $this->getParameter("TransID", ""),
            $this->getParameter("MerchantID", ""),
            $this->getParameter("Amount", ""),
            $this->getParameter("Currency", ""),
        ];
        $hashString = implode("*", $hashParts);
        $secret = Config::getInstance()->getConfigParam('mac');

        return hash_hmac('sha256', $hashString, $secret);
    }

    /**
     * Returns parameters in encrypted format
     *
     * @param  array $params
     * @return array
     */
    public function getEncryptedParameters($params = null)
    {
        if ($params === null) {
            $params = $this->getParameters();
        }

        $dataQuery = urldecode(http_build_query($params));
        $length = mb_strlen($dataQuery);

        return [
            'MerchantID' => $this->getParameter('MerchantID'),
            'Len' => $length,
            'Data' => Encryption::getInstance()->encrypt($dataQuery, $length),
        ];
    }

    /**
     * Send request to given url and decode given response
     *
     * @param  string      $url
     * @param  string      $requestType
     * @param  array|null  $params
     * @param  Order|null  $order
     * @return array|null
     */
    protected function handleCurlRequest($url, $requestType, $params, Order $order = null)
    {
        $response = null;

        try {
            $responseBody = $this->sendCurlRequest($url, $this->getEncryptedParameters($params));
        } catch (\Exception $exc) {
            throw $exc;
        }

        if (!empty($responseBody)) {
            parse_str($responseBody, $parsedResponse);
            if (isset($parsedResponse['Data']) && isset($parsedResponse['Len'])) {
                $response = Encryption::getInstance()->decrypt($parsedResponse['Data'], $parsedResponse['Len']);
            } elseif (isset($parsedResponse['mid'])) { // not encrypted? this is the case with PPE paypalComplete call
                $response = $parsedResponse;
            }
        }

        $paymentName = '';
        if (!empty($order)) {
            $paymentName = get_class($order->computopGetPaymentModel());
        }
        Api::getInstance()->addLogEntry($params, $response, $paymentName, $requestType);

        return $response;
    }

    /**
     * @param  string $uri
     * @param  array $params
     * @return bool|string
     */
    protected function sendCurlRequest($uri, $params = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS | CURLPROTO_FTP | CURLPROTO_FTPS);
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($params) ? http_build_query($params) : $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);

        $err = curl_errno($curl);
        if ($err) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);

        return $response;
    }

    /**
     * @param string     $requestType
     * @param array      $request
     * @param array      $response
     * @param Order|null $order
     * @return void
     */
    protected function handleLogging($requestType, $request, $response = null, Order $order = null)
    {
        $this->checkoutSession->setComputopApiLogData(['type' => $requestType, 'request' => $request, 'response' => $response]);
        $this->apiLog->addApiLogEntry($requestType, $request, $response, $order);
    }

    /**
     * Sends a standard curl request to Computop API
     * Endpoint and request type are taken from the properties of the request class
     *
     * @param  array|null $params
     * @param  Order|null $order
     * @return array|null
     */
    protected function handleStandardCurlRequest($params, Order $order = null)
    {
        return $this->handleCurlRequest($this->getFullApiEndpoint(), $this->getRequestType(), $params, $order);
    }

    /**
     * Sends a payment curl request to Computop API
     * Endpoint and request type are taken from method instance object
     *
     * @param  BaseMethod $methodInstance
     * @param  array $params
     * @param  Order|null $order
     * @return array|null
     */
    public function handlePaymentCurlRequest(BaseMethod $methodInstance, $params, Order $order = null)
    {
        return $this->handleCurlRequest($this->getFullApiEndpoint($methodInstance->getApiEndpoint()), $methodInstance->getRequestType(), $params, $order);
    }
}
