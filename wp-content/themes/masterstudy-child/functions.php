<?php
require_once  'inc/enqueue.php';
require_once 'inc/helpers.php';
require_once 'inc/woocommerce-functions.php';



add_filter('woocommerce_short_description', 'do_shortcode', 10, 1);		//render shortcodes in woocommerce "short description" field

add_filter( 'the_excerpt', 'do_shortcode'); //Display Shortcode on Post Excerpts.

/**
 * Display Shortcode on WooCommerce Single Product Template Excerpt
 * 
 * @param string $content Excerpt of the Product
 * 
 * @return string $content Excerpt of the Product
 */
function psycics_woocommerce_template_single_excerpt_cb( $content ) {
	$content = do_shortcode( $content );
	return $content;
}
add_filter( 'woocommerce_template_single_excerpt', 'psycics_woocommerce_template_single_excerpt_cb', 999, 1 );

/**
 * Customization of WooCommerce My Account
 */
require_once 'inc/my-account/functions.php';