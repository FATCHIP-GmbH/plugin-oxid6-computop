<?php

/**
 * The Computop Oxid Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Computop Oxid Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Computop Oxid Plugin. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.6, 7.0 , 7.1
 *
 * @category   Payment
 * @package    FatchipCTPayment
 * @author     FATCHIP GmbH <support@fatchip.de>
 * @copyright  2018 Computop
 * @license    <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link       https://www.computop.com
 */

namespace Fatchip\CTPayment;
/**
 * Class CTPaymentConfigForms
 * @package Fatchip\CTPayment
 */
class CTPaymentConfigForms
{
    const formGeneralTextElements =
        [
            'merchantID' => [
                'name' => 'merchantID',
                'type' => 'text',
                'value' => '',
                'label' => 'MerchantID',
                'required' => true,
                'description' => 'Ihre Merchant Id (Benutzername)',
            ],
            'mac' => [
                'name' => 'mac',
                'type' => 'text',
                'value' => '',
                'label' => 'MAC',
                'required' => true,
                'description' => 'Ihr HMAC-Key',
            ],
            'blowfishPassword' => [
                'name' => 'blowfishPassword',
                'type' => 'text',
                'value' => '',
                'label' => 'Passwort',
                'required' => true,
                'description' => 'Ihr Verschlüsselungs-Passwort',
            ],

        ];

    const formGeneralSelectElements =
        [
            'debuglog' => [
                'name' => 'debuglog',
                'type' => 'select',
                'value' => 'inactive',
                'label' => 'Debug Protokoll',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        ['inactive', [
                            'de_DE' => 'keine Protokollierung',
                            'en_GB' => 'disable logging',
                        ]],
                        ['active', [
                            'de_DE' => 'Protokollierung',
                            'en_GB' => 'enable logging',
                        ]],
                        ['extended', [
                            'de_DE' => 'erweiterte Protokollierung',
                            'en_GB' => 'enable extra logging',
                        ]],
                    ],
                'description' => 'Erzeugt eine Log Datei <FatchipCTPayment_.log> mit Debug Ausgaben im Oxid Protokollverzeichnis.<BR>',
            ],
            'encryption' => [
                'name' => 'encryption',
                'type' => 'select',
                'value' => 'blowfish',
                'label' => 'Verschlüsselung',
                'required' => true,
                'editable' => false,
                'store' =>
                    [
                        ['blowfish', [
                            'de_DE' => 'Blowfish Verschlüsselung (Standard)',
                            'en_GB' => 'Blowfish encyption (default)',
                        ]],
                        ['aes', [
                            'de_DE' => 'AES Verschlüsselung',
                            'en_GB' => 'AES encyption',
                        ]],
                    ],
                'description' => '<p>Art der verwendeten Verschlüsselung.</p><p>Blowfish Verschlüsselung wird vom Computop Support als Standard eingerichtet.</p><p>Sollte die Blowfish Verschlüsselung (bf-cbc) bei Ihrem Hoster nicht verfügbar sein, wenden Sie sich bitte an den Computop Support und lassen Sie AES aufschalten.</p><p>Wenn seitens Computop AES aktiviert wurde, stellen Sie auf AES um.</p>',
            ],
        ];

    const formCreditCardSelectElements =
        [
            'creditCardMode' => [
                'name' => 'creditCardMode',
                'type' => 'select',
                'value' => 'IFRAME',
                'label' => 'Kreditkarte - Modus',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        ['IFRAME', 'IFrame'],
                        ['SILENT', 'Silent Mode'],
                        ['PAYMENTPAGE', 'Payment Page'],
                    ],
                'description' => '<p><b>IFrame</b>: Kreditkartendaten werden nach klick auf "Zahlungsplichtig bestellen" in einem IFrame eingegeben<BR><b>Silent Mode</b>: Kreditkartendaten werden auf der Seite "Prüfen und Bestellen" eingegeben.<BR><b>Payment Page</b>: Kreditkartendaten werden nach klick auf "Zahlungsplichtig bestellen" in einem blanken Fenster eingegeben<BR></p>'
            ],
            'creditCardTestMode' => [
                'name' => 'creditCardTestMode',
                'type' => 'select',
                'value' => 1,
                'label' => 'Kreditkarte - Test-Modus',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        [0, [
                            'de_DE' => 'inaktiv',
                            'en_GB' => 'disabled',
                        ]],
                        [1, [
                            'de_DE' => 'aktiv',
                            'en_GB' => 'enabled',
                        ]],
                    ],
            ],
            'creditCardSilentModeBrandsVisa' => [
                'name' => 'creditCardSilentModeBrandsVisa',
                'type' => 'select',
                'value' => 1,
                'label' => 'Kreditkarte - Visa (Silent Mode)',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        [0, [
                            'de_DE' => 'inaktiv',
                            'en_GB' => 'disabled',
                        ]],
                        [1, [
                            'de_DE' => 'aktiv',
                            'en_GB' => 'enabled',
                        ]],
                    ],
            ],
            'creditCardSilentModeBrandsMaster' => [
                'name' => 'creditCardSilentModeBrandsMaster',
                'type' => 'select',
                'value' => 1,
                'label' => 'Kreditkarte - MasterCard (Silent Mode)',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        [0, [
                            'de_DE' => 'inaktiv',
                            'en_GB' => 'disabled',
                        ]],
                        [1, [
                            'de_DE' => 'aktiv',
                            'en_GB' => 'enabled',
                        ]],
                    ],
            ],
            'creditCardSilentModeBrandsAmex' => [
                'name' => 'creditCardSilentModeBrandsAmex',
                'type' => 'select',
                'value' => 1,
                'label' => 'Kreditkarte - American Express (Silent Mode)',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        [0, [
                            'de_DE' => 'inaktiv',
                            'en_GB' => 'disabled',
                        ]],
                        [1, [
                            'de_DE' => 'aktiv',
                            'en_GB' => 'enabled',
                        ]],
                    ],
            ],
            'creditCardCaption' => [
                'name' => 'creditCardCaption',
                'type' => 'select',
                'value' => 'AUTO',
                'label' => 'Kreditkarte - Capture Modus',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        ['AUTO', [
                            'de_DE' => 'Automatisch',
                            'en_GB' => 'automatic',
                        ]],
                        ['MANUAL', [
                            'de_DE' => 'Manuell',
                            'en_GB' => 'manual',
                        ]],
                    ],
                'description' => '<p><b>AUTO</b>: Reservierte Beträge werden sofort automatisch eingezogen.<BR><b>MANUAL</b>: Geldeinzüge werden von Ihnen manuell im Shopbackend durchgeführt.</p>',
            ],
            'creditCardAcquirer' => [
                'name' => 'creditCardAcquirer',
                'type' => 'select',
                'value' => 'GICC',
                'label' => 'Kreditkarte - Acquirer',
                'required' => 'true',
                'editable' => false,
                'store' =>
                    [
                        ['GICC', 'GICC'],
                        ['CAPN', 'CAPN'],
                        ['Omnipay', 'Omnipay'],
                    ],
                'description' => '<p><b>GICC</b>: Concardis, B+S Card Service, EVO Payments, American Express, Elavon, SIX Payment Service<BR><b>CAPN</b>: American Express<BR><b>Omnipay</b>: EMS payment solutions, Global Payments, Paysquare</p>',
            ]
        ];
    const formCreditCardTextElements =
        [
            'creditCardTemplate' => [
                'name' => 'creditCardTemplate',
                'type' => 'text',
                'value' => 'ct_responsive',
                'label' => 'Kreditkarte - Template Name',
                'required' => false,
                'description' => 'Name der XSLT-Datei mit Ihrem individuellen Layout für das Bezahlformular. Wenn Sie das Responsive Computop-Template für mobile Endgeräte nutzen möchten, übergeben Sie den Templatenamen „ct_responsive“.',
            ],
        ];
    const formPayPalSelectElements =
        [
            'paypalCaption' => [
                'name' => 'paypalCaption',
                'type' => 'select',
                'value' => 'AUTO',
                'label' => 'Paypal - Capture Modus',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        ['AUTO', [
                            'de_DE' => 'Automatisch',
                            'en_GB' => 'automatic',
                        ]],
                        ['MANUAL', [
                            'de_DE' => 'Manuell',
                            'en_GB' => 'manual',
                        ]],
                    ],
                'description' => '<p>Bestimmt, ob der angefragte Betrag sofort oder erst später eingezogen wird. <br><b>Wichtig:<br>Bitte kontaktieren Sie den Computop Support für Manual, um die unterschiedlichen Einsatzmöglichkeiten abzuklären.</b></p>',
            ],
        ];

    const formTranslations =
        [
            'de_DE' => [
                'merchantID' => [
                    'label' => 'MerchantID',
                    'description' => 'Ihre MerchantID',
                ],
                'mac' => [
                    'label' => 'MAC',
                    'description' => 'Ihr HMAC-Key',
                ],
                'blowfishPassword' => [
                    'label' => 'Passwort',
                    'description' => 'Ihr Verschlüsselungs-Passwort',
                ],
                'debuglog' => [
                    'label' => 'Debug Protokoll',
                    'description' => 'Erzeugt eine Log Datei <FatchipCTPayment_.log> mit Debug Ausgaben im Oxid Protokollverzeichnis',
                ],
                'encryption' => [
                    'label' => 'Verschlüsselung',
                    'description' => '<p>Art der verwendeten Verschlüsselung.</p><p>Blowfish Verschlüsselung wird vom Computop Support als Standard eingerichtet.</p><p>Sollte die Blowfish Verschlüsselung (bf-cbc) bei Ihrem Hoster nicht verfügbar sein, wenden Sie sich bitte an den Computop Support und lassen Sie AES aufschalten.</p><p>Wenn seitens Computop AES aktiviert wurde, stellen Sie auf AES um.</p>',
                ],
                'creditCardMode' => [
                    'label' => 'Kreditkarte - Modus',
                    'description' => '<p><b>IFrame</b>: Kreditkartendaten werden nach klick auf "Zahlungsplichtig bestellen" in einem IFrame eingegeben<BR><b>Silent Mode</b>: Kreditkartendaten werden auf der Seite "Prüfen und Bestellen" eingegeben.<BR><b>Payment Page</b>: Kreditkartendaten werden nach klick auf "Zahlungsplichtig bestellen" in einem blanken Fenster eingegeben.</p>',
                ],
                'creditCardTestMode' => [
                    'label' => 'Kreditkarte - Test-Modus',
                    //'description' => '',
                ],
                'creditCardSilentModeBrandsVisa' => [
                    'label' => 'Kreditkarte - Visa (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardSilentModeBrandsMaster' => [
                    'label' => 'Kreditkarte - MasterCard (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardSilentModeBrandsAmex' => [
                    'label' => 'Kreditkarte - American Express (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardCaption' => [
                    'label' => 'Kreditkarte - Capture Modus',
                    'description' => '<p><b>AUTO</b>: Reservierte Beträge werden sofort automatisch eingezogen.<BR><b>MANUAL</b>: Geldeinzüge werden von Ihnen manuell im Shopbackend durchgeführt.</p>',
                ],
                'creditCardAcquirer' => [
                    'label' => 'Kreditkarte - Acquirer',
                    'description' => '<p><b>GICC</b>: Concardis, B+S Card Service, EVO Payments, American Express, Elavon, SIX Payment Service<BR><b>CAPN</b>: American Express<BR><b>Omnipay</b>: EMS payment solutions, Global Payments, Paysquare</p>',
                ],

                'creditCardTemplate' => [
                    'label' => 'Kreditkarte - Template Name',
                    'description' => 'Name der XSLT-Datei mit Ihrem individuellen Layout für das Bezahlformular. Wenn Sie das Responsive Computop-Template für mobile Endgeräte nutzen möchten, übergeben Sie den Templatenamen „ct_responsive“.',
                ],
                'paypalCaption' => [
                    'label' => 'Paypal - Capture Modus',
                    'description' => '<p>Bestimmt, ob der angefragte Betrag sofort oder erst später eingezogen wird. <br><b>Wichtig:<br>Bitte kontaktieren Sie den Computop Support für Manual, um die unterschiedlichen Einsatzmöglichkeiten abzuklären.</b></p>',
                ],
            ],
            'en_GB' => [
                'merchantID' => [
                    'label' => 'MerchantID',
                    'description' => 'Your MerchantID',
                ],
                'mac' => [
                    'label' => 'MAC',
                    'description' => 'Your HMAC-Key',
                ],
                'blowfishPassword' => [
                    'label' => 'Password',
                    'description' => 'Your encryption password',
                ],
                'fatchip_computop_ideal_button' => [
                    'label' => '<strong>update iDeal banks<strong>',
                ],
                'debuglog' => [
                    'label' => 'Debug protocol',
                    'description' => 'Creates a log file <FatchipCTPayment_.log> with debugging output on the Oxid log folder',
                ],
                'encryption' => [
                    'label' => 'Encyption',
                    'description' => '<p>Type of encryption used.<br>Blowfish encryption is set up by Computop Support as a standard. If Blowfish encryption (bf-cbc) is not available from your hoster, please contact Computop Support and have AES activated.<BR>If Computop has activated AES, switch to AES.</p>',
                ],
                'creditCardMode' => [
                    'label' => 'Creditcard - Mode',
                    'description' => '</p><b>IFrame</b>: The creditcard form will be displayed after clicking "confirm payment" in an iframe<BR><b>Silent Mode</b>: The creditcard form will be displayed on the "complete order" page.<BR><b>Payment Page</b>: Credit card details are entered in a blank page after clicking on "Order payment".',
                ],
                'creditCardTestMode' => [
                    'label' => 'Creditcard - Testmode',
                    // 'description' => '',
                ],
                'creditCardSilentModeBrandsVisa' => [
                    'label' => 'Creditcard - Visa (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardSilentModeBrandsMaster' => [
                    'label' => 'Creditcard - MasterCard (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardSilentModeBrandsAmex' => [
                    'label' => 'Creditcard - American Express (Silent Mode)',
                    // 'description' => '',
                ],
                'creditCardCaption' => [
                    'label' => 'Creditcard - Capture Mode',
                    'description' => '<p></p><b>AUTO</b>: Reserved amounts will be captured automatically.<BR><b>MANUAL</b>: Reserverd amounts have to be captured manuelly in the shop backend.</p>',
                ],
                'creditCardAcquirer' => [
                    'label' => 'Creditcard - Acquirer',
                    'description' => '<p><b>GICC</b>: Concardis, B+S Card Service, EVO Payments, American Express, Elavon, SIX Payment Service<BR><b>CAPN</b>: American Express<BR><b>Omnipay</b>: EMS payment solutions, Global Payments, Paysquare</p>',
                ],
                'creditCardTemplate' => [
                    'label' => 'Creditcard - Template name',
                    'description' => 'Name of the XSLT-file with your individual payment form layout. If you want to use the responsive computop template for mobile devices, please use the template name „ct_responsive“.',
                ],
                'paypalCaption' => [
                    'label' => 'Paypal - Capture Modus',
                    'description' => '<p>Determines whether the requested amount is collected immediately or at a later date. <br><b>Important:<br>Please contact Computop Support for Manual to clarify the different application options.</b></p>',
                ],
            ],
        ];

    const formPayPalExpressSelectElementTestMode =
        [
            'paypalExpressTestMode' => [
                'name' => 'paypalExpressTestMode',
                'type' => 'select',
                'value' => 'An',
                'label' => 'PayPal Express - Test Modus',
                'required' => false,
                'editable' => false,
                'when' => [
                    [
                        'value' => 'An',
                        'do' => [
                            'hide' => [
                                'paypalExpressClientID_block',
                                'paypalExpressMerchantID_block',
                                'paypalExpressPartnerAttributionID_block'
                            ]
                        ]
                    ],
                    [
                        'value' => 'Aus',
                        'do' => [
                            'show' => [
                                'paypalExpressClientID_block',
                                'paypalExpressMerchantID_block',
                                'paypalExpressPartnerAttributionID_block'
                            ]
                        ]
                    ]
                ],
                'store' =>
                    [
                        ['An', [
                            'de_DE' => 'An',
                            'en_GB' => 'active',
                        ]],
                        ['Aus', [
                            'de_DE' => 'Aus',
                            'en_GB' => 'inactive',
                        ]],
                    ],
                'description' => '<p>Test Mode</p>',
            ],
        ];

    const formPayPalExpressSelectElements =
        [
            'paypalExpressCaption' => [
                'name' => 'paypalExpressCaption',
                'type' => 'select',
                'value' => 'AUTO',
                'label' => 'PayPal Express - Capture Modus',
                'required' => false,
                'editable' => false,
                'store' =>
                    [
                        ['AUTO', [
                            'de_DE' => 'Automatisch',
                            'en_GB' => 'automatic',
                        ]],
                        ['MANUAL', [
                            'de_DE' => 'Manuell',
                            'en_GB' => 'manual',
                        ]],
                    ],
                'description' => '<p>Bestimmt, ob der angefragte Betrag sofort oder erst später eingezogen wird. <br><b>Wichtig:<br>Bitte kontaktieren Sie den Computop Support für Manual, um die unterschiedlichen Einsatzmöglichkeiten abzuklären.</b></p>',
            ],
        ];

    const formPayPalExpressTextElementClientID = [
        'paypalExpressClientID' => [
            'name' => 'paypalExpressClientID',
            'type' => 'text',
            'value' => '',
            'label' => 'PayPal Express - Client-ID',
            'required' => true,
            'description' => '<p>PaypalExpress Client-ID.</p>',
        ]
    ];

    const formPayPalExpressTextElementMerchantID = [
        'paypalExpressMerchantID' => [
            'name' => 'paypalExpressMerchantID',
            'type' => 'text',
            'value' => '',
            'label' => 'PayPal Express - Merchant-ID',
            'required' => true,
            'description' => '<p>PaypalExpress Merchant-ID.</p>',
        ]
    ];

    const formPayPalExpressTextElementPartnerAttributionID = [
        'paypalExpressPartnerAttributionID' => [
            'name' => 'paypalExpressPartnerAttributionID',
            'type' => 'text',
            'value' => '',
            'label' => 'Computop - Partner-Attribution-ID',
            'required' => true,
            'description' => '<p>Computop Partner-Attribution-ID.</p>',
        ]
    ];

    const formRatePayDirectDebitSelectElementBic = [
        'ratepayDirectDebitRequestBic' => [
            'name' => 'ratepayDirectDebitRequestBic',
            'type' => 'select',
            'value' => 'Aus',
            'label' => 'BIC abfragen',
            'required' => false,
            'editable' => false,
            'store' =>
                [
                    ['An', [
                        'de_DE' => 'An',
                        'en_GB' => 'active',
                    ]],
                    ['Aus', [
                        'de_DE' => 'Aus',
                        'en_GB' => 'inactive',
                    ]],
                ],
        ],
    ];

}
