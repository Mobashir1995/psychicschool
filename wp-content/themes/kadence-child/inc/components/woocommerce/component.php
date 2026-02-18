<?php



/**
 * Remove Kadence WooCommerce component programmatically
 * 
 * This removes the component from the theme and cleans up all its hooks
 */
function remove_kadence_woocommerce_component() {
	// Check if Kadence theme classes exist
	if ( ! class_exists( '\Kadence\Theme' ) || ! class_exists( '\Kadence\Woocommerce\Component' ) ) {
		return;
	}

	// Get the theme instance
	$theme = \Kadence\Theme::instance();
	
	// Check if the component exists
	if ( ! isset( $theme->components['woocommerce'] ) ) {
		return;
	}

	$woocommerce_component = $theme->components['woocommerce'];
	
	// Remove all filters and actions added by this component
	// These are the main hooks added in the initialize() method
	// remove_filter( 'kadence_dynamic_css', array( $woocommerce_component, 'dynamic_css' ), 20 );
	remove_action( 'wp_head', array( $woocommerce_component, 'frontend_gfonts' ), 80 );
	// remove_action( 'wp_enqueue_scripts', array( $woocommerce_component, 'action_enqueue_scripts' ) );
	// remove_action( 'wp_enqueue_scripts', array( $woocommerce_component, 'action_enqueue_product_scripts' ), 1 );
	// remove_action( 'after_setup_theme', array( $woocommerce_component, 'action_add_woocommerce_support' ) );
	// remove_action( 'woocommerce_before_main_content', array( $woocommerce_component, 'action_remove_normal_archive_description' ) );
	// remove_action( 'woocommerce_before_single_product', array( $woocommerce_component, 'output_product_above' ), 20 );
	// remove_action( 'woocommerce_before_main_content', array( $woocommerce_component, 'output_product_above_title' ), 5 );
	// remove_filter( 'woocommerce_product_description_heading', array( $woocommerce_component, 'remove_product_tab_heading' ) );
	// remove_filter( 'woocommerce_product_additional_information_heading', array( $woocommerce_component, 'remove_product_tab_heading' ) );
	// remove_action( 'woocommerce_before_main_content', array( $woocommerce_component, 'output_content_wrapper' ) );
	// remove_action( 'woocommerce_after_main_content', array( $woocommerce_component, 'output_content_wrapper_end' ) );
	// remove_filter( 'woocommerce_single_product_image_gallery_classes', array( $woocommerce_component, 'single_product_image_initial_ratio' ), 20 );
	remove_filter( 'woocommerce_show_page_title', '__return_false', 20 );
	// remove_action( 'woocommerce_before_shop_loop', array( $woocommerce_component, 'archive_loop_top' ), 20 );
	// remove_action( 'woocommerce_before_single_product', array( $woocommerce_component, 'single_product_layout' ), 20 );
	// remove_action( 'woocommerce_after_single_product_summary', array( $woocommerce_component, 'single_product_comment_css' ), 5 );
	// remove_filter( 'woocommerce_product_loop_start', array( $woocommerce_component, 'product_loop_start' ), 5 );
	// remove_filter( 'kadence_blocks_carousel_woocommerce_product_loop_start', array( $woocommerce_component, 'product_loop_start' ), 5 );
	// remove_filter( 'post_class', array( $woocommerce_component, 'add_woo_entry_classes' ), 20 );
	// remove_filter( 'product_cat_class', array( $woocommerce_component, 'add_woo_cat_entry_classes' ), 20 );
    // Add to cart wrap.
    // remove_action( 'woocommerce_after_shop_loop_item', array( $woocommerce_component, 'archive_action_wrap_start' ), 5 );
	// remove_action( 'woocommerce_after_shop_loop_item', array( $woocommerce_component, 'archive_action_wrap_end' ), 20 );
	
	// Remove the component from the components array
	// unset( $theme->components['woocommerce'] );

    /**
     * Remove WooCommerce plugin hooks
     */
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ); // Remove price from product loops
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); // Remove add to cart button from product loops

	/**
	 * Customizations
	 */
	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_start_shop_loop_title_wrap', 1 ); // Start title wrap
	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_end_shop_loop_title_wrap', 99 ); // End title wrap
}
// Hook early to remove component and its hooks
add_action( 'after_setup_theme', 'remove_kadence_woocommerce_component', 999 );


function kadence_child_start_shop_loop_title_wrap() {
	echo '<div class="shop-loop-title-wrap">';
}

function kadence_child_end_shop_loop_title_wrap() {
	echo '</div>';
}