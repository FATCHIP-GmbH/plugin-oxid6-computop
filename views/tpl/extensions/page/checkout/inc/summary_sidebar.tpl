[{$smarty.block.parent}]

[{if $oViewConf->isPaypalActive()}]
    [{if $oViewConf->getActiveClassName() eq 'basket'}]
    <div class="w-100 mb-2">
        [{include file="fatchip_computop_paypalbutton.tpl" buttonId="paypal_button_container" buttonclass="large" placement="Cart"}]
    </div>
    [{/if}]
[{/if}]
