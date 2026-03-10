<?php
/**
 * VC map configuration for stm_teachers_grid (WPBakery Teachers Grid element).
 *
 * Mirrors the MasterStudy params: per_page, image_size, pagination, css.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_teachers_grid_get_vc_map() {
	return array(
		'name'     => __( 'Teachers Grid', 'kadence-child' ),
		'base'     => 'stm_teachers_grid',
		'icon'     => 'stm_teachers_grid',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Teachers per page', 'kadence-child' ),
				'param_name' => 'per_page',
				// 'default'    => '8',
				'value'      => '8',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Image Size', 'kadence-child' ),
				'param_name'  => 'image_size',
				'description' => __( 'Enter image size (e.g. "thumbnail", "medium", "large", "full" or 200x200). Leave empty to use "medium" size.', 'kadence-child' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Show Pagination', 'kadence-child' ),
				'param_name' => 'pagination',
				'value'      => array(
					__( 'Show', 'kadence-child' ) => 'show',
					__( 'Hide', 'kadence-child' ) => 'hide',
				),
				'std'        => 'show',
			),
			array(
				'type'       => 'css_editor',
				'heading'    => __( 'Css', 'kadence-child' ),
				'param_name' => 'css',
				'group'      => __( 'Design Options', 'kadence-child' ),
			),
		),
	);
}

