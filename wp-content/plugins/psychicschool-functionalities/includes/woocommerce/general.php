<?php

/**
 * General WooCommerce-related customizations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Render shortcodes in WooCommerce short description.
add_filter( 'woocommerce_short_description', 'do_shortcode', 10, 1 );

// Render shortcodes in post excerpts.
add_filter( 'the_excerpt', 'do_shortcode' );

if ( ! function_exists( 'psycics_woocommerce_template_single_excerpt_cb' ) ) {
	/**
	 * Display shortcode output in WooCommerce single product excerpt.
	 *
	 * @param string $content Excerpt content.
	 * @return string
	 */
	function psycics_woocommerce_template_single_excerpt_cb( $content ) {
		return do_shortcode( $content );
	}

	add_filter( 'woocommerce_template_single_excerpt', 'psycics_woocommerce_template_single_excerpt_cb', 999, 1 );
}

if ( ! function_exists( 'woo_custom_cart_button_text_1' ) ) {
	/**
	 * Customize the add to cart button text on single product pages.
	 *
	 * @return string
	 */
	function woo_custom_cart_button_text_1() {
		return __( 'Register Now', 'stm_domain' );
	}

	add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text_1' );
}

if ( ! function_exists( 'psychicschool_variation_dropdown_label' ) ) {
	/**
	 * Override variation attribute label shown above the dropdown.
	 *
	 * @param string     $label   Attribute label.
	 * @param string     $name    Attribute name.
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	function psychicschool_variation_dropdown_label( $label, $name, $product ) {
		return __( 'Registration & Payment Option', 'psychicschool-functionalities' );
	}

	add_filter( 'woocommerce_attribute_label', 'psychicschool_variation_dropdown_label', 10, 3 );
}

