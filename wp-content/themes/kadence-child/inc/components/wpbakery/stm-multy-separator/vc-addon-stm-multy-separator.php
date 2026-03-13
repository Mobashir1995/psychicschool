<?php
/**
 * STM Multy Separator – WPBakery addon bootstrap.
 *
 * Registers vc_map configuration and shortcode, wires in render logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

if ( ! function_exists( 'vc_map' ) ) {
	return;
}

$stm_multy_separator_dir = dirname( __FILE__ );

require_once $stm_multy_separator_dir . '/vc-map.php';
require_once $stm_multy_separator_dir . '/shortcode.php';
require_once $stm_multy_separator_dir . '/render.php';

vc_map( stm_multy_separator_get_vc_map() );
add_shortcode( 'stm_multy_separator', 'kadence_child_stm_multy_separator_shortcode' );

