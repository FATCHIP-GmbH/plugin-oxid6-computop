    [{assign var="payment" value=$oView->getPayment()}]
    [{assign var="creditcardMode" value=$oView->getFatchipComputopShopCreditcardMode()}]

    [{if $payment->oxpayments__oxid->value == 'fatchip_computop_creditcard' && $creditcardMode == 'SILENT'}]
    [{$fatchipComputopConfig.creditCardMode}]

        [{include file="fatchip_computop_creditcard_silentmode.tpl"}]
    [{else}]
        [{$smarty.block.parent}]
    [{/if}]



