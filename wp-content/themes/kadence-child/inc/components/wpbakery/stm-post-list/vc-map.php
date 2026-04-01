<?php
/**
 * VC map configuration for stm_post_list (WPBakery Post List element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_post_list_get_vc_map() {
	// Mirror MasterStudy $post_list_data (post types) but with Kadence text domain.
	$post_list_data = array(
		'Post'         => 'post',
		'Experts'      => 'teachers',
		'Testimonials' => 'testimonial',
	);

	return array(
		'name'   => __( 'STM Post List', 'kadence-child' ),
		'base'   => 'stm_post_list',
		'icon'   => 'stm_post_list',
		'params' => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Post Data', 'kadence-child' ),
				'param_name'  => 'post_list_data_source',
				'description' => __( 'Choose post type', 'kadence-child' ),
				'value'       => $post_list_data,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Number of items to output', 'kadence-child' ),
				'param_name'  => 'post_list_per_page',
				'description' => __( 'Fill field with number only', 'kadence-child' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Number of items to output per row', 'kadence-child' ),
				'param_name' => 'post_list_per_row',
				'value'      => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'6' => 6,
				),
				'std'        => 3,
				'group'      => __( 'List design', 'kadence-child' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Show post date', 'kadence-child' ),
				'param_name' => 'post_list_show_date',
				'group'      => __( 'Item design', 'kadence-child' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Show post categories', 'kadence-child' ),
				'param_name' => 'post_list_show_cats',
				'group'      => __( 'Item design', 'kadence-child' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Show post tags', 'kadence-child' ),
				'param_name' => 'post_list_show_tags',
				'group'      => __( 'Item design', 'kadence-child' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Show comments count', 'kadence-child' ),
				'param_name' => 'post_list_show_comments',
				'group'      => __( 'Item design', 'kadence-child' ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Custom color', 'kadence-child' ),
				'param_name' => 'custom_color',
				'group'      => __( 'Item design', 'kadence-child' ),
			),
			array(
				'type'       => 'css_editor',
				'heading'    => __( 'Css', 'kadence-child' ),
				'param_name' => 'css',
				'group'      => __( 'Design options', 'kadence-child' ),
			),
		),
	);
}

