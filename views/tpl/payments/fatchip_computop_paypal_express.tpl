[{block name="checkout_order_billing_address_button"}]
    [{assign var="payment" value=$oView->getPayment()}]
    [{if $payment->oxpayments__oxid->value != 'fatchip_computop_paypal_express'}]
    [{$smarty.block.parent}]
    [{/if}]
[{/block}]

[{block name="checkout_order_shipping_address_button"}]
    [{assign var="payment" value=$oView->getPayment()}]
    [{if $payment->oxpayments__oxid->value != 'fatchip_computop_paypal_express'}]
    [{$smarty.block.parent}]
    [{/if}]
    [{/block}]

[{block name="checkout_order_shipping_carrier_button"}]
    [{assign var="payment" value=$oView->getPayment()}]
    [{if $payment->oxpayments__oxid->value != 'fatchip_computop_paypal_express'}]
    [{$smarty.block.parent}]
    [{/if}]
[{/block}]

[{block name="checkout_order_payment_method_button"}]
    [{assign var="payment" value=$oView->getPayment()}]
    [{if $payment->oxpayments__oxid->value != 'fatchip_computop_paypal_express'}]
    [{$smarty.block.parent}]
    [{/if}]
[{/block}]