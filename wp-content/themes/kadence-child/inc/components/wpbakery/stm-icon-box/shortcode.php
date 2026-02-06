<?php
/**
 * Shortcode handler for stm_icon_box.
 * Processes attributes and delegates to the render function.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string|null $content Shortcode content.
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_icon_box_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_icon_box', $atts );

	$icon_align  = isset( $atts['icon_align'] ) ? $atts['icon_align'] : 'center';
	$icon_height = isset( $atts['icon_height'] ) ? $atts['icon_height'] : 65;
	$icon_width  = isset( $atts['icon_width'] ) ? $atts['icon_width'] : 65;

	$icon_inline_css = ( 'center' === $icon_align )
		? 'height:' . esc_attr( $icon_height ) . 'px;'
		: 'width:' . esc_attr( $icon_width ) . 'px;';

	$atts['css_class']     = isset( $atts['css'] )
		? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ) )
		: '';
	$atts['css_icon_class'] = isset( $atts['css_icon'] )
		? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css_icon'], ' ' ) )
		: '';
	$atts['icon_inline_css'] = $icon_inline_css;
	$atts['link']           = vc_build_link( isset( $atts['link'] ) ? $atts['link'] : '' );
	$atts['unique']         = function_exists( 'kadence_child_create_unique_id' )
		? kadence_child_create_unique_id( $atts )
		: 'stm_icon_box_' . uniqid();
	$atts['content']        = $content;
	
	return kadence_child_stm_icon_box_render( $atts );
}
