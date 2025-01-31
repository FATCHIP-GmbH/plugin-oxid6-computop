<?php

namespace Fatchip\CTPayment;

/**
 * Class CTPaymentMethods
 * @package Fatchip\CTPayment
 */
class CTPaymentMethods
{
    const paymentMethods =
        [
            [
                'name' => 'fatchip_computop_creditcard',
                'shortname' => 'Kreditkarte',
                'description' => 'Computop Kreditkarte',
                'action' => 'FatchipCTCreditCard',
                'template' => '',
                'additionalDescription' => '',
                'className' => 'CreditCard',
                //'countries' => ['DE', 'NL', 'DK', 'FI', 'SE', 'NO'],
            ],
           /* [
                'name' => 'fatchip_computop_twint',
                'shortname' => 'Twint',
                'description' => 'Computop Twint',
                'action' => 'FatchipCTIdeal',
                'template' => 'fatchip_computopideal.tpl',
                'additionalDescription' => '',
                'className' => 'Twint',
                //'countries' => ['DE', 'NL', 'DK', 'FI', 'SE', 'NO'],
            ],*/

            [
                'name' => 'fatchip_computop_paypal_standard',
                'shortname' => 'PayPal',
                'description' => 'Computop PayPal Standard',
                'action' => 'FatchipCTPaypalStandard',
                'template' => '',
                'additionalDescription' => '',
                'className' => 'PaypalStandard',
                //'countries' => ['DE', 'NL', 'DK', 'FI', 'SE', 'NO'],
            ],
            [
                'name' => 'fatchip_computop_paypal_express',
                'shortname' => 'PayPalExpress',
                'description' => 'Computop PayPal Express',
                'action' => 'FatchipCTPaypalExpress',
                'template' => '',
                'additionalDescription' => '',
                'className' => 'PaypalExpress',
                //'countries' => ['DE', 'NL', 'DK', 'FI', 'SE', 'NO'],
            ],

        ];
}
