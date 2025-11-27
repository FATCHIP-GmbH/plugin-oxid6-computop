[{capture append="oxidBlock_content"}]
    [{include file="page/checkout/inc/steps.tpl" active=4}]
    <style>
        .iframe-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 55vh;
        }

        iframe {
            border: none;
            width: 80% !important;
            height: 80% !important;
        }
    </style>

    <div style="clear:both;"></div>
    <div class="iframe-container">
        <iframe src="[{$oView->getIframeUrl()}]" style="width:100%; height:750px;" frameborder="0" allow="payment"></iframe>
    </div>
[{/capture}]
[{include file="layout/page.tpl" title=$template_title location=$template_title}]
