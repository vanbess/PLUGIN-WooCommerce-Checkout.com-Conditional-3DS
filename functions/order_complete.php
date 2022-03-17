<?php

/**
 * Hook to order thank you page to improve error display and handling
 */
add_filter('woocommerce_thankyou_order_received_text', 'sbwc_ccom_fix_order_complete_text', 10, 2);

function sbwc_ccom_fix_order_complete_text($str, $order)
{

    // $order = wc_get_order('1234');
    $payment_method = $order->get_payment_method();

    // Checkout.com payment gateway list
    $ccom_gateway_list = [
        "wc_checkout_com_cards",
        "wc_checkout_com_alternative_payments_alipay",
        "wc_checkout_com_alternative_payments_bancontact",
        "wc_checkout_com_alternative_payments_boleto",
        "wc_checkout_com_alternative_payments_eps",
        "wc_checkout_com_alternative_payments_fawry",
        "wc_checkout_com_alternative_payments_giropay",
        "wc_checkout_com_alternative_payments_ideal",
        "wc_checkout_com_alternative_payments_klarna",
        "wc_checkout_com_alternative_payments_knet",
        "wc_checkout_com_alternative_payments_multibanco",
        "wc_checkout_com_alternative_payments_poli",
        "wc_checkout_com_alternative_payments_qpay",
        "wc_checkout_com_alternative_payments_sepa",
        "wc_checkout_com_alternative_payments_sofort"
    ];

    // if not valid Checkout.com payment method, bail
    if (!in_array($payment_method, $ccom_gateway_list)) :
        return $str;
    endif;

    // retrieve order status
    $status = $order->get_status();

    // processing status
    if ($status === 'processing') :
        return $str;
    endif;

    // pending status
    if($status === 'pending'):
        $str = __('Thank you for your order. Your order is currently pending payment and will be on hold until such a time that we have confirmed payment, at which point it will be processed.', 'woocommerce');
        return $str;
    endif;

    // on hold status
    if ($status === 'on-hold') :
        $str = __('Thank you for your order. Your order will be on hold until such a time that we have confirmed payment, at which point it will be processed.', 'woocommerce');
        return $str;
    endif;
    
    // failed status
    if ($status === 'failed') :
        $str = __('Unfortunately your order failed. This could be due to your payment having failed. Please try using a different payment method.', 'woocommerce');
        return $str;
    endif;
    
    // cancelled status
    if($status === 'cancelled'):
        $str = __('Your order was cancelled. Reason: payment or transaction cancelled.', 'woocommerce');
        return $str;
    endif;
}
