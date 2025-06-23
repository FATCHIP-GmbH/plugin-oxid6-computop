<?php

namespace Fatchip\ComputopPayments\Model\Method;

use Fatchip\ComputopPayments\Core\Config;
use Fatchip\CTPayment\CTResponse;
use OxidEsales\Eshop\Application\Model\Order;

abstract class BaseMethod
{
    /**
     * @var string
     */
    protected $oxidPaymentId;

    /**
     * @var string
     */
    protected $libClassName;

    /**
     * @var string
     */
    protected $requestType = "REQUEST";

    /**
     * @var string|false
     */
    protected $customFrontendTemplate = false;

    /**
     * @var bool
     */
    protected $isIframeLibMethod = true;

    /**
     * Determines if auth requests adds billing address parameters to the request
     *
     * @var bool
     */
    protected $addBillingAddressData = false;

    /**
     * Determines if auth requests adds shipping address parameters to the request
     *
     * @var bool
     */
    protected $addShippingAddressData = false;

    /**
     * @var Config|null
     */
    protected $config = null;

    /**
     * Get oxid payment id of this payment method
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->oxidPaymentId;
    }

    /**
     * Get name of the matching class in the Computop lib
     *
     * @return string
     */
    public function getLibClassName()
    {
        return $this->libClassName;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @return bool
     */
    public function isIframeLibMethod()
    {
        return $this->isIframeLibMethod;
    }


    /**
     * Returns if address parameters have to be added in auth request
     *
     * @return bool
     */
    public function isBillingAddressDataNeeded()
    {
        return $this->addBillingAddressData;
    }

    /**
     * Returns if address parameters have to be added in auth request
     *
     * @return bool
     */
    public function isShippingAddressDataNeeded()
    {
        return $this->addShippingAddressData;
    }

    /**
     * @param array $dynValue
     * @param string $fieldName
     * @return string|false
     */
    protected function getDynValue($dynValue, $fieldName)
    {
        if (!empty($dynValue[$this->getPaymentId()."_".$fieldName])) {
            return $dynValue[$this->getPaymentId()."_".$fieldName];
        }
        return false;
    }

    /**
     * @param Order $order
     * @param array $dynValue
     * @return string
     */
    protected function getTelephoneNumber(Order $order, $dynValue)
    {
        $dynValueTelephone = $this->getDynValue($dynValue, "telephone");
        if (!empty($dynValueTelephone)) {
            return $dynValueTelephone;
        }

        $user = $order->getUser();
        if (!empty($user->oxuser__oxmobfon->value)) {
            return $user->oxuser__oxmobfon->value;
        }
        if (!empty($user->oxuser__oxprivfon->value)) {
            return $user->oxuser__oxprivfon->value;
        }
        if (!empty($user->oxuser__oxfon->value)) {
            return $user->oxuser__oxfon->value;
        }
        return false;
    }

    /**
     * Return parameters specific to this payment type
     *
     * @param  Order|null $order
     * @return array
     */
    public function getPaymentSpecificParameters(Order $order, $dynValue, $ctOrder = false)
    {
        return []; // filled in child classes
    }

    /**
     * Return parameters specific to this payment subtype
     *
     * @param  Order $order
     * @param  array $dynValue
     * @return array
     */
    public function getSubTypeSpecificParameters(Order $order, $dynValue)
    {
        return []; // filled in child classes
    }

    /**
     * @param Order $order
     * @param CTResponse $notify
     * @return void
     */
    public function handleNotifySpecific(Order $order, CTResponse $notify)
    {
        // hook for extention by child methods
    }

    /**
     * @return string|false
     */
    public function getCustomFrontendTemplate()
    {
        return $this->customFrontendTemplate;
    }

    /**
     * @return Config|null
     */
    public function getComputopConfig()
    {
        if ($this->config === null) {
            $this->config = new Config();
        }
        return $this->config;
    }
}