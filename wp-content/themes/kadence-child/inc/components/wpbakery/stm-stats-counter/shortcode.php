<?php
/**
 * Shortcode handler for stm_stats_counter.
 * Processes attributes and delegates to the render function.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_stats_counter_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_stats_counter', $atts );

	// Normalize and defaults to match MasterStudy behavior.
	$atts['counter_value']         = isset( $atts['counter_value'] ) ? $atts['counter_value'] : '1000';
	$atts['duration']              = isset( $atts['duration'] ) ? $atts['duration'] : '2.5';
	$atts['icon_size']             = isset( $atts['icon_size'] ) ? $atts['icon_size'] : '65';
	$atts['icon_height']           = isset( $atts['icon_height'] ) ? $atts['icon_height'] : '90';
	$atts['icon_width']            = isset( $atts['icon_width'] ) ? $atts['icon_width'] : '';
	$atts['icon_text_alignment']   = isset( $atts['icon_text_alignment'] ) ? $atts['icon_text_alignment'] : 'center';
	$atts['icon_text_color']       = isset( $atts['icon_text_color'] ) ? $atts['icon_text_color'] : '#fff';
	$atts['counter_text_color']    = isset( $atts['counter_text_color'] ) ? $atts['counter_text_color'] : '#eab830';
	$atts['icon_background_color'] = isset( $atts['icon_background_color'] ) ? $atts['icon_background_color'] : '';
	$atts['text_font_size']        = isset( $atts['text_font_size'] ) ? $atts['text_font_size'] : '';
	$atts['counter_text_font_size']= isset( $atts['counter_text_font_size'] ) ? $atts['counter_text_font_size'] : '';
	$atts['border']                = isset( $atts['border'] ) ? $atts['border'] : '';

	$css       = isset( $atts['css'] ) ? $atts['css'] : '';
	$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
	if ( empty( $css_class ) ) {
		$css_class = 'kadence_stats_counter_';
	}

	$unique_id    = 'kadence_stats_counter_' . substr( md5( wp_json_encode( $atts ) . microtime( true ) ), 0, 10 );
	$atts['id']   = $unique_id;
	$atts['css_class'] = $css_class;

	return kadence_child_stm_stats_counter_render( $atts );
}

