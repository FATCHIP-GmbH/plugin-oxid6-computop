<?php

$sLangName = 'English';
$aLang = [
    'charset'                                               => 'UTF-8',

    'SHOP_MODULE_GROUP_COMPUTOP_GENERAL'                    => 'General',
    'SHOP_MODULE_GROUP_COMPUTOP_CREDITCARD'                 => 'Credit Card',
    'SHOP_MODULE_GROUP_COMPUTOP_PAYPAL'                     => 'PayPal',
    'SHOP_MODULE_GROUP_COMPUTOP_PAYPALEXPRESS'              => 'PayPal Express',
    'SHOP_MODULE_GROUP_COMPUTOP_DIRECTDEBIT'                => 'Direct Debit',
    'SHOP_MODULE_GROUP_COMPUTOP_KLARNA'                     => 'Klarna',
    'SHOP_MODULE_GROUP_COMPUTOP_AMAZONPAY'                  => 'Amazon',
    'SHOP_MODULE_GROUP_COMPUTOP_IDEAL'                      => 'Ideal',
    'SHOP_MODULE_GROUP_COMPUTOP_RATEPAYDIRECTDEBIT'         => 'Ratepay Direct Debit',

    'SHOP_MODULE_merchantID'                                => 'MerchantID',
    'SHOP_MODULE_mac'                                       => 'MAC',
    'SHOP_MODULE_blowfishPassword'                          => 'Password',
    'SHOP_MODULE_debuglog'                                  => 'Debug Log',
    'SHOP_MODULE_debuglog_inactive'                         => 'No logging',
    'SHOP_MODULE_debuglog_active'                           => 'Logging',
    'SHOP_MODULE_debuglog_extended'                         => 'Extended logging',
    'SHOP_MODULE_encryption'                                => 'Encryption',
    'SHOP_MODULE_encryption_blowfish'                       => 'Blowfish encryption (default)',
    'SHOP_MODULE_encryption_aes'                            => 'AES encryption',
    'SHOP_MODULE_refnr_prefix'                              => 'Reference Number Prefix',
    'SHOP_MODULE_refnr_suffix'                              => 'Reference Number Suffix',

    'SHOP_MODULE_creditCardMode'                            => 'Credit Card - Mode',
    'SHOP_MODULE_creditCardMode_IFRAME'                     => 'IFrame',
    'SHOP_MODULE_creditCardMode_SILENT'                     => 'Silent Mode',
    'SHOP_MODULE_creditCardMode_PAYMENTPAGE'                => 'Payment Page',
    'SHOP_MODULE_creditCardTestMode'                        => 'Credit Card - Test Mode',
    'SHOP_MODULE_creditCardSilentModeBrandsVisa'            => 'Credit Card - Visa (Silent Mode)',
    'SHOP_MODULE_creditCardSilentModeBrandsMaster'          => 'Credit Card - MasterCard (Silent Mode)',
    'SHOP_MODULE_creditCardSilentModeBrandsAmex'            => 'Credit Card - American Express (Silent Mode)',
    'SHOP_MODULE_creditCardCaption'                         => 'Credit Card - Capture Mode',
    'SHOP_MODULE_creditCardCaption_AUTO'                    => 'Automatic',
    'SHOP_MODULE_creditCardCaption_MANUAL'                  => 'Manual',
    'SHOP_MODULE_creditCardAcquirer'                        => 'Credit Card - Acquirer',
    'SHOP_MODULE_creditCardAcquirer_GICC'                   => 'GICC',
    'SHOP_MODULE_creditCardAcquirer_CAPN'                   => 'CAPN',
    'SHOP_MODULE_creditCardAcquirer_Omnipay'                => 'Omnipay',
    'SHOP_MODULE_creditCardTemplate'                        => 'Credit Card - Template Name',

    'SHOP_MODULE_paypalCaption'                             => 'Paypal - Capture Mode',
    'SHOP_MODULE_paypalCaption_AUTO'                        => 'Automatic',
    'SHOP_MODULE_paypalCaption_MANUAL'                      => 'Manual',

    'SHOP_MODULE_paypalExpressTestMode'                     => 'PayPal Express - Test Mode',
    'SHOP_MODULE_paypalExpressCaption'                      => 'PayPal Express - Capture Mode',
    'SHOP_MODULE_paypalExpressCaption_AUTO'                 => 'Automatic',
    'SHOP_MODULE_paypalExpressCaption_MANUAL'               => 'Manual',
    'SHOP_MODULE_paypalExpressClientID'                     => 'PayPal Express - Client-ID',
    'SHOP_MODULE_paypalExpressMerchantID'                   => 'PayPal Express - Merchant-ID',
    'SHOP_MODULE_paypalExpressPartnerAttributionID'         => 'Computop - Partner-Attribution-ID',

    'SHOP_MODULE_lastschriftCaption'                        => 'Direct Debit - Capture Mode',
    'SHOP_MODULE_lastschriftCaption_AUTO'                   => 'Automatic',
    'SHOP_MODULE_lastschriftCaption_MANUAL'                 => 'Manual',

    'SHOP_MODULE_klarnaaccount'                             => 'Klarna Account',

    'SHOP_MODULE_amazonpayMerchantId'                       => 'AmazonPay - MerchantId',
    'SHOP_MODULE_amazonpayPubKeyId'                         => 'AmazonPay - Public Key Id',
    'SHOP_MODULE_amazonLiveMode'                            => 'Amazon Mode',
    'SHOP_MODULE_amazonLiveMode_Live'                       => 'Live',
    'SHOP_MODULE_amazonLiveMode_Test'                       => 'Test',
    'SHOP_MODULE_amazonCaptureType'                         => 'Amazon Capture Mode',
    'SHOP_MODULE_amazonCaptureType_AUTO'                    => 'Automatic',
    'SHOP_MODULE_amazonCaptureType_MANUAL'                  => 'Manual',
    'SHOP_MODULE_amazonButtonColor'                         => 'AmazonPay - Button Color',
    'SHOP_MODULE_amazonButtonColor_Gold'                    => 'Gold',
    'SHOP_MODULE_amazonButtonColor_LightGray'               => 'LightGray',
    'SHOP_MODULE_amazonMarketplace'                         => 'Used Amazon Marketplace',
    'SHOP_MODULE_amazonMarketplace_EU'                      => 'EU',
    'SHOP_MODULE_amazonMarketplace_UK'                      => 'UK',
    'SHOP_MODULE_amazonMarketplace_US'                      => 'US',
    'SHOP_MODULE_amazonMarketplace_JP'                      => 'JP',

    'SHOP_MODULE_idealDirektOderUeberSofort'                => 'iDEAL - Service',
    'SHOP_MODULE_idealDirektOderUeberSofort_DIREKT'         => 'iDEAL Direct',
    'SHOP_MODULE_idealDirektOderUeberSofort_PPRO'           => 'via PPRO',

    'SHOP_MODULE_ratepayDirectDebitRequestBic'              => 'Request BIC',

    'HELP_SHOP_MODULE_blMollieShowIcons'                    => 'Your Merchant Id (username)',
    'HELP_SHOP_MODULE_mac'                                  => 'Your HMAC key',
    'HELP_SHOP_MODULE_blowfishPassword'                     => 'Your encryption password',
    'HELP_SHOP_MODULE_debuglog'                             => 'Creates a log file "FatchipCTPayment_.log" with debug output in the Oxid log directory.',
    'HELP_SHOP_MODULE_encryption'                           => 'Type of encryption used.<br>Blowfish encryption is set up as standard by Computop Support.<br>If Blowfish encryption (bf-cbc) is not available on your host, please contact Computop Support to have AES enabled.<br>If AES has been activated by Computop, switch to AES.',

    'HELP_SHOP_MODULE_creditCardMode'                       => '<b>IFrame</b>: The creditcard form will be displayed after clicking "confirm payment" in an iframe<BR><b>Silent Mode</b>: The creditcard form will be displayed on the "complete order" page.<BR><b>Payment Page</b>: Credit card details are entered in a blank page after clicking on "Order payment".',
    'HELP_SHOP_MODULE_creditCardCaption'                    => '<b>AUTO</b>: Reserved amounts will be captured automatically.<BR><b>MANUAL</b>: Reserverd amounts have to be captured manuelly in the shop backend.</p>',
    'HELP_SHOP_MODULE_creditCardAcquirer'                   => '<b>GICC</b>: Concardis, B+S Card Service, EVO Payments, American Express, Elavon, SIX Payment Service<BR><b>CAPN</b>: American Express<BR><b>Omnipay</b>: EMS payment solutions, Global Payments, Paysquare',
    'HELP_SHOP_MODULE_creditCardTemplate'                   => 'Name of the XSLT-file with your individual payment form layout. If you want to use the responsive computop template for mobile devices, please use the template name „ct_responsive“.',

    'HELP_SHOP_MODULE_paypalCaption'                        => '<p>Determines whether the requested amount is collected immediately or at a later date. <br><b>Important:<br>Please contact Computop Support for Manual to clarify the different application options.</b></p>',

    'HELP_SHOP_MODULE_paypalExpressTestMode'                => 'Test Mode',
    'HELP_SHOP_MODULE_paypalExpressCaption'                 => 'Determines whether the requested amount is collected immediately or at a later date. <br><b>Important:<br>Please contact Computop Support for Manual to clarify the different application options.</b>',
    'HELP_SHOP_MODULE_paypalExpressClientID'                => 'PaypalExpress Client-ID.',
    'HELP_SHOP_MODULE_paypalExpressMerchantID'              => 'PaypalExpress Merchant-ID.',
    'HELP_SHOP_MODULE_paypalExpressPartnerAttributionID'    => 'Computop Partner-Attribution-ID.',

    'HELP_SHOP_MODULE_lastschriftCaption'                   => '<b>AUTO</b>: Reserved amounts will be captured automatically.<BR><b>MANUAL</b>: Captures are carried out by you manually in the shop backend.</p>',

    'HELP_SHOP_MODULE_klarnaaccount'                        => 'The Klarna account to be used.',

    'HELP_SHOP_MODULE_amazonpayMerchantId'                  => 'Your AmazonPay MerchantId',
    'HELP_SHOP_MODULE_amazonpayPubKeyId'                    => 'Your AmazonPay Public Key Id',
    'HELP_SHOP_MODULE_amazonLiveMode'                       => 'Use AmazonPay in Live or Test mode',
    'HELP_SHOP_MODULE_amazonCaptureType'                    => '<b>Automatic</b>: Reserved amounts will be captured automatically.<BR><b>Manual</b>: Captures are carried out by you manually in the shop backend.',
    'HELP_SHOP_MODULE_amazonButtonColor'                    => 'Color of the Amazon button<BR>The appearance of the different buttons.<BR>Click on the link "AmazonPay - Button Color" on the left',

    'HELP_SHOP_MODULE_idealDirektOderUeberSofort'           => 'Select your connection to iDeal here - direct or via PPRO.',

    'FATCHIP_COMPUTOP_PAYMENT'          => 'Computop Payment Method Settings',
    'fatchip-computop-payments'        => 'Computop',
    'FATCHIP_COMPUTOP_PAYMENTS_CONFIG' => 'Configuration',
    'FATCHIP_COMPUTOP_PAYMENTS_APILOG' => 'API Logs',
    'FATCHIP_COMPUTOP_ERR_CONF_INVALID' => 'Configuration incorrect',
    'FATCHIP_COMPUTOP_CONF_VALID'      => 'Configuration correct',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_ID' => 'ID',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_REQUEST' => 'Request',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_RESPONSE' => 'Response',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_CREATION_DATE' => 'Time',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_PAYMENT_NAME' => 'Payment Method',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_REQUEST_DETAILS' => 'Request Details',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_RESPONSE_DETAILS' => 'Response Details',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_RESPONSE_TRANS_ID' => 'TransId',
    'FATCHIP_COMPUTOP_PAYMENT_API_LOG_RESPONSE_X_ID' => 'XId',
    'FATCHIP_COMPUTOP_REMARK' => 'Computop Message',
    'FATCHIP_COMPUTOP_GENERAL_SETTINGS' => 'General',
    'FATCHIP_COMPUTOP_CREDIT_CARD_SETTINGS' => 'Credit Card',
    'FATCHIP_COMPUTOP_IDEAL_SETTINGS' => 'iDEAL',
    'FATCHIP_COMPUTOP_PAYPAL_SETTINGS' => 'Paypal',
    'FATCHIP_COMPUTOP_PAYPALEXPRESS_SETTINGS' => 'PayPal Express',
    'FATCHIP_COMPUTOP_RATEPAYDIRECTDEBIT_SETTINGS' => 'Ratepay Direct Debit',
    'FATCHIP_COMPUTOP_DIRECT_DEBIT_SETTINGS' => 'Direct Debit',
    'FATCHIP_COMPUTOP_AMAZON_SETTINGS' => 'Amazon',
    'FATCHIP_COMPUTOP_KLARNA_SETTINGS' => 'Klarna',
    'FATCHIP_COMPUTOP_IDEAL_ISSUERS' => 'Banks have been successfully updated',
    'FATCHIP_COMPUTOP_IDEAL_ISSUERS_ERROR' => 'Error updating the banks',
    'fatchip_computop_ADMIN_API_LOGS_SELECT_ENTRY' => 'Please select a log',
    'FATCHIP_COMPUTOP_PAYMENT_TYPE' => 'Payment Type',
    'FATCHIP_COMPUTOP_EXTERNAL_TRANSACTION_ID' => 'External Transaction Id',
    'FATCHIP_COMPUTOP_ORDER_REMARK' => 'Note',
    'FATCHIP_COMPUTOP_CAPTURE_TITLE' => 'Capture Payment',
    'FATCHIP_COMPUTOP_NO_COMPUTOP_PAYMENT' => 'Not a Computop order',
    'FATCHIP_COMPUTOP_HEADER_SINGLE_PRICE' => 'Unit Price',
    'FATCHIP_COMPUTOP_CAPTURE_SUBMIT' => 'Capture Payment',
    'FATCHIP_COMPUTOP_ORDER_SETTINGS' => 'Computop',
    'FATCHIP_COMPUTOP_PAYMENT_DETAILS' => 'Computop Order Details',
    'FATCHIP_COMPUTOP_ORDER_CAPTURED_AMOUNT' => 'Captured Amount',
    'FATCHIP_COMPUTOP_ORDER_PAYID' => 'PayId',
    'FATCHIP_COMPUTOP_ORDER_REFUND_AMOUNT' => 'Refunded Amount:',
    'FATCHIP_COMPUTOP_REFUND_SUBMIT' => 'Refund entire order',
    'FATCHIP_COMPUTOP_REFUND_ARTICLES_SUBMIT' => 'Refund selected items',
    'COMPUTOP_ARTICLE_REFUNDED' => 'Is refunded?',
    'COMPUTOP_ARTICLE_REFUNDED_YES' => 'Yes',
    'COMPUTOP_ARTICLE_REFUNDED_NO' => 'No',
    'COMPUTOP_ARTICLE_REFUNDED_NO_ARTICLES_TO_REFUND' => 'All items already refunded',
    'COMPUTOP_ARTICLE_REFUNDED_NO_ARTICLES_CHECKED'   => 'Please select items',
    'FATCHIP_COMPUTOP_CAPTURE_SUBMIT_COMPLETE' => 'Order completely captured.',
    'COMPUTOP_REFUND_SUCCESS' => 'Refund created successfully.',
    'FATCHIP_COMPUTOP_REFUND' => 'Refund:',
    'FATCHIP_COMPUTOP_REFUND_ALL' => 'Full Refund:',
    'FATCHIP_COMPUTOP_PAYMENTS_APILOG_MAIN' => 'API Log Details',
];