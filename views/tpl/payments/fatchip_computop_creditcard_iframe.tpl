<div class="payment-option">
    [{assign var="dynvalue" value=$oView->getDynValue()}]
    <div class="payment-option-form">
        <div class="payment-option-info [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <div class="hidden">
                <input id="payment_[{$sPaymentID}]_javascriptEnabled" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_javascriptEnabled]" value="">
                <input id="payment_[{$sPaymentID}]_javaEnabled" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_javaEnabled]" value="">
                <input id="payment_[{$sPaymentID}]_screenHeight" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_screenHeight]" value="">
                <input id="payment_[{$sPaymentID}]_screenWidth" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_screenWidth]" value="">
                <input id="payment_[{$sPaymentID}]_colorDepth" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_colorDepth]" value="">
                <input id="payment_[{$sPaymentID}]_timeZoneOffset" type="hidden" maxlength="64"
                       name="dynvalue[[{$sPaymentID}]_timeZoneOffset]" value="">
            </div>
        </div>
    </div>

    [{if $paymentmethod->getPrice()}]
    <div class="payment-option-price">
        [{assign var="oPaymentPrice" value=$paymentmethod->getPrice()}]
        [{if $oViewConf->isFunctionalityEnabled('blShowVATForPayCharge')}]
        [{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]

        [{if $oPaymentPrice->getVatValue() > 0}]
        [{oxmultilang ident="PLUS_VAT"}][{oxprice price=$oPaymentPrice->getVatValue() currency=$currency}]

        [{/if}]
        [{else}]
        [{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]
        [{/if}]
    </div>
    [{/if}]
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var javaScriptEnabled = true;
        var javaEnabled = navigator.javaEnabled();
        var screenHeight = screen.height;
        var screenWidth = screen.width;
        var colorDepth = screen.colorDepth;
        var date = new Date();
        var timeZoneOffset = date.getTimezoneOffset();

        console.log('JavaScriptEnabled:', javaScriptEnabled);
        console.log('JavaEnabled:', javaEnabled);
        console.log('screenHeight:', screenHeight);
        console.log('screenWidth:', screenWidth);
        console.log('ColorDepth:', colorDepth);
        console.log('timeZoneOffset:', timeZoneOffset);

        function setInputValue(inputId, value) {
            var inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.setAttribute('value', value);
            } else {
                console.warn("Element not found:", inputId);
            }
        }

        setInputValue('payment_[{$sPaymentID}]_javascriptEnabled', javaScriptEnabled);
        setInputValue('payment_[{$sPaymentID}]_javaEnabled', javaEnabled);
        setInputValue('payment_[{$sPaymentID}]_screenHeight', screenHeight);
        setInputValue('payment_[{$sPaymentID}]_screenWidth', screenWidth);
        setInputValue('payment_[{$sPaymentID}]_colorDepth', colorDepth);
        setInputValue('payment_[{$sPaymentID}]_timeZoneOffset', timeZoneOffset);
    });
</script>
