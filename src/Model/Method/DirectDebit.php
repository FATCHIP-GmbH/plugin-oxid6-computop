<?php

namespace Fatchip\ComputopPayments\Model\Method;

use OxidEsales\Eshop\Application\Model\Order;

class DirectDebit extends ServerToServerPayment
{
    const ID = "fatchip_computop_lastschrift";

    /**
     * @var string
     */
    protected $oxidPaymentId = self::ID;

    /**
     * @var string
     */
    protected $libClassName = 'LastschriftDirekt';

    /**
     * Return parameters specific to this payment type
     *
     * @param  Order|null $order
     * @return array
     */
    public function getPaymentSpecificParameters(Order $order, $dynValue, $ctOrder = false)
    {
        return [
            'AccBank' => $dynValue['fatchip_computop_lastschrift_bankname'],
            'AccOwner' => $dynValue['fatchip_computop_lastschrift_bank_account_holder'],
            'IBAN' => $dynValue['fatchip_computop_lastschrift_iban'],
        ];
    }
}