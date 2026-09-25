[{if $module_var == 'cronFinalizeOrdersDays'}]
    <dl>
        <dt>
            <select class="select" name="confselects[[{$module_var}]]" [{ $readonly }]>
                [{section name=i start=1 loop=29 step=1}]
                    [{assign var="iDay" value=$smarty.section.i.index}]
                    <option value="[{$iDay}]" [{if $confselects.$module_var == $iDay}]selected[{/if}]>[{$iDay}]&nbsp;[{if $iDay == 1}][{oxmultilang ident="FATCHIP_COMPUTOP_DAY"}][{else}][{oxmultilang ident="FATCHIP_COMPUTOP_DAYS"}][{/if}]</option>
                [{/section}]
            </select>
            [{oxinputhelp ident="HELP_SHOP_MODULE_`$module_var`"}]
        </dt>
        <dd>
            [{oxmultilang ident="SHOP_MODULE_`$module_var`"}]
        </dd>
    </dl>
[{else}]
    [{$smarty.block.parent}]
[{/if}]
