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

            [{if $oView->getIdealUpdateSuccess() === null}]
            [{* No action when getIdealUpdateSuccess() is null (initial state) *}]
            [{else}]
            <div class="alert alert-[{if $oView->getIdealUpdateSuccess()}]success[{else}]danger[{/if}] mb-3" role="alert">
                [{if $oView->getIdealUpdateSuccess()}]
                [{assign var="idealMessage" value="FATCHIP_COMPUTOP_IDEAL_ISSUERS"}]
                [{else}]
                [{assign var="idealMessage" value="FATCHIP_COMPUTOP_IDEAL_ISSUERS_ERROR"}]
                [{/if}]

                [{oxmultilang ident=$idealMessage}]            </div>
            [{/if}]
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

        [{* iDEAL Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_IDEAL_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_IDEAL_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingIdeal' collapseId='collapseIdeal'
        title=$FATCHIP_COMPUTOP_IDEAL_SETTINGS
        formFields=$idealFormFields currentBlock='ideal' }]

        [{* PayPal Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingPayPal' collapseId='collapsePayPal'
        title=$FATCHIP_COMPUTOP_PAYPAL_SETTINGS
        formFields=$payPalFormFields currentBlock='paypal' }]

        [{* PayPal Express Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_PAYPAL_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingPayPalExpress' collapseId='collapsePayPalExpress'
        title=$FATCHIP_COMPUTOP_PAYPAL_SETTINGS
        formFields=$payPalExpressFormFields currentBlock='paypalExpress' }]

        [{* Direct Debit (Lastschrift) Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_DIRECT_DEBIT_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_DIRECT_DEBIT_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingLastschrift' collapseId='collapseLastschrift'
        title=$FATCHIP_COMPUTOP_DIRECT_DEBIT_SETTINGS
        formFields=$lastschriftFormFields currentBlock='lastschrift' }]

        [{* Amazon Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_AMAZON_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_AMAZON_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingAmazon' collapseId='collapseAmazon'
        title=$FATCHIP_COMPUTOP_AMAZON_SETTINGS
        formFields=$amazonFormFields currentBlock='amazon' }]

        [{* Klarna Settings *}]
        [{capture assign="FATCHIP_COMPUTOP_KLARNA_SETTINGS"}][{oxmultilang ident="FATCHIP_COMPUTOP_KLARNA_SETTINGS"}][{/capture}]
        [{include file="accordion_section.tpl" headingId='headingKlarna' collapseId='collapseKlarna'
        title=$FATCHIP_COMPUTOP_KLARNA_SETTINGS
        formFields=$klarnaFormFields currentBlock='klarna' }]

        <br />
        <div class="form-group row mt-3">
            <div class="col-md-12 text-center">
                <button type="submit" name="saveButton" class="btn btn-primary">Speichern</button>
            </div>
        </div>
    </form>
</div>

[{include file="bottomitem.tpl"}]
