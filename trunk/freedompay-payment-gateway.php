<?php
/*
Freedompay Payment Gateway is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Freedompay Payment Gateway is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Freedompay Payment Gateway . If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Plugin Name: Freedompay Payment Gateway
 * Text Domain: freedompay-payment-gateway
 * Description: Receive payments using the Freedom Pay payments provider.
 * Author: Freedom Pay
 * Author URI: https://freedompay.money/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.9.4
 * WC tested up to: 5.8.2
 * WC requires at least: 2.6
 *
 * Copyright (c) 2014-2017 WooCommerce
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC Detection
 */
if (!function_exists('is_woocommerce_active')) {
    function is_woocommerce_active()
    {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }
}

/**
 * Initialize the gateway.
 * @since 1.0.0
 */
function freedompay_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    define('WC_GATEWAY_FREEDOMPAY_VERSION', '1.9.4');

    require_once(plugin_basename('freedompay/includes/class-wc-freedompay-payment-gateway.php'));
    load_plugin_textdomain('freedompay-payment-gateway', false, trailingslashit(dirname(plugin_basename(__FILE__))));
    add_filter('woocommerce_payment_gateways', 'freedompay_add_gateway');
}

add_action('plugins_loaded', 'freedompay_init', 0);

function freedompay_plugin_links($links)
{
    $settings_url = add_query_arg(
        array(
            'page'    => 'wc-settings',
            'tab'     => 'checkout',
            'section' => 'wc_freedompay_payment_gateway',
        ),
        admin_url('admin.php')
    );

    $plugin_links = array(
        '<a href="' . esc_url($settings_url) . '">' . __('Settings', 'freedompay-payment-gateway') . '</a>'
    );

    return array_merge($plugin_links, $links);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'freedompay_plugin_links');

/**
 * Add the gateway to WooCommerce
 * @since 1.0.0
 */
function freedompay_add_gateway($methods)
{
    $methods[] = 'FPWC_Freedompay_Payment_Gateway';

    return $methods;
}
