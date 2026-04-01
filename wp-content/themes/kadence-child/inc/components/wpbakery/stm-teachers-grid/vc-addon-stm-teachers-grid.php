<?php
/**
 * STM Teachers Grid – WPBakery addon bootstrap.
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

$stm_teachers_grid_dir = dirname( __FILE__ );

require_once $stm_teachers_grid_dir . '/vc-map.php';
require_once $stm_teachers_grid_dir . '/shortcode.php';
require_once $stm_teachers_grid_dir . '/render.php';

vc_map( stm_teachers_grid_get_vc_map() );
add_shortcode( 'stm_teachers_grid', 'kadence_child_stm_teachers_grid_shortcode' );

