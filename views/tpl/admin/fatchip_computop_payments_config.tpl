[{include file="headitem.tpl" title="fatchip_computop"}]
[{assign var="sModuleId" value='fatchip_computop_payments'}]
[{assign var="sModuleUrl" value=$oViewConf->getModuleUrl('fatchip_computop_payments')}]

<div id="content" class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>[{oxmultilang ident="FATCHIP_COMPUTOP_PAYMENT"}]</h1>

            <div class="alert alert-[{if $Errors.fatchip_computop_error}]danger[{else}]success[{/if}] mb-3" role="alert">
                [{if $Errors.fatchip_computop_error}]
                [{oxmultilang ident="FATCHIP_COMPUTOP_ERR_CONF_INVALID"}]:
                <ul class="list-unstyled">
                    [{foreach from=$Errors.fatchip_computop_error key=key item=oEr}]
                    [{assign var="errorMessage" value=$oEr->getOxMessage()->getRawValue}]
                    <li>[{$errorMessage }]</li>
                    [{/foreach}]
                </ul>
                [{else}]
                [{oxmultilang ident="FATCHIP_COMPUTOP_CONF_VALID"}]
                [{/if}]
            </div>

        </div>
    </div>

    <form action="[{$oViewConf->getSelfLink() }]" method="post" class="panel-group" id="accordion">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName() }]">
        <input type="hidden" name="fnc" value="save">

        [{* General Settings *}]
        [{capture assign="generalSettingsTitle"}][{oxmultilang ident="FATCHIP_COMPUTOP_GENERAL_SETTINGS"}][{/capture}]

        [{include file="accordion_section.tpl"
        headingId='headingGeneral'
        collapseId='collapseGeneral'
        title=$generalSettingsTitle
        formFields=$generalFormFields
        currentBlock='general'
        expanded=false
        }]

        [{* Credit Card Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_CREDIT_CARD_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_CREDIT_CARD_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingCreditCard' collapseId='collapseCreditCard'
        title=$FATCHIP_COMPUTOP_CREDIT_CARD_SETTINGS
        formFields=$creditCardFormFields currentBlock='creditcard' }]

        [{* PayPal Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingPayPal' collapseId='collapsePayPal'
        title=$FATCHIP_COMPUTOP_PAYPAL_SETTINGS
        formFields=$payPalFormFields currentBlock='paypal' }]

        [{* PayPal Express Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_PAYPALEXPRESS_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingPayPalExpress' collapseId='collapsePayPalExpress'
        title=$FATCHIP_COMPUTOP_PAYPAL_SETTINGS
        formFields=$payPalExpressFormFields currentBlock='paypalExpress' }]
        <br />
        <div class="form-group row mt-3">
            <div class="col-md-12 text-center">
                <button type="submit" name="saveButton" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </form>
</div>

[{include file="bottomitem.tpl"}]
