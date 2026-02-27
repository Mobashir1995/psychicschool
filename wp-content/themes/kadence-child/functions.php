<?php
define('KADENCE_CHILD_VERSION', time());

function kadence_child_enqueue_scripts()
{
    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), array(), KADENCE_CHILD_VERSION);
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/all.min.css', array(), KADENCE_CHILD_VERSION, 'all' );
    wp_enqueue_style( 'fontawesome-shims', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/v4-shims.min.css', array('font-awesome'), KADENCE_CHILD_VERSION, 'all' );

}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_scripts');

$kadence_child_components = array(
    'wpbakery',
    'password-protection',
    'woocommerce',
	'customizer',
);
foreach ($kadence_child_components as $component) {
    require_once get_stylesheet_directory() . '/inc/components/' . $component . '/component.php';
}

/**
 * Override Kadence theme default options
 * 
 * This filter allows you to change default values for Kadence customizer settings
 */
function kadence_child_override_default_options( $defaults ) {
	// Set site background color to #ffffff (white)
	// You can change this to any hex color, e.g., '#ff0000' for red
	$defaults['site_background'] = array(
		'desktop' => array(
			'color' => '#ffffff', // Change to your desired color
		),
	);
	
	// Set product archive mobile columns (if you want to change this too)
	// $defaults['product_archive_mobile_columns'] = 'twocolumn';
	
	return $defaults;
}
// add_filter( 'kadence_theme_options_defaults', 'kadence_child_override_default_options', 20 );
