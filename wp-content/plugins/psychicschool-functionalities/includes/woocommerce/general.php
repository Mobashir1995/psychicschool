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

	// add_filter( 'woocommerce_attribute_label', 'psychicschool_variation_dropdown_label', 10, 3 );
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

if ( ! function_exists( 'psycics_gcal_sync_only_confirmed' ) ) {
	/**
	 * Remove 'paid' from the list of statuses that trigger Google Calendar sync.
	 * This prevents double-entries when 'wc-booking-confirmed-after-paid' flips a booking from paid to confirmed.
	 *
	 * @param array $statuses Array of statuses.
	 * @return array
	 */
	function psycics_gcal_sync_only_confirmed( $statuses ) {
		return array_diff( $statuses, array( 'paid' ) );
	}

	add_filter( 'woocommerce_booking_is_paid_statuses', 'psycics_gcal_sync_only_confirmed', 99, 1 );
}

if ( ! function_exists( 'psycics_silently_remove_completed_bookings_from_cart' ) ) {
	/**
	 * Silently remove confirmed or complete bookings from the cart session
	 * before WooCommerce Bookings checks them, preventing false "inactivity" notices.
	 *
	 * @param WC_Cart $cart
	 */
	function psycics_silently_remove_completed_bookings_from_cart( $cart ) {
		// Only run if woocommerce-bookings is active and get_wc_booking exists.
		if ( ! function_exists( 'get_wc_booking' ) ) {
			return;
		}
		
		foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
			if ( isset( $cart_item['booking'] ) && isset( $cart_item['booking']['_booking_id'] ) ) {
				$booking_id = $cart_item['booking']['_booking_id'];
				$booking    = get_wc_booking( $booking_id );

				if ( $booking && $booking->has_status( array( 'confirmed', 'complete', 'paid' ) ) ) {
					unset( $cart->cart_contents[ $cart_item_key ] );
				}
			}
		}
	}
	
	// Hook before WC_Booking_Cart_Manager (which uses priority 10).
	add_action( 'woocommerce_cart_loaded_from_session', 'psycics_silently_remove_completed_bookings_from_cart', 5, 1 );
}

/**
 * Add brand to WooCommerce Product Schema to resolve Merchant Listings warnings.
 *
 * @param array      $markup  Schema markup array.
 * @param WC_Product $product Product object.
 * @return array
 */
function psychicschool_add_brand_to_schema( $markup, $product ) {
	if ( empty( $markup['brand'] ) ) {
		$markup['brand'] = array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' )
		);
	}
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'psychicschool_add_brand_to_schema', 10, 2 );
