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
				'type'       => 'css_editor',
				'heading'    => __( 'Css', 'kadence-child' ),
				'param_name' => 'css',
				'group'      => __( 'Design Options', 'kadence-child' ),
			),
		),
	);
}

