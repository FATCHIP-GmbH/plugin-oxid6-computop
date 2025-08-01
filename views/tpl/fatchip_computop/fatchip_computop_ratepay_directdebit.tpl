[{oxstyle include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/css/computop_frontend.css')}]
[{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/computop.js') priority=30 dynamic=$__oxid_include_dynamic}]
<div class="ctAcceptMandateError" id="ctRatepayDirectDebitAcceptMandateError" style="display:none">
    [{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_ACCEPT_MANDATE_ERROR"}]
</div>
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
[{if $oView->showBICInput() }]
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
<div class="desc ctRatepayMandateText">
    <input type="hidden" name="dynvalue[[{$sPaymentID}]_accept_sepa_mandate]" value="0">
    <input type="checkbox" name="dynvalue[[{$sPaymentID}]_accept_sepa_mandate]" id="computop_ratepay_direct_debug_accept_sepa_mandate" value="1" required onchange="ctHideElement('ctRatepayDirectDebitAcceptMandateError');">
    <label for="computop_ratepay_direct_debug_accept_sepa_mandate">[{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_MANDATE_ACCEPT"}] <a href="#" onclick="ctToggleMandateText();return false;">[{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_MANDATE_UNFOLD_TEXT"}]</a></label>
    <div id="ctRatepayMandateText" style="display:none" class="ctRatepayMandateTextBox">
        [{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_MANDATE_TEXT"}]
    </div>
</div>
<div class="desc ctRatepayLegalText">
    [{oxmultilang ident="FATCHIP_COMPUTOP_RATEPAY_POLICY"}]
</div>
[{capture name="fatchip_computop_ratepay_onsubmit"}]
    ctAddPaymentFormSubmitEvent(function(e) {
        ctValidateRatepayDirectDebitForm(e);
    }, '[{$sPaymentID}]');
[{/capture}]
[{oxscript add=$smarty.capture.fatchip_computop_ratepay_onsubmit}]
