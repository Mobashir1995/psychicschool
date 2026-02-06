<?php
/**
 * VC map configuration for stm_icon_box (WPBakery Icon Box element).
 *
 * @return array vc_map config
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stm_icon_box_get_vc_map() {
	return array(
		'name'     => __( 'Icon Box', 'kadence-child' ),
		'base'     => 'stm_icon_box',
		'icon'     => 'stm_icon_box',
		'category' => __( 'STM', 'kadence-child' ),
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'heading'    => __( 'Title', 'kadence-child' ),
				'param_name' => 'title',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Link', 'kadence-child' ),
				'param_name' => 'link',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Title Holder', 'kadence-child' ),
				'param_name' => 'title_holder',
				'value'      => array( 'H1' => 'h1', 'H2' => 'h2', 'H3' => 'h3', 'H4' => 'h4', 'H5' => 'h5' ),
				'std'        => 'h3',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Hover position', 'kadence-child' ),
				'param_name' => 'hover_pos',
				'value'      => array(
					__( 'None', 'kadence-child' )   => 'none',
					__( 'Top', 'kadence-child' )   => 'top',
					__( 'Right', 'kadence-child' ) => 'right',
					__( 'Left', 'kadence-child' )  => 'left',
					__( 'Bottom', 'kadence-child' ) => 'bottom',
				),
				'std'        => 'none',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Box background color', 'kadence-child' ),
				'param_name'  => 'box_bg_color',
				'description' => __( 'default - green', 'kadence-child' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Box text color', 'kadence-child' ),
				'param_name'  => 'box_text_color',
				'description' => __( 'Default - white', 'kadence-child' ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Box icon color', 'kadence-child' ),
				'param_name' => 'box_icon_bg_color',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Link color style', 'kadence-child' ),
				'param_name' => 'link_color_style',
				'value'      => array( 'Standart' => 'standart', 'Dark' => 'dark' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'kadence-child' ),
				'param_name' => 'icon',
				'value'      => '',
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Icon Size', 'kadence-child' ),
				'param_name' => 'icon_size',
				'value'      => '60',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Align', 'kadence-child' ),
				'param_name' => 'icon_align',
				'value'      => array( 'Center' => 'center', 'Left' => 'left', 'Right' => 'right' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Box Align', 'kadence-child' ),
				'param_name' => 'box_align',
				'value'      => array( 'Center' => 'center', 'Left' => 'left', 'Right' => 'right' ),
				'std'        => 'left',
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Icon Height', 'kadence-child' ),
				'param_name' => 'icon_height',
				'value'      => '65',
				'dependency' => array( 'element' => 'icon_align', 'value' => array( 'center' ) ),
			),
			array(
				'type'       => 'number_field',
				'heading'    => __( 'Icon Width', 'kadence-child' ),
				'param_name' => 'icon_width',
				'value'      => '65',
				'dependency' => array( 'element' => 'icon_align', 'value' => array( 'left', 'right' ) ),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => __( 'Icon Color', 'kadence-child' ),
				'param_name' => 'icon_color',
				'value'      => '#fff',
			),
			array(
				'type'       => 'textarea_html',
				'heading'    => __( 'Text', 'kadence-child' ),
				'param_name' => 'content',
			),
			array(
				'type'       => 'css_editor',
				'heading'    => __( 'Icon Css', 'kadence-child' ),
				'param_name' => 'css_icon',
				'group'      => __( 'Icon Design options', 'kadence-child' ),
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
