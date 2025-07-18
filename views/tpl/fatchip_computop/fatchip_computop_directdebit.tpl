[{oxstyle include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/css/computop_frontend.css')}]
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BANKNAME"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_BANKNAME"}]"
               name="dynvalue[[{$sPaymentID}]_bankname]"
               value="[{$dynvalue.fatchip_computop_lastschrift_bankname}]">
    </div>
</div>
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_IBAN"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_IBAN"}]"
               name="dynvalue[[{$sPaymentID}]_iban]"
               value="[{$dynvalue.fatchip_computop_lastschrift_iban}]">
    </div>
</div>
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BANK_ACCOUNT_HOLDER"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_BANK_ACCOUNT_HOLDER"}]"
               name="dynvalue[[{$sPaymentID}]_bank_account_holder]"
               value="[{if $dynvalue.fatchip_computop_lastschrift_bank_account_holder}][{$dynvalue.fatchip_computop_lastschrift_bank_account_holder}][{else}][{$oxcmp_user->oxuser__oxfname->value }] [{ $oxcmp_user->oxuser__oxlname->value }][{/if}]">
    </div>
</div>
