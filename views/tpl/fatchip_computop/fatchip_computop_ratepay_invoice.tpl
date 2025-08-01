<div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_TELEPHONE"}]:</label>
    <div class="col-lg-9">
        <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64"
               placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_TELEPHONE"}]"
               name="dynvalue[[{$sPaymentID}]_telephone]"
               value="[{if $dynvalue.fatchip_computop_ratepay_debit_telephone}][{$dynvalue.fatchip_computop_ratepay_debit_telephone}][{else}][{$oView->ctGetTelephoneNumber()}][{/if}]">
    </div>
</div>
[{if $oView->showBirthdate() }]
    <div class="form-group">
        <label class="req control-label col-lg-3">[{oxmultilang ident="FATCHIP_COMPUTOP_BIRTHDAY"}]:</label>
        <div class="col-lg-2 ctBirthday">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64"
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_DAY_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthday]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthday}]">
        </div>
        <div class="col-lg-2 ctBirthmonth">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64"
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_MONTH_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthmonth]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthmonth}]">
        </div>
        <div class="col-lg-2 ctBirthyear">
            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64"
                   placeholder="[{oxmultilang ident="FATCHIP_COMPUTOP_YEAR_OF_BIRTH"}]"
                   name="dynvalue[[{$sPaymentID}]_birthyear]"
                   value="[{$dynvalue.fatchip_computop_ratepay_debit_birthyear}]">
        </div>
        <div class="col-lg-2 ctBirthformat">
            (DD.MM.YYYY)
        </div>
    </div>
[{/if}]
<div class="desc ctRatepayLegalText">
    [{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_POLICY"}]
</div>
