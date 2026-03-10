<?php
/**
 * Shortcode handler for stm_experts.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_experts_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_experts', $atts );

	$defaults = array(
		'per_page'   => '8',
		'image_size' => '',
		'pagination' => 'hide',
		'orderby'    => 'date',
		'order'      => 'DESC',
		'css'        => '',
	);

	$atts = wp_parse_args( $atts, $defaults );

	return kadence_child_stm_experts_render( $atts );
}

