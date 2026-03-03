<?php
/**
 * VC map configuration for stm_testimonials (WPBakery Testimonials element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_testimonials_get_vc_map() {
	return array(
		'name'   => __( 'STM Testimonials', 'kadence-child' ),
		'base'   => 'stm_testimonials',
		'icon'   => 'stm_testimonials',
		'params' => array(
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'heading'     => __( 'Section title', 'kadence-child' ),
				'param_name'  => 'testimonials_title',
				'description' => __( 'Title will be shown on the top of section', 'kadence-child' ),
			),
			array(
				'type'        => 'number_field',
				'heading'     => __( 'Number of testimonials to output', 'kadence-child' ),
				'param_name'  => 'testimonials_max_num',
				'description' => __( 'Fill field with number only', 'kadence-child' ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Text Color', 'kadence-child' ),
				'param_name' => 'testimonials_text_color',
				'value'      => '#aaaaaa',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Style', 'kadence-child' ),
				'param_name' => 'style',
				'std'        => 'style_1',
				'value'      => array(
					'Style 1' => 'style_1',
					'Style 2' => 'style_2',
					'Style 3' => 'style_3',
					'Style 4' => 'style_4',
					'Style 5' => 'style_5',
					'Style 6' => 'style_6',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Number of testimonials per row', 'kadence-child' ),
				'param_name' => 'testimonials_slides_per_row',
				'std'        => 2,
				'value'      => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
				),
			),
		),
	);
}

