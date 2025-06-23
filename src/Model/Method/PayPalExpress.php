<?php

namespace Fatchip\ComputopPayments\Model\Method;

class PayPalExpress extends RedirectPayment
{
    const ID = "fatchip_computop_paypal_express";

    /**
     * @var string
     */
    protected $oxidPaymentId = self::ID;

    /**
     * @var string
     */
    protected $libClassName = 'PayPalExpress';

    /**
     * @var bool
     */
    protected $isIframeLibMethod = false;
}