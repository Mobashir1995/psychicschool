<?php
/**
 * Shortcode handler for stm_post_list.
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

function kadence_child_stm_post_list_shortcode( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'stm_post_list', $atts );

	$pairs = array(
		'name'                  => '',
		'image'                 => '',
		'post_list_data_source' => 'post',
		'post_list_per_page'    => '3',
		'post_list_per_row'     => '3',
		'post_list_show_date'   => '',
		'post_list_show_cats'   => '',
		'post_list_show_tags'   => '',
		'post_list_show_comments' => '',
		'custom_color'          => '',
	);

	$atts = wp_parse_args( $atts, $pairs );

	return kadence_child_stm_post_list_render( $atts );
}

