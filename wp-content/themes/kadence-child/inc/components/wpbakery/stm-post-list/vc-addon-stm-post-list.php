<?php
/**
 * STM Post List – WPBakery addon bootstrap.
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

$stm_post_list_dir = dirname( __FILE__ );

require_once $stm_post_list_dir . '/vc-map.php';
require_once $stm_post_list_dir . '/shortcode.php';
require_once $stm_post_list_dir . '/render.php';

vc_map( stm_post_list_get_vc_map() );
add_shortcode( 'stm_post_list', 'kadence_child_stm_post_list_shortcode' );

