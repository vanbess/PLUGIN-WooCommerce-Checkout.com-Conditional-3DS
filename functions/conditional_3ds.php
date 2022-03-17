<?php

/**
 * Conditionally enable 3DS for GBP transactions > 150
 */

add_action('wp_head', 'sbwc_ccom_conditional_3ds');

function sbwc_ccom_conditional_3ds()
{

    // retrieve cart total
    $cart_total = wc()->cart->get_cart_contents_total();

    // retrieve ALG currency code
    $currency = $_SESSION['alg_currency'];

    // set default 3DS status in $_SESSION
    $_SESSION['checkout_com_3ds_enabled'] = 'no';

    // if cart total present
    if ($cart_total && is_checkout()) :

        // retrieve user country code (Cloudflare)
        if ($_SERVER['HTTP_CF_IPCOUNTRY']) :
            $country_code = $_SERVER['HTTP_CF_IPCOUNTRY'];

        // retrieve user country code (no Cloudflare)
        else :
            $ip_address    = $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $location      = new WC_Geolocation();
            $location_data = $location->geolocate_ip($ip_address);
            $country_code  = $location_data['country'];
        endif;

        // if country code GB, currency GBP, $_SESSION 3DS not active and cart total > 150, enable 3DS
        if ($country_code === 'GB' && $cart_total > 150 && $currency === 'GBP' && $_SESSION['checkout_com_3ds_enabled'] === 'no') :

            update_option('ckocom_card_threed', 1);
            $_SESSION['checkout_com_3ds_enabled'] = 'yes';

        // if country code GB and cart total <= 150, disable 3DS
        elseif ($country_code === 'GB' && $cart_total <= 150) :

            update_option('ckocom_card_threed', 0);
            $_SESSION['checkout_com_3ds_enabled'] = 'no';

        // if country code no GB, disable 3DS
        elseif ($country_code !== 'GB') :
            update_option('ckocom_card_threed', 0);
        endif;

    // reset checkout.com 3DS if conditions not met
    else :
        update_option('ckocom_card_threed', 0);
        $_SESSION['checkout_com_3ds_enabled'] = 'no';
    endif;
}
