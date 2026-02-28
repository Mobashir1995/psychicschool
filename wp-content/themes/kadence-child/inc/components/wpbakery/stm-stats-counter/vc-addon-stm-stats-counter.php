<?php
/**
 * STM Stats Counter – WPBakery addon bootstrap.
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

$stm_stats_counter_dir = dirname( __FILE__ );

require_once $stm_stats_counter_dir . '/vc-map.php';
require_once $stm_stats_counter_dir . '/shortcode.php';
require_once $stm_stats_counter_dir . '/render.php';

vc_map( stm_stats_counter_get_vc_map() );
add_shortcode( 'stm_stats_counter', 'kadence_child_stm_stats_counter_shortcode' );

