<?php
/**
 * VC map configuration for stm_product_categories (WPBakery Product Categories element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_product_categories_get_vc_map() {
	return array(
		'name'     => __( 'Product Categories', 'kadence-child' ),
		'base'     => 'stm_product_categories',
		'icon'     => 'stm_product_categories',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Carousel Auto Scroll', 'kadence-child' ),
				'param_name' => 'auto',
				'value'      => 'true',
			),
			array(
				'type'        => 'number_field',
				'heading'     => __( 'Number of items to output', 'kadence-child' ),
				'param_name'  => 'number',
				'description' => __( 'Leave field empty to display all categories', 'kadence-child' ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Box text Color', 'kadence-child' ),
				'param_name' => 'box_text_color',
				'value'      => '#fff',
				'group'      => __( 'Item Options', 'kadence-child' ),
			),
			array(
				'type'        => 'number_field',
				'heading'     => __( 'Icon size', 'kadence-child' ),
				'param_name'  => 'icon_size',
				'group'       => __( 'Item Options', 'kadence-child' ),
				'value'       => '150',
				'description' => __( 'If category has font icon chosen - size will be applied', 'kadence-child' ),
			),
			array(
				'type'        => 'number_field',
				'heading'     => __( 'Icon height', 'kadence-child' ),
				'param_name'  => 'icon_height',
				'group'       => __( 'Item Options', 'kadence-child' ),
				'description' => __( 'If category has font icon chosen - height will be applied', 'kadence-child' ),
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
