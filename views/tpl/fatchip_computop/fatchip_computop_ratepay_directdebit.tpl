[{oxstyle include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/css/computop_frontend.css')}]
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BANK_ACCOUNT_HOLDER"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_BANK_ACCOUNT_HOLDER"}]"
               name="dynvalue[[{$sPaymentID}]_accountholder]"
               value="[{if $dynvalue.fatchip_computop_ratepay_debit_accountholder}][{$dynvalue.fatchip_computop_ratepay_debit_accountholder}][{else}][{$oxcmp_user->oxuser__oxfname->value }] [{ $oxcmp_user->oxuser__oxlname->value }][{/if}]">
    </div>
</div>
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_IBAN"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_IBAN"}]"
               name="dynvalue[[{$sPaymentID}]_iban]"
               value="[{$dynvalue.fatchip_computop_ratepay_debit_iban}]">
    </div>
</div>
[{if $oView->isBicNeeded() }]
    <div class="form-group">
        <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BIC"}]:</label>
        <div class="col-lg-9">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_BIC"}]"
                   name="dynvalue[[{$sPaymentID}]_bic]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_bic}]">
        </div>
    </div>
[{/if}]
<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_TELEPHONE"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_TELEPHONE"}]"
               name="dynvalue[[{$sPaymentID}]_telephone]"
               value="[{if $dynvalue.fatchip_computop_ratepay_debit_telephone}][{$dynvalue.fatchip_computop_ratepay_debit_telephone}][{else}][{$oView->ctGetTelephoneNumber()}][{/if}]">
    </div>
</div>
[{if $oView->showBirthdate() }]
    <div class="form-group">
        <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BIRTHDAY"}]:</label>
        <div class="col-lg-2 ctBirthday">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_DAY_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthday]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthday}]">
        </div>
        <div class="col-lg-2 ctBirthmonth">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_MONTH_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthmonth]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthmonth}]">
        </div>
        <div class="col-lg-2 ctBirthyear">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" required
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_YEAR_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthyear]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthyear}]">
        </div>
        <div class="col-lg-2 ctBirthformat">
            (DD.MM.YYYY)
        </div>
    </div>
[{/if}]