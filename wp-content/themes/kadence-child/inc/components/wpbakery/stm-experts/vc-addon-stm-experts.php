<?php
/**
 * STM Experts – WPBakery addon bootstrap.
 *
 * Registers the vc_map configuration and shortcode and wires in the render logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

if ( ! function_exists( 'vc_map' ) ) {
	return;
}

$stm_experts_dir = dirname( __FILE__ );

require_once $stm_experts_dir . '/vc-map.php';
require_once $stm_experts_dir . '/shortcode.php';
require_once $stm_experts_dir . '/render.php';

vc_map( stm_experts_get_vc_map() );
add_shortcode( 'stm_experts', 'kadence_child_stm_experts_shortcode' );

