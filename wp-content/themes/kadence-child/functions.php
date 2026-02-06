<?php
define('KADENCE_CHILD_VERSION', time());

function kadence_child_enqueue_scripts()
{
    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), array(), KADENCE_CHILD_VERSION);
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/all.min.css', array(), KADENCE_CHILD_VERSION, 'all' );
    wp_enqueue_style( 'fontawesome-shims', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/v4-shims.min.css', array('font-awesome'), KADENCE_CHILD_VERSION, 'all' );

}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_scripts');

require_once get_stylesheet_directory() . '/inc/vc-addons/index.php';

$kadence_child_components = array(
    'password-protection',
    'woocommerce',
);
foreach ($kadence_child_components as $component) {
    require_once get_stylesheet_directory() . '/inc/components/' . $component . '/component.php';
}