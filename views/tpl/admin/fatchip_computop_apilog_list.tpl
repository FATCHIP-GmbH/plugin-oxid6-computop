{% include "headitem.html.twig" with {title: "GENERAL_ADMIN_TITLE"|translate, box: "list"} %}
{% set where = oView.getListFilter() %}

{% if readonly %}
    {% set readonly = "readonly disabled" %}
{% else %}
    {% set readonly = "" %}
{% endif %}

<script type="text/javascript">
    <!--
    window.onload = function ()
    {
        top.reloadEditFrame();
        {% if updatelist == 1 %}
        top.oxid.admin.updateList('{{ oxid }}');
        {% endif %}
    }
    //-->
</script>

<div id="liste">
    <form name="search" id="search" action="{{ oViewConf.getSelfLink()|raw }}" method="post">
        {% include "_formparams.html.twig" with {cl: "fatchip_computop_apilog_list", lstrt: lstrt, actedit: actedit, oxid: oxid, fnc: "", language: actlang, editlanguage: actlang} %}
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <colgroup>
                <col width="25%">
                <col width="25%">
                <col width="10%">
                <col width="20%">
                <col width="20%">
            </colgroup>

            <tr class="listitem">
                <td valign="top" class="listfilter" align="left">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="15" name="where[fatchip_computop_api_log][creation_date]" value="{{ where.fatchip_computop_api_log.creation_date }}">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" align="left">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="31" name="where[fatchip_computop_api_log][trans_id]" value="{{ where.fatchip_computop_api_log.trans_id }}">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" align="left">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="2" name="where[fatchip_computop_api_log][pay_id]" value="{{ where.fatchip_computop_api_log.pay_id }}">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" align="left">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="15" name="where[fatchip_computop_api_log][request]" value="{{ where.fatchip_computop_api_log.request }}">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" align="left">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="15" name="where[fatchip_computop_api_log][payment_name]" value="{{ where.fatchip_computop_api_log.payment_name }}">
                        </div>
                    </div>
                </td>
                <input class="listedit" type="submit" name="submitit" value="{{ translate({ ident: "GENERAL_SEARCH" }) }}">
            </tr>

            <tr>
                <td class="listheader first" height="15" width="30"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'fatchip_computop_api_log', 'creation_date', 'desc');document.search.submit();" class="listheader">creation_date</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'fatchip_computop_api_log', 'trans_id', 'asc');document.search.submit();" class="listheader">trans_id</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'fatchip_computop_api_log', 'pay_id', 'asc');document.search.submit();" class="listheader">pay_id</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'fatchip_computop_api_log', 'request', 'asc');document.search.submit();" class="listheader">api-call</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'fatchip_computop_api_log', 'payment_name', 'asc');document.search.submit();" class="listheader">payment_name</a></td>
            </tr>

            {% set blWhite = "" %}
            {% set _cnt = 0 %}
            {% for listitem in mylist %}
                {% set _cnt = _cnt+1 %}
                <tr id="row.{{ _cnt }}">
                    {% block fatchip_computop_requestlog_list_item %}
                        {% if listitem.blacklist == 1 %}
                            {% set listclass = "listitem3" %}
                        {% else %}
                            {% set listclass = "listitem" ~ blWhite %}
                        {% endif %}
                        {% if listitem.getId() == oxid %}
                            {% set listclass = "listitem4" %}
                        {% endif %}
                        <td valign="top" class="{{ listclass }}" height="15">
                            <div class="listitemfloating">
                                <a href="Javascript:top.oxid.admin.editThis('{{ listitem.fatchip_computop_api_log__oxid.value }}');" class="{{ listclass }}">
                                    {{ listitem.fatchip_computop_api_log__creation_date.value }}
                                </a>
                            </div>
                        </td>
                        <td valign="top" class="{{ listclass }}" height="15">
                            <div class="listitemfloating">
                                <a href="Javascript:top.oxid.admin.editThis('{{ listitem.fatchip_computop_api_log__oxid.value }}');" class="{{ listclass }}">
                                    {{ listitem.fatchip_computop_api_log__trans_id.value }}
                                </a>
                            </div>
                        </td>
                        <td valign="top" class="{{ listclass }}" height="15">
                            <div class="listitemfloating">
                                <a href="Javascript:top.oxid.admin.editThis('{{ listitem.fatchip_computop_api_log__oxid.value }}');" class="{{ listclass }}">
                                    {{ listitem.fatchip_computop_api_log__pay_id.value }}
                                </a>
                            </div>
                        </td>
                        <td valign="top" class="{{ listclass }}" height="15">
                            <div class="listitemfloating">
                                <a href="Javascript:top.oxid.admin.editThis('{{ listitem.fatchip_computop_api_log__oxid.value }}');" class="{{ listclass }}">
                                    {{ listitem.fatchip_computop_api_log__request.value }}
                                </a>
                            </div>
                        </td>
                        <td valign="top" class="{{ listclass }}" height="15">
                            <div class="listitemfloating">
                                <a href="Javascript:top.oxid.admin.editThis('{{ listitem.fatchip_computop_api_log__oxid.value }}');" class="{{ listclass }}">
                                    {{ listitem.fatchip_computop_api_log__payment_name.value }}
                                </a>
                            </div>
                        </td>
                    {% endblock %}
                </tr>

            {% endfor %}
            {% include "pagenavisnippet.html.twig" with {colspan: "5"} %}
        </table>
    </form>
</div>

{% include "pagetabsnippet.html.twig" %}


</body>
</html>
