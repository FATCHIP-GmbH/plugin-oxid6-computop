[{oxscript include="https://static-eu.payments-amazon.com/checkout.js"}]
<div class="amazonpay-button nonexpress" id="[{$_computop_button_id}]"></div>
[{capture name="fatchip_computop_amazonpay_script_amazonbutton"}]
    if (typeof [{$_computop_button_id}]_is_rendered === "undefined" || [{$_computop_button_id}]_is_rendered !== true) {
        var fatchipComputopAmazonPayButton = amazon.Pay.renderButton('#[{$_computop_button_id}]', {
            merchantId: '[{$_computop_amazon_config.amazonpayMerchantId}]',
            publicKeyId : '[{$_computop_amazon_config.amazonpayPubKeyId}]',
            ledgerCurrency: '[{$_computop_amazon_config.currency}]',
            productType: 'PayAndShip',
            placement: 'Checkout',
            buttonColor: '[{$_computop_amazon_config.amazonButtonColor}]',
            payloadJSON: '[{$_computop_amazon_config.amazonPayload}]',
            estimatedOrderAmount: { "amount": "[{ $_computop_price }]", "currencyCode": "[{$_computop_amazon_config.currency}]"},
        });
        FatchipComputopAmazonPayButtonComponent.init(fatchipComputopAmazonPayButton, [{$_computop_amazon_config.amazonPayload}], '[{$_computop_amazon_config.amazonSignature}]','[{$_computop_amazon_config.amazonpayPubKeyId}]');
        [{$_computop_button_id}]_is_rendered = true;
    }
[{/capture}]
[{oxscript add=$smarty.capture.fatchip_computop_amazonpay_script_amazonbutton}]
