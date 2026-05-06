[{assign var="ctRatepayToken" value=$oView->computopGetRatepayFingerprintToken()}]
[{assign var="ctRatepaySnippetId" value=$oView->computopGetRatepayFingerprintSnippetId()}]

[{if $ctRatepayToken}]
    [{capture name="fatchip_computop_ratepay_dfp"}]
        window.di = {
            t: '[{$ctRatepayToken}]',
            v: '[{$ctRatepaySnippetId}]',
            l:'checkout'
        };

        var diScript = document.createElement('script');
        diScript.type = 'text/javascript';
        diScript.src = '//d.ratepay.com/[{$ctRatepaySnippetId}]/di.js';
        document.getElementsByTagName('head')[0].appendChild(diScript);
    [{/capture}]
    [{oxscript add=$smarty.capture.fatchip_computop_ratepay_dfp}]
    <noscript><link rel="stylesheet" type="text/css" href="https://d.ratepay.com/di.css?t=[{$ctRatepayToken}]&v=[{$ctRatepaySnippetId}]&l=Checkout"></noscript>
    [{$oView->computopMarkDfpAsSent()}]
[{/if}]
