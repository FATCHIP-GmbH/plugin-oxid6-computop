<?php

namespace Fatchip\ComputopPayments\Model\Method;

use OxidEsales\Eshop\Application\Model\Order;

class Ideal extends RedirectPayment
{
    const ID = "fatchip_computop_ideal";

    /**
     * @var string
     */
    protected $oxidPaymentId = self::ID;

    /**
     * @var string
     */
    protected $libClassName = 'Ideal';

    /**
     * Return parameters specific to this payment type
     *
     * @param  Order|null $order
     * @return array
     */
    public function getPaymentSpecificParameters(Order $order, $dynValue, $ctOrder = false)
    {
        $computopConfig = $order->ctGetComputopConfig();
        if ($computopConfig['idealDirektOderUeberSofort'] === 'PPRO') {
            return [];
        }
        return [];
    }
}