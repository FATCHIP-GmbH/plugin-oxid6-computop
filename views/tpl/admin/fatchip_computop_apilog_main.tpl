[{include file="headitem.tpl" title="fatchip_computop_apilog"}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
    [{/if}]

[{assign var="edit" value=$oView->getEdit()}]
<style>
    .edittext pre {
        word-wrap: break-word;
        white-space: pre-wrap;
    }
    .show-more {
        color: red;
    }
    #editval_computop_no_entry {
        margin: 20px;
        padding: 30px;
    }
</style>
<script type="text/javascript">
    function editThis(sID) {
        var oTransfer = top.basefrm.edit.document.getElementById("transfer");
        oTransfer.oxid.value = sID;
        oTransfer.cl.value = top.basefrm.list.sDefClass;

        // Forcing edit frame to reload after submit
        top.forceReloadingEditFrame();

        var oSearch = top.basefrm.list.document.getElementById("search");
        oSearch.oxid.value = sID;
        oSearch.actedit.value = 0;
        oSearch.submit();
    }

    window.onload = function () {
        top.oxid.admin.updateList('[{$oxid }]');
    }
</script>

<style>
    tr.response {
        margin-bottom: 29px;
    }
    .request, .response {
        border: 1px solid #A9A9A9;
        padding-left: 15px;
        display: block;
        width: 1600px;
        margin: 0 18px 20px 24px;
    }
    .linebox {
        border: 1px solid #A9A9A9;
        margin-bottom: 15px;
        padding-left: 10px;
        width: 1572px;
        background-color: #eee;
    }
</style>

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid }]">
    <input type="hidden" name="oxidCopy" value="[{$oxid }]">
    <input type="hidden" name="cl" value="fatchip_computop_apilog_main">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

[{if $edit}]
    [{assign var="request" value=$oView->getRequest()}]
    [{assign var="response" value=$oView->getResponse()}]
    [{assign var="responseDetails" value=$oView->getResponseDetails()}]
    [{assign var="requestDetails" value=$oView->getRequestDetails()}]
    <table cellspacing="0" cellpadding="0" border="0" style="width:98%;border-collapse: collapse;">
        <tr class="response">
            <td id="editval_computop__response">
                [{if $requestDetails != false}]
                <h2>Request Details</h2>
                <andypf-json-viewer data='[{$requestDetails}]' indent="2"
                                    expanded="false"
                                    theme="default-light"
                                    show-data-types="true"
                                    show-toolbar="true"
                                    expand-icon-type="arrow"
                                    show-copy="true"
                                    show-size="true"></andypf-json-viewer>
                [{/if}]
            </td>
        </tr>
        <tr class="response">
            <td id="editval_computop__response">
                [{if $responseDetails != false}]
                <h2>Response Details</h2>
                <pre class="prettyprint language-js"><code></code></pre>
                <andypf-json-viewer data='[{$responseDetails}]' indent="2"
                                    expanded="false"
                                    theme="default-light"
                                    show-data-types="true"
                                    show-toolbar="true"
                                    expand-icon-type="arrow"
                                    show-copy="true"
                                    show-size="true"></andypf-json-viewer>
                [{/if}]
            </td>
        </tr>
    </table>
    [{else}]
    <div id="editval_computop_no_entry" class="">
        [{oxmultilang ident="fatchip_computop_ADMIN_API_LOGS_SELECT_ENTRY"}]
    </div>
    [{/if}]

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
