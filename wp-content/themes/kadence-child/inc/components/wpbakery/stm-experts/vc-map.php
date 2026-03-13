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
				'type'       => 'textfield',
				'heading'    => __( 'Section Title', 'kadence-child' ),
				'param_name' => 'experts_title',
				'value'      => '',
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Number of Teachers to output', 'kadence-child' ),
				'param_name' => 'teachers_count',
				'default'    => '8',
				'value'      => '8',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Number of Teachers per row', 'kadence-child' ),
				'param_name' => 'expert_slides_per_row',
				'value'      => array(
					'1' => 1,
					'2' => 2,
				),
				'std'        => 2,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Order By', 'kadence-child' ),
				'param_name' => 'orderby',
				'value'      => array(
					__( 'Date', 'kadence-child' )       => 'date',
					__( 'Title', 'kadence-child' )      => 'title',
					__( 'Random', 'kadence-child' )     => 'rand',
					__( 'Menu Order', 'kadence-child' ) => 'menu_order',
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

