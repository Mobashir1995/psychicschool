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

    // WooCommerce Select2 (selectWoo) for product category filter (shop archive only).
    if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'select2' );   // WC style handle for selectWoo
        wp_enqueue_script( 'selectWoo' ); // WC script handle
    }

    // Combined theme script (product cat filter, swiper, stats counter).
    $kadence_child_script_deps = array( 'jquery', 'kadence-swiper' );
    if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) && class_exists( 'WooCommerce' ) ) {
        $kadence_child_script_deps[] = 'selectWoo';
    }
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

/** Load Login NoCaptcha assets on the front end (plugin only does so on wp-login, account, checkout). */
function kadence_child_enqueue_login_nocaptcha_globally() {
	if ( is_user_logged_in() || is_admin() ) {
		return;
	}
	if ( ! class_exists( 'LoginNocaptcha' ) ) {
		return;
	}
	LoginNocaptcha::register_scripts_css();
	wp_enqueue_script( 'login_nocaptcha_google_api' );
	wp_enqueue_style( 'login_nocaptcha_css' );
}
add_action( 'wp_enqueue_scripts', 'kadence_child_enqueue_login_nocaptcha_globally', 25 );

/**
 * `wp_login_form()` does not fire `login_form`; only `login_form_*` filters. Output the same
 * captcha as the plugin’s `login_form` callback so it appears in Kadence’s modal.
 */
function kadence_child_login_nocaptcha_wp_login_form_middle( $content ) {
	if ( ! class_exists( 'LoginNocaptcha' ) ) {
		return $content;
	}
	ob_start();
	echo '<div class="login-recaptcha-wrap">';
	LoginNocaptcha::nocaptcha_form();
	echo '</div>';
	return $content . ob_get_clean();
}
add_filter( 'login_form_middle', 'kadence_child_login_nocaptcha_wp_login_form_middle', 10, 2 );

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


/**
 * Make blog archive use the same grid/card layout as the STM Post List WPBakery widget.
 */
function kadence_child_archive_container_classes( $classes ) {
	$is_post_archive = is_home() || is_category() || is_tag() || is_author() || is_date();
	if ( $is_post_archive ) {
		$classes[] = 'kadence_post_list_main_section_wrapper';
		$classes[] = 'kadence_post_list_archive';
	}
	return $classes;
}
add_filter( 'kadence_archive_container_classes', 'kadence_child_archive_container_classes', 20 );

add_filter('script_loader_tag', function($tag, $handle) {
    if ('kadence-countup' === $handle) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);