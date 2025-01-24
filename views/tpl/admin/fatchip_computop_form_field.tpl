{% if value.name == 'creditCardMode' %}
    {% set currentBlock = 'creditcard' %}
{% elseif value.name == 'creditCardTemplate' %}
    {% set currentBlock = 'general' %}
{% endif %}

<div id="{{ value.name }}_block" class="form-group row mb-3 {% if currentBlock == 'creditcard' %}creditcard-block{% else %}general-block{% endif %}">
    <div class="col-md-4">
        <label for="{{ value.name }}" class="control-label">{{ value.label|raw }}</label>
        <div class="help-button-container">
            {% if value.description is defined %}
                <button type="button" class="btn btn-sm btn-info" data-toggle="popover" data-trigger="focus" title="Hilfe" data-content="{{ value.description|e('html_attr') }}">
                    <i class="fa fa-question-circle"></i> Hilfe
                </button>
            {% endif %}
        </div>
    </div>
    <div class="col-md-8">
        {% set fieldValue = config[value.name].current_value is defined ? config[value.name].current_value : value.value %}

        {% if value.type == 'text' %}
            <input type="text" class="form-control" name="conf[{{ value.name }}]" value="{{ fieldValue }}" id="{{ value.name }}">
        {% elseif value.type == 'select' %}
            <select name="conf[{{ value.name }}]" id="{{ value.name }}" class="form-control">
                {% for option, optionvalues in value.store %}
                    <option value="{{ optionvalues.0.0 }}" {% if fieldValue == optionvalues.0.0 %}selected{% endif %}>
                        {% if optionvalues.0.0 == '0' %}Nein{% elseif optionvalues.0.0 == '1' %}Ja{% else %}{{ optionvalues.0.0|raw }}{% endif %}
                    </option>
                {% endfor %}
            </select>
        {% endif %}
    </div>
</div>

{% if value.when is defined %}
<script type="application/javascript">
    window.addEventListener('load',() => {
        document.querySelector('#{{ value.name }}').addEventListener('change',function(){
            handle(this,{{ value.when|json_encode|raw }});
        });

        handle(document.querySelector('#{{ value.name }}'),{{ value.when|json_encode|raw }});
    })
</script>
{% endif %}