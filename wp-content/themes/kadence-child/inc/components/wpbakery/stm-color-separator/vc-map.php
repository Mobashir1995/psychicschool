<?php
/**
 * VC map configuration for stm_color_separator (WPBakery Colored Separator element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_color_separator_get_vc_map() {
	return array(
		'name'     => __( 'Colored Separator', 'kadence-child' ),
		'base'     => 'stm_color_separator',
		'icon'     => 'stm_color_separator',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Separator Color', 'kadence-child' ),
				'param_name' => 'color',
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

