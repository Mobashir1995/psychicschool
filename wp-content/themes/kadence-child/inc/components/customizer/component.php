<?php
/**
 * Extend Kadence product archive style settings from child theme
 * 
 * This modifies the product_archive_style setting to add more options
 * Hook runs after parent theme loads settings (priority 1) but before registration (priority 5)
 */
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
		$settings['product_archive_style']['input_attrs']['layout']['action-fade'] = array(
			'tooltip' => __( 'Fade in on hover', 'kadence' ),
			'name' => __( 'Fade In', 'kadence' ),
		);
		$settings['product_archive_style']['input_attrs']['layout']['action-slide-down'] = array(
			'tooltip' => __( 'Slide down from top', 'kadence' ),
			'name' => __( 'Slide Down', 'kadence' ),
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