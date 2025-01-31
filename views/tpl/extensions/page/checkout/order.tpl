    [{assign var="payment" value=$oView->getPayment()}]
    [{assign var="getFatchipComputopShopConfigMode" value=$oView->getFatchipComputopShopConfigMode()}]

    [{if $payment->oxpayments__oxid->value == 'fatchip_computop_creditcard' && $getFatchipComputopShopConfigMode == 'SILENT'}]
    [{$fatchipComputopConfig.creditCardMode}]

        [{include file="fatchip_computop_creditcard_silentmode.tpl"}]
    [{else}]
        [{$smarty.block.parent}]
    [{/if}]



