<?php

/**
 * Plugin Name: SBWC Checkout.com Conditional 3DS & Improved Error Handling
 * Description: Allows conditionally enabling of 3DS for Checkout.com credit card transactions and adds additional error handling for failed transactions on WC Order Complete page
 * Author: WC Bessinger
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) :
    exit();
endif;

// plugin path constant
if (!defined('CCOM_3DS_PATH')) :
    define('CCOM_3DS_PATH', plugin_dir_path(__FILE__));
endif;

// plugin uri constant
if (!defined('CCOM_3DS_URI')) :
    define('CCOM_3DS_URI', plugin_dir_url(__FILE__));
endif;

// plugin init
add_action('plugins_loaded', 'sbwc_ccom_3ds_init');

function sbwc_ccom_3ds_init()
{
    include CCOM_3DS_PATH . 'functions/conditional_3ds.php';
    include CCOM_3DS_PATH . 'functions/order_complete.php';
}
