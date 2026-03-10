<?php
/**
 * VC map configuration for stm_experts (WPBakery Experts grid element).
 *
 * Best-effort clone of MasterStudy STM Experts: grid of teachers/experts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_experts_get_vc_map() {
	return array(
		'name'     => __( 'STM Experts', 'kadence-child' ),
		'base'     => 'stm_experts',
		'icon'     => 'stm_experts',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Experts per page', 'kadence-child' ),
				'param_name' => 'per_page',
				'default'    => '8',
				'value'      => '8',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Image Size', 'kadence-child' ),
				'param_name'  => 'image_size',
				'description' => __( 'Enter image size (e.g. \"thumbnail\", \"medium\", \"large\", \"full\" or 200x200). Leave empty to use \"medium\" size.', 'kadence-child' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Show Pagination', 'kadence-child' ),
				'param_name' => 'pagination',
				'value'      => array(
					__( 'Show', 'kadence-child' ) => 'show',
					__( 'Hide', 'kadence-child' ) => 'hide',
				),
				'std'        => 'hide',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Order By', 'kadence-child' ),
				'param_name' => 'orderby',
				'value'      => array(
					__( 'Date', 'kadence-child' )          => 'date',
					__( 'Title', 'kadence-child' )         => 'title',
					__( 'Random', 'kadence-child' )        => 'rand',
					__( 'Menu Order', 'kadence-child' )    => 'menu_order',
				),
				'std'        => 'date',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Order', 'kadence-child' ),
				'param_name' => 'order',
				'value'      => array(
					__( 'Descending', 'kadence-child' ) => 'DESC',
					__( 'Ascending', 'kadence-child' )  => 'ASC',
				),
				'std'        => 'DESC',
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

