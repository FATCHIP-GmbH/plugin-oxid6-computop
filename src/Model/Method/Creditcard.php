<?php

namespace Fatchip\ComputopPayments\Model\Method;

use Fatchip\CTPayment\CTResponse;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class Creditcard extends RedirectPayment
{
    const ID = "fatchip_computop_creditcard";

    /**
     * @var string
     */
    protected $oxidPaymentId = self::ID;

    /**
     * @var string
     */
    protected $libClassName = 'CreditCard';

    /**
     * @return string
     */
    public function getRequestType()
    {
        return parent::getRequestType()."-".$this->getComputopConfig()->getCreditCardMode();
    }

    /**
     * Return parameters specific to this payment type
     *
     * @param  Order|null $order
     * @return array
     */
    public function getPaymentSpecificParameters(Order $order, $dynValue, $ctOrder = false)
    {
        return [
            'RefNr' => Registry::getSession()->getSessionChallengeToken(),
            'UserData' => Registry::getSession()->getId()
        ];
    }

    /**
     * @param Order $order
     * @param CTResponse $notify
     * @return void
     */
    public function handleNotifySpecific(Order $order, CTResponse $notify)
    {
        $changed = false;

        if (!empty($notify->getPCNr())) {
            $order->assign(['fatchip_computop_pcnr' => $notify->getPCNr()]);
            $changed = true;
        }

        if (!empty($notify->getCCExpiry())) {
            $order->assign(['fatchip_computop_ccexpiry' => $notify->getCCExpiry()]);
            $changed = true;
        }

        if (!empty($notify->getCCBrand())) {
            $order->assign(['fatchip_computop_ccbrand' => $notify->getCCBrand()]);
            $changed = true;
        }

        if (!empty($notify->getCardHolder())) {
            $order->assign(['fatchip_computop_cardholder' => $notify->getCardHolder()]);
            $changed = true;
        }

        if ($changed === true) {
            $order->save();
        }
    }
}