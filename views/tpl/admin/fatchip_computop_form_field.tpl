[{if $value.name == 'creditCardMode'}]
    [{assign var="currentBlock" value="creditcard"}]
[{elseif $value.name == 'creditCardTemplate'}]
    [{assign var="currentBlock" value="general"}]
[{/if}]

<div id="[{$value.name }]_block" class="form-group row mb-3 [{if $currentBlock == 'creditcard'}]creditcard-block[{else}]general-block[{/if}]">
    <div class="col-md-4">
        <label for="[{$value.name }]" class="control-label">[{$value.label }]</label>
        <div class="help-button-container">
            [{if isset($value.description)}]
            <button type="button" class="btn btn-sm btn-info" data-toggle="popover" data-trigger="focus" title="Hilfe" data-content='[{$value.description|escape:"html"}]'>
                <i class="fa fa-question-circle"></i> Hilfe
            </button>
            [{/if}]
        </div>
    </div>
    <div class="col-md-8">
        [{assign var="fieldValue" value=$value.value}]
        [{if isset($config[$value.name].current_value)}]
        [{assign var="fieldValue" value=$config[$value.name].current_value}]
        [{/if}]

        [{if $value.type == 'text'}]
    <input type="text" class="form-control" name="conf[[{$value.name }]]" value="[{$fieldValue }]" id="[{$value.name }]">
        [{elseif $value.type == 'select'}]
        <select name="conf[[{$value.name }]]" id="[{$value.name }]" class="form-control">

            [{foreach from=$value.store key=option item=optionvalues}]
            <option value="[{$optionvalues[0] }]" [{if $fieldValue == $optionvalues[0]}]selected[{/if}]>
                [{if $optionvalues[0] == '0'}]Nein
                [{elseif $optionvalues[0] == '1'}]Ja
                [{else}][{$optionvalues[0] }][{/if}]
            </option>
            [{/foreach}]
        </select>
        [{/if}]
    </div>
</div>

[{if isset($value.when)}]
    <script type="application/javascript">
        window.addEventListener('load', () => {
            document.querySelector('#[{$value.name }]').addEventListener('change', function () {
                handle(this, [{$value.when|json_encode}]);
            });

            handle(document.querySelector('#[{$value.name }]'), [{$value.when|json_encode }]);
        });
    </script>
    [{/if}]
