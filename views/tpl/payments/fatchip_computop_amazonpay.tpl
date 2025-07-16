<div class="well well-sm cart-buttons">
    [{oxstyle include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/css/amazonpay.css')}]
    [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/amazonpay.js')}]

    [{assign var="ctPayment" value=$oView->computopGetPaymentModel()}]
    [{assign var="basketPrice" value=$oxcmp_basket->getPrice()}]

    [{oxid_include_dynamic file="fatchip_computop_amazonpaybutton.tpl" type="computop" button_id="FatchipComputopAmazonPayButtonNextCartLastStep" price=$basketPrice->getBruttoPrice() amazon_config=$ctPayment->getAmazonPayFrontendConfig()}]

    <div class="clearfix"></div>
</div>