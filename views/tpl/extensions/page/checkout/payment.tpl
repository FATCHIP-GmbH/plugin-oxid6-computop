[{$smarty.block.parent}]
[{assign var="dynvalue" value=$oView->getDynValue()}]
    [{assign var="fatchipcomutopoptions" value=$oView->getFatchipComputopConfig()}]

    [{if $sPaymentID == 'fatchip_computop_creditcard'}]
        [{include file="fatchip_computop_creditcard_iframe.tpl"}]
    [{/if}]
