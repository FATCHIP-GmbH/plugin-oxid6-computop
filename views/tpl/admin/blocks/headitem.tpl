[{if $title|lower == "fatchip_computop_apilog"}]
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap.min.css')}]}]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap-editable.css') }]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap-table.css') }]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/fatchip_computop.css') }]" />
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/jquery.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/jsonviewer.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-editable.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table-de-DE.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table-editable.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/api_log.js') priority=30}]
    [{elseif $title|lower == "fatchip_computop"}]
    [{$sModuleId|lower}]
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap.min.css') }]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap-editable.css') }]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/bootstrap-table.css') }]" />
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fatchip_computop_payments', 'assets/css/fatchip_computop.css') }]" />
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/jquery.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-editable.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table-de-DE.min.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/bootstrap-table-editable.js') priority=30}]
        [{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/config.js') priority=30}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]

