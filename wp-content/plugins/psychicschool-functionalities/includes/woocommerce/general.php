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

if ( ! function_exists( 'psychicschool_disable_product_gallery_zoom_and_lightbox' ) ) {
	/**
	 * Disable WooCommerce single product gallery zoom and lightbox.
	 *
	 * This removes both the zoom effect and the gallery search icon trigger.
	 *
	 * @return void
	 */
	function psychicschool_disable_product_gallery_zoom_and_lightbox() {
		remove_theme_support( 'wc-product-gallery-zoom' );
		remove_theme_support( 'wc-product-gallery-lightbox' );
	}

	add_action( 'after_setup_theme', 'psychicschool_disable_product_gallery_zoom_and_lightbox', 100 );
}

if ( ! function_exists( 'psychicschool_remove_product_gallery_image_links' ) ) {
	/**
	 * Remove anchor links from single product gallery images.
	 *
	 * @param string $html Gallery image HTML.
	 * @return string
	 */
	function psychicschool_remove_product_gallery_image_links( $html ) {
		$html = preg_replace( '#<a\b[^>]*>#i', '', $html );
		$html = preg_replace( '#</a>#i', '', $html );

		return $html;
	}

	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'psychicschool_remove_product_gallery_image_links', 10, 1 );
}

