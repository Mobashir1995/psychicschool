<?php
define('KADENCE_CHILD_VERSION', time());

function kadence_child_enqueue_scripts()
{
    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), array(), KADENCE_CHILD_VERSION);
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/all.min.css', array(), KADENCE_CHILD_VERSION, 'all' );
    wp_enqueue_style( 'fontawesome-shims', get_stylesheet_directory_uri() . '/vendors/fontawesome/css/v4-shims.min.css', array('font-awesome'), KADENCE_CHILD_VERSION, 'all' );

    // Swiper for product categories carousel.
    wp_enqueue_style(
        'kadence-swiper',
        get_stylesheet_directory_uri() . '/vendors/swiper/swiper-bundle.min.css',
        array(),
        '12.1.2'
    );
    wp_enqueue_script(
        'kadence-swiper',
        get_stylesheet_directory_uri() . '/vendors/swiper/swiper-bundle.min.js',
        array(),
        '12.1.2',
        true
    );

    // Combined theme script (product cat filter, swiper, stats counter).
    $kadence_child_script_deps = array( 'jquery', 'kadence-swiper' );
    if ( ! wp_is_mobile() ) {
        wp_enqueue_script(
            'kadence-countup',
            get_stylesheet_directory_uri() . '/vendors/countUp/countUp.min.js',
            array(),
            KADENCE_CHILD_VERSION,
            true
        );
        $kadence_child_script_deps[] = 'kadence-countup';
    }
    wp_enqueue_script(
        'kadence-child',
        get_stylesheet_directory_uri() . '/assets/js/kadence-child.js',
        $kadence_child_script_deps,
        KADENCE_CHILD_VERSION,
        true
    );

}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_scripts');

/**
 * Disable widgets block editor
 * 
 * @return boolean false
 * @link https://developer.wordpress.org/reference/hooks/use_widgets_block_editor/
 */
add_filter( 'use_widgets_block_editor', '__return_false' );

$kadence_child_components = array(
    'wpbakery',
    'password-protection',
    'woocommerce',
	'customizer',
	// 'footer',
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


add_filter('script_loader_tag', function($tag, $handle) {
    if ('kadence-countup' === $handle) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);