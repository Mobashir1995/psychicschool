<?php
/**
 * VC map configuration for stm_stats_counter (WPBakery Stats Counter element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_stats_counter_get_vc_map() {
	return array(
		'name'     => __( 'Stats Counter', 'kadence-child' ),
		'base'     => 'stm_stats_counter',
		'icon'     => 'stm_stats_counter',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'heading'    => __( 'Title', 'kadence-child' ),
				'param_name' => 'title',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Counter Value', 'kadence-child' ),
				'description' => __( 'Number Only', 'kadence-child' ),
				'param_name'  => 'counter_value',
				'value'       => '1000',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Duration', 'kadence-child' ),
				'param_name' => 'duration',
				'value'      => '2.5',
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'kadence-child' ),
				'param_name' => 'icon',
				'value'      => '',
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Icon Size', 'kadence-child' ),
				'param_name'  => 'icon_size',
				'value'       => '65',
				'description' => __( 'Enter icon size in px', 'kadence-child' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Icon Width', 'kadence-child' ),
				'param_name'  => 'icon_width',
				'value'       => '',
				'description' => __( 'Enter icon width in px', 'kadence-child' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Icon Height', 'kadence-child' ),
				'param_name'  => 'icon_height',
				'value'       => '90',
				'description' => __( 'Enter icon height in px', 'kadence-child' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Text alignment', 'kadence-child' ),
				'param_name'  => 'icon_text_alignment',
				'value'       => array(
					'Center' => 'center',
					'Left'   => 'left',
					'Right'  => 'right',
				),
				'description' => __( 'Text alignment in block', 'kadence-child' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Text color', 'kadence-child' ),
				'param_name'  => 'icon_text_color',
				'description' => __( 'Text color (white - default)', 'kadence-child' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Icon background color', 'kadence-child' ),
				'param_name'  => 'icon_background_color',
				'description' => __( 'Icon Background Color', 'kadence-child' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Text font size (px)', 'kadence-child' ),
				'param_name' => 'text_font_size',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Counter text color', 'kadence-child' ),
				'param_name'  => 'counter_text_color',
				'description' => __( 'Counter Text color (yellow - default)', 'kadence-child' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Counter text font size (px)', 'kadence-child' ),
				'param_name' => 'counter_text_font_size',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Include Border', 'kadence-child' ),
				'param_name' => 'border',
				'value'      => array(
					'None'  => 'none',
					'Right' => 'right',
				),
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

