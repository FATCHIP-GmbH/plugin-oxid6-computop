<?php

namespace Fatchip\ComputopPayments\Model\Method;

class AmazonPay extends RedirectPayment
{
    const ID = "fatchip_computop_amazonpay";

    /**
     * @var string
     */
    protected $oxidPaymentId = self::ID;

    /**
     * @var string
     */
    protected $libClassName = 'AmazonPay';

    /**
     * @var bool
     */
    protected $isIframeLibMethod = false;

    /**
     * Determines if auth requests adds shipping address parameters to the request
     *
     * @var bool
     */
    protected $addShippingAddressData = true;
}