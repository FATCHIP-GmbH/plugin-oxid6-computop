{% extends 'page/checkout/order.html.twig' %}

{% block checkout_order_btn_confirm_bottom %}
    {{ parent() }}
    {% set payment = oView.getPayment() %}
    {% if payment.oxpayments__oxid.value == 'fatchip_computop_amazonpay'  %}
        {% include "@fatchip_computop_payments/fatchip_computop/checkout_order_btn_submit_bottom" with {paymentId: fatchip_computop_amazonpay} %}
    {% endif %}
{% endblock %}

{% block checkout_order_payment_method %}
    {{ parent() }}
    {% block checkout_order_computop_fatchip_additional_information %}
        {% set payment = oView.getPayment() %}
        {% if payment.oxpayments__oxid.value == 'fatchip_computop_easycredit'  %}
            {% include "@fatchip_computop_payments/fatchip_computop/checkout_order_easycredit"%}
        {% endif %}

    {% endblock %}
{% endblock %}
{% block checkout_order_next_step_side %}
    {% set payment = oView.getPayment() %}
    {% set getFatchipComputopShopConfigMode = oView.getFatchipComputopShopConfigMode() %}
   {{ fatchipComputopConfig['creditCardMode'] }}
    {% if payment.oxpayments__oxid.value == 'fatchip_computop_creditcard' and getFatchipComputopShopConfigMode == 'SILENT'  %}

        {% include "@fatchip_computop_payments/payments/fatchip_computop_creditcard_silentmode"%}
    {% else %}
        {{  parent() }}
    {% endif %}
{% endblock %}
{% block checkout_order_billing_address_button %}
    {% set payment = oView.getPayment() %}
    {% if payment.oxpayments__oxid.value != 'fatchip_computop_paypal_express'  %}
        {{  parent() }}
    {% endif %}
{% endblock %}
{% block checkout_order_shipping_address_button %}
    {% set payment = oView.getPayment() %}
    {% if payment.oxpayments__oxid.value != 'fatchip_computop_paypal_express'  %}
        {{  parent() }}
    {% endif %}
{% endblock %}
{% block checkout_order_shipping_carrier_button %}
    {% set payment = oView.getPayment() %}
    {% if payment.oxpayments__oxid.value != 'fatchip_computop_paypal_express'  %}
        {{  parent() }}
    {% endif %}
{% endblock %}
{% block checkout_order_payment_method_button %}
    {% set payment = oView.getPayment() %}
    {% if payment.oxpayments__oxid.value != 'fatchip_computop_paypal_express'  %}
        {{  parent() }}
    {% endif %}
{% endblock %}



