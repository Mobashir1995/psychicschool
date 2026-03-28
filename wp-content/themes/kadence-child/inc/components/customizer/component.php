<?php
/**
 * Extend Kadence product archive style settings from child theme
 * 
 * This modifies the product_archive_style setting to add more options
 * Hook runs after parent theme loads settings (priority 1) but before registration (priority 5)
 */

use function Kadence\kadence;

function kadence_child_extend_product_archive_settings( $wp_customize ) {
	// Check if Kadence Theme_Customizer class exists
	if ( ! class_exists( '\Kadence\Theme_Customizer' ) ) {
		return;
	}
	
	// Access the static settings property
	// Since it's a public static property, we can access it directly
	$settings = \Kadence\Theme_Customizer::$settings;
	
	// Check if product_archive_style setting exists and has the expected structure
	if ( isset( $settings['product_archive_style'] ) && isset( $settings['product_archive_style']['input_attrs']['layout'] ) ) {
		// Add more layout options to the existing product_archive_style setting
		$settings['product_archive_style']['input_attrs']['layout']['view-more'] = array(
			'tooltip' => __( 'View More Button', 'kadence' ),
			'name' => __( 'View More', 'kadence' ),
		);
		
		// Update the settings array
		\Kadence\Theme_Customizer::$settings = $settings;
	}
	
	// Optionally, add a completely new setting
	// $new_settings = array(
	// 	'product_archive_custom_option' => array(
	// 		'control_type' => 'kadence_switch_control',
	// 		'sanitize'     => 'kadence_sanitize_toggle',
	// 		'section'      => 'woocommerce_product_catalog',
	// 		'priority'     => 8,
	// 		'default'      => false,
	// 		'label'        => esc_html__( 'Custom Product Archive Option', 'kadence' ),
	// 		'transport'    => 'refresh',
	// 	),
	// );
	
	// // Add new settings using the Theme_Customizer method
	// \Kadence\Theme_Customizer::add_settings( $new_settings );
}
// Hook at priority 2 to run after parent theme loads settings (priority 1) but before registration
add_action( 'customize_register', 'kadence_child_extend_product_archive_settings', 2 );

/**
 * Mirror Kadence generate_base_css() heading rules for h1–h6 onto utility classes .h1–.h6
 * (and .wp-block-heading.h1–.h6), using the same options and responsive breakpoints as the parent.
 *
 * @see Kadence\Styles\Component::generate_base_css() wp-content/themes/kadence/inc/components/styles/component.php (~1874+)
 *
 * @param string $css Accumulated dynamic CSS from kadence_dynamic_css.
 * @return string
 */
function kadence_child_heading_utility_classes_dynamic_css( $css ) {
	if ( ! function_exists( 'Kadence\kadence' ) || ! class_exists( '\Kadence\Kadence_CSS' ) ) {
		return $css;
	}

	$kcss = new \Kadence\Kadence_CSS();

	$media_query             = array();
	$media_query['mobile']   = apply_filters( 'kadence_mobile_media_query', '(max-width: 767px)' );
	$media_query['tablet']   = apply_filters( 'kadence_tablet_media_query', '(max-width: 1024px)' );
	$media_query['desktop']  = apply_filters( 'kadence_desktop_media_query', '(min-width: 1025px)' );

	$heading_levels = array(
		'h1' => 'h1_font',
		'h2' => 'h2_font',
		'h3' => 'h3_font',
		'h4' => 'h4_font',
		'h5' => 'h5_font',
		'h6' => 'h6_font',
	);

	$kcss->set_selector( '.h1,.h2,.h3,.h4,.h5,.h6,.wp-block-heading.h1,.wp-block-heading.h2,.wp-block-heading.h3,.wp-block-heading.h4,.wp-block-heading.h5,.wp-block-heading.h6' );
	$kcss->add_property( 'font-family', 'var(--global-heading-font-family)' );

	foreach ( $heading_levels as $class => $option_key ) {
		$font = kadence()->option( $option_key );
		$kcss->set_selector( '.' . $class . ',.wp-block-heading.' . $class );
		$kcss->render_font( $font, $kcss );
	}

	// Large screens: mirror parent tablet/mobile pattern — explicit desktop size/line-height/letter-spacing
	// (Parent outputs these in the base block via render_font(); this @media matches kadence_desktop_media_query
	// so .h1-.h6 keep the same desktop typography as h1-h6 above 1024px.)
	$kcss->start_media_query( $media_query['desktop'] );
	foreach ( $heading_levels as $class => $option_key ) {
		$font = kadence()->option( $option_key );
		$kcss->set_selector( '.' . $class . ',.wp-block-heading.' . $class );
		$kcss->add_property( 'font-size', $kcss->render_font_size( $font, 'desktop' ) );
		$kcss->add_property( 'line-height', $kcss->render_font_height( $font, 'desktop' ) );
		$kcss->add_property( 'letter-spacing', $kcss->render_font_spacing( $font, 'desktop' ) );
		kadence_child_heading_font_text_transform( $font, $kcss );
	}
	$kcss->stop_media_query();

	$kcss->start_media_query( $media_query['tablet'] );
	foreach ( $heading_levels as $class => $option_key ) {
		$font = kadence()->option( $option_key );
		$kcss->set_selector( '.' . $class . ',.wp-block-heading.' . $class );
		$kcss->add_property( 'font-size', $kcss->render_font_size( $font, 'tablet' ) );
		$kcss->add_property( 'line-height', $kcss->render_font_height( $font, 'tablet' ) );
		$kcss->add_property( 'letter-spacing', $kcss->render_font_spacing( $font, 'tablet' ) );
		kadence_child_heading_font_text_transform( $font, $kcss );
	}
	$kcss->stop_media_query();

	$kcss->start_media_query( $media_query['mobile'] );
	foreach ( $heading_levels as $class => $option_key ) {
		$font = kadence()->option( $option_key );
		$kcss->set_selector( '.' . $class . ',.wp-block-heading.' . $class );
		$kcss->add_property( 'font-size', $kcss->render_font_size( $font, 'mobile' ) );
		$kcss->add_property( 'line-height', $kcss->render_font_height( $font, 'mobile' ) );
		$kcss->add_property( 'letter-spacing', $kcss->render_font_spacing( $font, 'mobile' ) );
		kadence_child_heading_font_text_transform( $font, $kcss );
	}
	$kcss->stop_media_query();

	$generated = $kcss->css_output();
	if ( ! empty( $generated ) ) {
		$css .= "\n/* Kadence child: heading utility classes (.h1-.h6) */\n" . $generated;
	}

	return $css;
}

/**
 * Output text-transform from Kadence heading font option (same key as Kadence_CSS::render_font()).
 *
 * @param array               $font Heading typography option array.
 * @param \Kadence\Kadence_CSS $kcss CSS builder instance.
 */
function kadence_child_heading_font_text_transform( $font, \Kadence\Kadence_CSS $kcss ) {
	if ( ! empty( $font['transform'] ) ) {
		$kcss->add_property( 'text-transform', $font['transform'] );
	}
}

add_filter( 'kadence_dynamic_css', 'kadence_child_heading_utility_classes_dynamic_css', 20 );