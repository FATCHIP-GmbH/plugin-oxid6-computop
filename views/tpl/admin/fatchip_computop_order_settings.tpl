[{include file="headitem.tpl" title="`oxmultilang ident='GENERAL_ADMIN_TITLE'`"}]

<script type="text/javascript">
    <!--
    function toggleRefundType(oElem) {
        var quantityDisplay = oElem.value === 'quantity' ? '' : 'none';
        var amountDisplay = oElem.value === 'amount' ? '' : 'none';

        setDisplayStyleForClassName('refundQuantity', quantityDisplay);
        setDisplayStyleForClassName('refundAmount', amountDisplay);
    }

    function setDisplayStyleForClassName(className, displayStyle) {
        var aElements = document.getElementsByClassName(className);
        for (i = 0; i < aElements.length; i++) {
            aElements[i].style.display = displayStyle;
        }
    }

    function copyRefundDescription(oElem) {
        var aFormElements = document.getElementsByClassName("refund_description");
        if (typeof aFormElements !== undefined && aFormElements.length > 0) {
            for (var i = 0; i < aFormElements.length; i++) {
                aFormElements[i].value = oElem.value;
            }
        }
    }

    //-->
</script>
<style>
    .refundTable TD {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    TD.borderTop {
        border-top: 1px solid black !important;
    }

    FIELDSET {
        border-radius: 15px;
        margin-bottom: 20px;
        padding: 10px;
    }

    FIELDSET.fullRefund SPAN {
        margin-left: 2px;
    }

    FIELDSET .refundSubmit {
        margin-top: 15px;
    }

    .typeSelect {
        margin-bottom: 10px;
    }

    FIELDSET.refundError {
        background-color: #FF8282;
        color: black;
        border: 3px solid #F00000;
    }

    FIELDSET.refundNotice {
        background-color: #ffeeb5;
        border: 3px solid #FFE385;
    }

    FIELDSET.refundSuccess {
        background-color: #7aff9e;
        border: 3px solid #00b02f;
    }

    FIELDSET.message STRONG {
        display: block;
        margin-bottom: 10px;
    }
</style>

[{assign var="readonly" value=""}]
[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="fatchip_computop_order_settings">
</form>

[{if $oView->isComputopOrder() }]
    [{if $oView->wasRefundSuccessful()}]
        <fieldset class="refundSuccess message">
            [{oxmultilang ident="COMPUTOP_REFUND_SUCCESS"}]
        </fieldset>
    [{/if}]
    [{if $oView->getErrorMessage() }]
    <fieldset class="refundError message">
        <strong>Error</strong>
        [{$oView->getErrorMessage()}]
    </fieldset>
    [{/if}]

    [{if $oView->hasOrderVoucher()}]
        <fieldset class="refundNotice message">
            <strong>[{oxmultilang ident="Computop_NOTICE"}]</strong>
            [{oxmultilang ident="Computop_VOUCHERS_EXISTING"}]
        </fieldset>
    [{/if}]

[{assign var="order" value=$oView->getOrder()}]
[{assign var="paymentType" value=$order->getPaymentType()}]
<fieldset>
    <legend>[{oxmultilang ident="FATCHIP_COMPUTOP_PAYMENT_DETAILS"}]</legend>
    <table>
        <tr>
            <td class="edittext">
                [{oxmultilang ident="FATCHIP_COMPUTOP_PAYMENT_API_LOG_PAYMENT_NAME"}]:
            </td>
            <td class="edittext">
                [{$paymentType->oxpayments__oxdesc->value}]
            </td>
            <td class="edittext"></td>
        </tr>
        <tr>
            <td class="edittext">
                [{oxmultilang ident="FATCHIP_COMPUTOP_PAYMENT_API_LOG_RESPONSE_TRANS_ID"}]:
            </td>
            <td class="edittext">
                [{$order->oxorder__fatchip_computop_transid->value}]
            </td>
            <td class="edittext"></td>
        </tr>
        [{if $order->oxorder__fatchip_computop_payid->value }]
        <tr>
            <td class="edittext">
                [{oxmultilang ident="FATCHIP_COMPUTOP_ORDER_PAYID"}]:
            </td>
            <td class="edittext">
                [{$order->oxorder__fatchip_computop_payid->value}]
            </td>
            <td class="edittext"></td>
        </tr>
        [{/if}]

        [{assign var="capturedAmount" value=$order->getCapturedAmount()}]
        [{assign var="refundedAmount" value=$order->getRefundedAmount()}]
        [{assign var="totalSum" value=$order->oxorder__oxtotalordersum->value}]

        [{if $capturedAmount }]
        <tr>
            <td class="edittext">
                [{oxmultilang ident="FATCHIP_COMPUTOP_ORDER_CAPTURED_AMOUNT"}]:
            </td>
            <td class="edittext">
                [{$capturedAmount}]
            </td>
            <td class="edittext"></td>
        </tr>
        [{/if}]
        [{if $refundedAmount }]
        <tr>
            <td class="edittext">
                [{oxmultilang ident="FATCHIP_COMPUTOP_ORDER_REFUND_AMOUNT"}]:
            </td>
            <td class="edittext">
                [{$refundedAmount}]
            </td>
            <td class="edittext"></td>
        </tr>
        [{/if}]
    </table>
</fieldset>
    [{if $oView->getErrorMessage() }]
    <fieldset class="refundError message">
        <strong>Error</strong>
        [{$oView->getErrorMessage()}]
    </fieldset>
    [{/if}]
    [{if $oView->getSNoticeMessage() }]
    <fieldset class="refundNotice message">
        <strong>Notice</strong>
        [{$oView->getSNoticeMessage()}]
    </fieldset>
    [{/if}]
    [{if $oView->wasRefundSuccessful()}]
        <fieldset class="refundSuccess message">
            [{oxmultilang ident="COMPUTOP_REFUND_SUCCESS"}]
        </fieldset>
    [{/if}]
[{if $order->getCapturedAmount() == "0.0"}]
<fieldset>
    <legend>[{oxmultilang ident="FATCHIP_COMPUTOP_CAPTURE_TITLE"}]</legend>
    <table cellspacing="0" cellpadding="0" border="0" width="98%" class="refundTable">
        <tr>
            <td class="listheader first" height="15" width="10%">[{oxmultilang ident="GENERAL_ITEMNR"}]</td>
            <td class="listheader" width="10%">[{oxmultilang ident="GENERAL_TITLE"}]</td>
            <td class="listheader" width="10%">[{oxmultilang ident="FATCHIP_COMPUTOP_HEADER_SINGLE_PRICE"}]</td>
            <td class="listheader" width="10%">[{oxmultilang ident="GENERAL_ATALL"}]</td>
            <td class="listheader" width="10%">[{oxmultilang ident="ORDER_ARTICLE_MWST"}]</td>
        </tr>
        [{assign var="blWhite" value=""}]
        [{assign var="class" value=""}]
        [{assign var="blBorderDrawn" value=false}]
        [{assign var="_cnt" value=0}]

        [{foreach from=$oView->getRefundItems() item=listitem}]
        [{assign var="_cnt" value=$_cnt+1}]
        <tr id="art.[{$_cnt}]">
            [{if !$listitem.isOrderarticle && !$blBorderDrawn}]
                [{assign var="class" value=" borderTop"}]
                [{assign var="blBorderDrawn" value=true}]
            [{/if}]
            [{assign var="listclass" value=$listitem->blWhite}]
            <td valign="top" class="[{$listclass}][{$class}]" height="15">[{$listitem.artnum}]</td>
            <td valign="top" class="[{$listclass}][{$class}]">[{$listitem.title|strip_tags}]</td>
            <td valign="top" class="[{$listclass}][{$class}]">
                [{$oView->getFormatedPrice($listitem.singlePrice)}]
                <small>[{$edit->oxorder__oxcurrency->value}]</small>
            </td>
            <td valign="top" class="[{$listclass}][{$class}]">
                [{$oView->getFormatedPrice($listitem.totalPrice)}]
                <small>[{$edit->oxorder__oxcurrency->value}]</small>
            </td>
            <td valign="top" class="[{$listclass}][{$class}]">[{$listitem.vat}]</td>
            [{if !$listitem.isOrderarticle}]
                [{assign var="class" value=""}]
            [{/if}]
        </tr>
        [{if $blWhite == "2"}]
            [{assign var="blWhite" value=""}]
        [{else}]
            [{assign var="blWhite" value="2"}]
        [{/if}]
        [{/foreach}]
        <tr>
            <td valign="top" class="[{$listclass}][{$class}]" nowrap>
                        <span class="refundQuantity">
                            <form action="[{$oViewConf->getSelfLink()}]" method="post">
                                [{$oViewConf->getHiddenSid()}]
                                <input type="hidden" name="cl" value="fatchip_computop_order_settings">
                                <input type="hidden" name="oxid" value="[{$oxid}]">
                                <input type="hidden" name="fnc" value="captureManual">
                                <input type="hidden" name="capture_description" value="" class="capture_description">
                                <input type="text" name="captureAmount"
                                       value="[{$order->oxorder__oxtotalordersum->value}]" class="listedit">
                                <input type="submit"
                                       value="[{oxmultilang ident='FATCHIP_COMPUTOP_CAPTURE_SUBMIT'}]">
                            </form>
                        </span>
            </td>
        </tr>
    </table>
    <br>
</fieldset>
[{else}]
[{if $totalSum != $refundedAmount}]
    <fieldset>
        <legend>[{oxmultilang ident="FATCHIP_COMPUTOP_REFUND"}]</legend>

        <table cellspacing="0" cellpadding="0" border="0" width="98%" class="refundTable">
            <tr>
                <td class="listheader first" height="15" width="10%">[{oxmultilang ident="GENERAL_ITEMNR"}]</td>
                <td class="listheader" width="10%">[{oxmultilang ident="GENERAL_TITLE"}]</td>
                <td class="listheader" width="10%">[{oxmultilang ident="FATCHIP_COMPUTOP_HEADER_SINGLE_PRICE"}]</td>
                <td class="listheader" width="10%">[{oxmultilang ident="GENERAL_ATALL"}]</td>
                <td class="listheader" width="10%">[{oxmultilang ident="ORDER_ARTICLE_MWST"}]</td>
                <td class="listheader" width="10%">[{oxmultilang ident="COMPUTOP_ARTICLE_REFUNDED"}]</td>
                <td class="listheader" width="10%">Auswahl</td>
            </tr>
            [{assign var="blWhite" value=""}]
            [{assign var="class" value=""}]
            [{assign var="blBorderDrawn" value=false}]
            [{assign var="_cnt" value=0}]

            <form action="[{$oViewConf->getSelfLink()}]" method="post">
                [{$oViewConf->getHiddenSid()}]
                <input type="hidden" name="cl" value="fatchip_computop_order_settings">
                <input type="hidden" name="oxid" value="[{$oxid}]">
                <input type="hidden" name="fnc" value="refundSpecificArticles">

                [{foreach from=$oView->getRefundItems() item=listitem}]
                [{assign var="_cnt" value=$_cnt+1}]
                <tr id="art.[{$_cnt}]">
                    [{if !$listitem.isOrderarticle && !$blBorderDrawn}]
                    [{assign var="class" value=" borderTop"}]
                    [{assign var="blBorderDrawn" value=true}]
                    [{/if}]
                    [{assign var="listclass" value=$listitem->blWhite}]

                    <input type="hidden" name="aArtId[[{$listitem.id}]][oxid]" value="[{$listitem.id}]"/>
                    <input type="hidden" name="aArtId[[{$listitem.id}]][price]" value="[{$listitem.totalPrice}]"/>
                    [{if !$listitem.isOrderarticle}]
                    <input type="hidden" name="aArtId[[{$listitem.id}]][shipping]" value="1"/>
                    [{/if}]

                    <td valign="top" class="[{$listclass}][{$class}]" height="15">[{$listitem.artnum}]</td>
                    <td valign="top" class="[{$listclass}][{$class}]">[{$listitem.title|strip_tags}]</td>
                    <td valign="top" class="[{$listclass}][{$class}]">
                        [{$oView->getFormatedPrice($listitem.singlePrice)}]
                        <small>[{$edit->oxorder__oxcurrency->value}]</small>
                    </td>
                    <td valign="top" class="[{$listclass}][{$class}]">
                        [{$oView->getFormatedPrice($listitem.totalPrice)}]
                        <small>[{$edit->oxorder__oxcurrency->value}]</small>
                    </td>
                    <td valign="top" class="[{$listclass}][{$class}]">[{$listitem.vat}]</td>
                    <td valign="top" class="[{$listclass}][{$class}]">[{$listitem.refunded}]</td>
                    <td valign="top" class="[{$listclass}][{$class}]">
                        <input type="checkbox" name="aArtId[[{$listitem.id}]][refundthis]">
                    </td>

                    [{if !$listitem.isOrderarticle}]
                    [{assign var="class" value=""}]
                    [{/if}]
                </tr>

                [{if $blWhite == "2"}]
                [{assign var="blWhite" value=""}]
                [{else}]
                [{assign var="blWhite" value="2"}]
                [{/if}]
                [{/foreach}]

                <tr>
                    <td>
                        <input type="submit" value="[{oxmultilang ident="FATCHIP_COMPUTOP_REFUND_ARTICLES_SUBMIT"}]">
                    </td>
                </tr>
            </form>
        </table>
        <br>
    </fieldset>

    [{if $refundedAmount == ""}]
    <fieldset>
        <legend>[{oxmultilang ident="FATCHIP_COMPUTOP_REFUND_ALL"}]</legend>
        <table>
            <tr>
                <td valign="top" class="[{$listclass}][{$class}]" nowrap>
                        <span class="refundQuantity">
                            <form action="[{$oViewConf->getSelfLink()}]" method="post">
                                [{$oViewConf->getHiddenSid()}]
                                <input type="hidden" name="cl" value="fatchip_computop_order_settings">
                                <input type="hidden" name="oxid" value="[{$oxid}]">
                                <input type="hidden" name="fnc" value="refundOrderArticles">
                                <input type="hidden" name="capture_description" value="" class="capture_description">
                                <input type="text" name="captureAmount"
                                       value="[{$order->oxorder__oxtotalordersum->value}]" class="listedit" readonly>
                                <input type="submit"
                                       value="[{oxmultilang ident="FATCHIP_COMPUTOP_REFUND_SUBMIT"}]">
                            </form>
                        </span>
                </td>
            </tr>
        </table>
    </fieldset>
    [{/if}]
    [{else}]
    <fieldset class="refundNotice message">
        [{oxmultilang ident="FATCHIP_COMPUTOP_CAPTURE_SUBMIT_COMPLETE"}]
    </fieldset>
    [{/if}]
[{/if}]
[{/if}]
[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]