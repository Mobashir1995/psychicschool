<?php
/**
 * STM Product Categories – WPBakery addon bootstrap.
 *
 * Loads: vc-map config, shortcode handler, and render logic.
 * Only registers when WooCommerce is active.
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

if ( ! function_exists( 'vc_map' ) ) {
	return;
}

if ( ! ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) ) ) {
	return;
}

$stm_product_categories_dir = dirname( __FILE__ );

require_once $stm_product_categories_dir . '/vc-map.php';
require_once $stm_product_categories_dir . '/shortcode.php';
require_once $stm_product_categories_dir . '/render.php';

vc_map( stm_product_categories_get_vc_map() );
add_shortcode( 'stm_product_categories', 'kadence_child_stm_product_categories_shortcode' );
