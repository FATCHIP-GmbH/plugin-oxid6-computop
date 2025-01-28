{% extends 'page/checkout/payment.html.twig' %}

{% block checkout_payment_errors %}
    {{ parent() }}
{% endblock %}

{% block checkout_payment %}
    {{ parent() }}
{% endblock %}

{% block change_payment %}
    {{ parent() }}
{% endblock %}

{% block select_payment %}
    {% set dynvalue = oView.getDynValue() %}
    {% set fatchipcomutopoptions = oView.getFatchipComputopConfig() %}

    {% if sPaymentID == 'fatchip_computop_lastschrift'  %}
        {% include "@fatchip_computop_payments/payments/fatchip_computop_debitnote" %}
    {% elseif sPaymentID == 'fatchip_computop_ideal' %}
        {% include "@fatchip_computop_payments/payments/fatchip_computop_ideal" %}
    {% elseif sPaymentID == 'fatchip_computop_easycredit' %}
        {% include "@fatchip_computop_payments/payments/fatchip_computop_easycredit" %}
    {% elseif sPaymentID == 'fatchip_computop_creditcard' %}
        {% include "@fatchip_computop_payments/payments/fatchip_computop_creditcard_iframe" %}
    {% elseif sPaymentID == 'fatchip_computop_amazonpay' %}
        {% include "@fatchip_computop_payments/payments/fatchip_computop_amazonpay" %}
    {% else %}
        {{ parent() }}
    {% endif %}
{% endblock %}
