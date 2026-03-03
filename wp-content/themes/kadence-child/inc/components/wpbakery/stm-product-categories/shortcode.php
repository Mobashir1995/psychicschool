<?php
/**
 * Shortcode handler for stm_product_categories.
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Shortcode content (unused).
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_product_categories_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_product_categories', $atts );

	$pairs = array(
		'title'          => '',
		'view_type'      => 'stm_vc_product_cat_carousel',
		'number'         => '',
		'per_row'        => 6,
		'box_text_color' => '#fff',
		'text_align'     => 'center',
		'icon_size'      => '60',
		'auto'           => '0',
		'icon_height'    => '69',
		'css'            => '',
	);

	$atts = wp_parse_args( $atts, $pairs );

	$atts['css_class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ) );

	return kadence_child_stm_product_categories_render( $atts );
}
