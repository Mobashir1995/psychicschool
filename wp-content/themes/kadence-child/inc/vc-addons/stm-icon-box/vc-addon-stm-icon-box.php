<?php
/**
 * STM Icon Box – WPBakery addon bootstrap.
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

$stm_icon_box_dir = dirname( __FILE__ );

require_once $stm_icon_box_dir . '/vc-map.php';
require_once $stm_icon_box_dir . '/shortcode.php';
require_once $stm_icon_box_dir . '/render.php';

vc_map( stm_icon_box_get_vc_map() );
add_shortcode( 'stm_icon_box', 'kadence_child_stm_icon_box_shortcode' );
