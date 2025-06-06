<script type="application/javascript">
    function handle(element,whenConditions){
        let currentValue = element.value;
        if(whenConditions !== null){
            for(let condition of whenConditions){
                if(condition.value == currentValue){
                    if(condition.do.hide !== undefined){
                        for(let toHideElement of condition.do.hide){
                            document.getElementById(toHideElement).classList.add('hidden');
                        }
                    }
                    if(condition.do.show !== undefined){
                        for(let toHideElement of condition.do.show){
                            document.getElementById(toHideElement).classList.remove('hidden');
                        }
                    }
                }
            }
        }
    }
</script>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="[{$headingId}]">
        <h4 class="panel-title">
            <a class="[{if !$expanded}]collapsed[{/if}]" data-toggle="collapse" data-parent="#accordion" href="#[{$collapseId}]" aria-expanded="[{if $expanded}]true[{else}]false[{/if}]" aria-controls="[{$collapseId}]">
                [{$title}]
            </a>
        </h4>
    </div>
    <div id="[{$collapseId}]" class="panel-collapse collapse [{if $expanded}]in[{/if}]" role="tabpanel" aria-labelledby="[{$headingId }]">
        <div class="panel-body">
            [{foreach from=$formFields key=item item=value}]
             [{include file="fatchip_computop_form_field.tpl" value=$value currentBlock=$currentBlock}]
            [{/foreach}]
        </div>
    </div>
</div>
