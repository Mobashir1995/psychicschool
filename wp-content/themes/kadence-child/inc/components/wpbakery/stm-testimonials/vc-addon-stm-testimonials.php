<?php
/**
 * STM Testimonials – WPBakery addon bootstrap.
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

$stm_testimonials_dir = dirname( __FILE__ );

require_once $stm_testimonials_dir . '/vc-map.php';
require_once $stm_testimonials_dir . '/shortcode.php';
require_once $stm_testimonials_dir . '/render.php';

vc_map( stm_testimonials_get_vc_map() );
add_shortcode( 'stm_testimonials', 'kadence_child_stm_testimonials_shortcode' );

