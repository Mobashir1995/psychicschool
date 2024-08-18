<?php
require_once  'inc/enqueue.php';
require_once 'inc/helpers.php';
require_once 'inc/woocommerce-functions.php';
require_once 'inc/hook-helpers.php';


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
 * Check if user has an active affiliate account
 * 
 * @param integer $user_id User ID
 * 
 * @return boolean true | false
 */
function psycics_is_user_affiliate_active( $user_id ) {
    if ( class_exists( 'Affiliate_WP' ) ) {
        $affiliate_id = affwp_get_affiliate_id( $user_id );

        if ($affiliate_id) {
            $status = affwp_get_affiliate_status( $affiliate_id );

            return $status === 'active';
        }
    }
    return false;
}

/**
 * Customization of WooCommerce My Account
 */
require_once 'inc/my-account/functions.php';
require_once 'inc/affiliate-wp/functions.php';