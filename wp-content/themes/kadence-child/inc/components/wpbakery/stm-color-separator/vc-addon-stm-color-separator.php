<?php
/**
 * STM Color Separator – WPBakery addon bootstrap.
 *
 * Loads: vc-map config, shortcode handler, and render logic.
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

if ( ! function_exists( 'vc_map' ) ) {
	return;
}

$stm_color_separator_dir = dirname( __FILE__ );

require_once $stm_color_separator_dir . '/vc-map.php';
require_once $stm_color_separator_dir . '/shortcode.php';
require_once $stm_color_separator_dir . '/render.php';

vc_map( stm_color_separator_get_vc_map() );
add_shortcode( 'stm_color_separator', 'kadence_child_stm_color_separator_shortcode' );

