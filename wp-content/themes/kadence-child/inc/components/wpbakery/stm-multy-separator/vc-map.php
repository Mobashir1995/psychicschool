<?php
/**
 * VC map configuration for stm_multy_separator.
 *
 * Simple two-color horizontal separator.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_multy_separator_get_vc_map() {
	return array(
		'name'     => __( 'STM Multy Separator', 'kadence-child' ),
		'base'     => 'stm_multy_separator',
		'icon'     => 'stm_multy_separator',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Primary Color', 'kadence-child' ),
				'param_name' => 'primary_color',
				'value'      => '#196EC0',
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Secondary Color', 'kadence-child' ),
				'param_name' => 'secondary_color',
				'value'      => '#0AED80',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Height (px)', 'kadence-child' ),
				'param_name' => 'height',
				'value'      => '3',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Width (%)', 'kadence-child' ),
				'param_name' => 'width',
				'value'      => '15',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Alignment', 'kadence-child' ),
				'param_name' => 'align',
				'value'      => array(
					__( 'Center', 'kadence-child' ) => 'center',
					__( 'Left', 'kadence-child' )   => 'left',
					__( 'Right', 'kadence-child' )  => 'right',
				),
				'std'        => 'center',
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

