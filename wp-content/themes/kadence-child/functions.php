<?php
define('KADENCE_CHILD_VERSION', time());

function kadence_child_enqueue_scripts()
{
    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), array(), KADENCE_CHILD_VERSION);
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/all.min.css', array(), KADENCE_CHILD_VERSION, 'all' );
    wp_enqueue_style( 'fontawesome-shims', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/v4-shims.min.css', array('font-awesome'), KADENCE_CHILD_VERSION, 'all' );

}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_scripts');


function add_sitewide_custom_content()
{
    echo '<h2>We\'re performing scheduled maintenance to improve your experience.</h2>';
    echo '<p>The site will be back online shortly — thank you for your patience.</p>';
}
add_action('ppw_sitewide_above_password_form_container', 'add_sitewide_custom_content');

require_once get_stylesheet_directory() . '/inc/vc-addons/index.php';

// Remove default WooCommerce product link wrapper (we handle links in template)
function kadence_child_remove_product_link() {
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}
add_action( 'woocommerce_before_shop_loop', 'kadence_child_remove_product_link' );