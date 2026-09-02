<?php

/**
 * Login Form
 *
 * Uses the shared LinkPVA WooCommerce login and registration form so the
 * shortcode and My Account page keep the same markup and security flow.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

defined('ABSPATH') || exit;

if (function_exists('login_register_form_by_woocommerce')) {
    // The shared renderer handles WooCommerce hooks, notices and nonces.
    echo login_register_form_by_woocommerce(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    return;
}

// Keep My Account usable when LinkPVA Core is inactive.
if (function_exists('WC') && WC()) {
    $woocommerce_login_template = trailingslashit(WC()->plugin_path()) . 'templates/myaccount/form-login.php';

    if (is_readable($woocommerce_login_template)) {
        include $woocommerce_login_template;
    }
}
