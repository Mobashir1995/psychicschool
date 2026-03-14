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
    remove_action( 'woocommerce_after_shop_loop_item', array( $woocommerce_component, 'archive_action_wrap_start' ), 5 );
	remove_action( 'woocommerce_after_shop_loop_item', array( $woocommerce_component, 'archive_action_wrap_end' ), 20 );
	
	// Remove the component from the components array
	// unset( $theme->components['woocommerce'] );

    /**
     * Remove WooCommerce plugin hooks
     */
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ); // Remove price from product loops
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); // Remove add to cart button from product loops
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 ); // Remove rating from product loops

	/**
	 * Customizations
	 */
	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_start_shop_loop_title_wrap', 6 ); // Start title wrap
	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_end_shop_loop_title_wrap', 49 ); // End title wrap

	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_product_grid_teachers_name', 11 ); // End title wrap

	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_end_shop_loop_meta_wrap', 49 ); // Product Grid meta wrap

	add_action( 'woocommerce_archive_description', 'kadence_child_woo_archive_action_section', 10 ); // Add search and course filter in WooCommerce Archive Page

	add_filter( 'woocommerce_product_loop_start', 'kadence_child_woo_archive_product_loop_start', 4 ); // Add search and course filter in WooCommerce Archive Page
	add_filter( 'woocommerce_product_loop_end', 'kadence_child_woo_archive_product_loop_end', 999 ); // Add search and course filter in WooCommerce Archive Page
	
	add_filter( 'woocommerce_before_shop_loop', 'kadence_child_woo_archive_loop_shop_columns', 999 ); // Add search and course filter in WooCommerce Archive Page
	add_action( 'woocommerce_shop_loop_item_title', 'kadence_child_woo_archive_product_grid_teachers_name', 11 ); // Add teachers name in WooCommerce Archive Page

	add_action( 'woocommerce_after_shop_loop_item_title', 'kadence_child_woo_archive_product_see_more_button', 50 ); // Add see more button in WooCommerce Archive Page
}
// Hook early to remove component and its hooks
add_action( 'after_setup_theme', 'remove_kadence_woocommerce_component', 999 );


function kadence_child_start_shop_loop_title_wrap() {
	if ( is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) {
		return;
	}
	echo '<div class="shop-loop-title-wrap">';
}

function kadence_child_end_shop_loop_title_wrap() {
	if ( is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) {
		return;
	}
	echo '</div>';
}

function kadence_child_product_grid_teachers_name() {
	if ( is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) {
		return;
	}
	$experts = get_post_meta( get_the_ID(), 'course_expert', true );
	?>
		<div class="product-grid-teachers-name">
			<?php if ( ! empty( $experts ) && 'no_expert' !== $experts && ( is_array( $experts ) && ! in_array( 'no_expert', $experts, true ) ) ) : ?>
				<div class="teacher-name">
					<?php echo esc_html( implode( ', ', array_map( 'get_the_title', (array) $experts ) ) ); ?>
				</div>
			<?php else : ?>
				<div class="teacher-name">&nbsp;</div>
			<?php endif; ?>
		</div>
	<?php
}

function kadence_child_end_shop_loop_meta_wrap() {
	if ( is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) {
		return;
	}
	$stock = get_post_meta( get_the_id(), '_stock', true );
	$comments_num = get_comments_number( get_the_id() ) ? get_comments_number( get_the_id() ) : 0;
	?>
		<div class="kadence-shop-loop-meta-wrap">
			<div class="meta-info">
				<div class="comment-count">
					<i class="fa fa-comment"></i><span><?php echo esc_html( $comments_num ); ?></span>
				</div>
				<div class="stock-count">
					<i class="fa fa-user"></i><span><?php echo $stock ? floatval( $stock ) : 0; ?></span>
				</div>	
			</div>
			<div class="meta-rating">
				<?php woocommerce_template_loop_rating(); ?>
			</div>
			<div class="action-button">
				<a href="<?php the_permalink(); ?>" class="btn btn-green-bg"><?php esc_html_e( 'View Details', 'kadence-child' ); ?></a>
			</div>
		</div>
	<?php
}


function kadence_child_woo_archive_action_section() {
	?>
	<div class="woocommerce-archive-action-section">
		<div class="woo-archive-search-form">
			<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
				<input type="text" name="s" placeholder="<?php esc_html_e( 'Search the Courses', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
				<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>" class="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
				<input type="hidden" name="post_type" value="product" />
			</form>
		</div>
		<div class="woo-archive-product-cat-filter">
			<select name="filtered_product_cat" id="filtered_product_cat" data-shop-url="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
				<option value=""><?php esc_html_e( 'All Courses', 'woocommerce' ); ?></option>
				<?php
					$product_categories = get_terms( array(
						'taxonomy' => 'product_cat',
						'hide_empty' => true,
					) );
					foreach ( $product_categories as $product_category ) {
						$term_link = get_term_link( $product_category );
						$term_url = ( ! is_wp_error( $term_link ) ) ? $term_link : '';
						echo '<option value="' . esc_attr( $term_url ) . '">' . esc_html( $product_category->name ) . '</option>';
					}
				?>
			</select>
		</div>
	</div>
	<?php	
}


function kadence_child_woo_archive_product_loop_start() {
	if ( ! (is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) ) {
		return;
	}
?>
	<div class="woocommerce-archive-product-loop-start">
	<?php
}

function kadence_child_woo_archive_product_loop_end() {
	if ( ! (is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) ) {
		return;
	}
?>
	</div>
	<?php
}

function kadence_child_woo_archive_loop_shop_columns( ) {
	if ( is_shop() || is_product_category() || is_product_tag() || is_archive() ) {        
        wc_set_loop_prop( 'columns', 1 );
    }
}

function kadence_child_woo_archive_product_grid_teachers_name() {
	if ( ! (is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) ) {
		return;
	}
	echo '<div class="woo-archive-product-grid-teachers-name">';
	$experts = get_post_meta( get_the_ID(), 'course_expert', true );
	if ( ! empty( $experts ) && 'no_expert' !== $experts && ( is_array( $experts ) && ! in_array( 'no_expert', $experts, true ) ) ) :
		foreach ( $experts as $expert ) :
	?>
		<a href="<?php echo esc_url( get_permalink( $expert ) ); ?>" class="teacher-item">
			<div class="teacher-thumbnail">
				<?php echo get_the_post_thumbnail( $expert, 'img-75-75', false ); ?>
			</div>
			<div class="teacher-info">
				<div class="teacher-name">
					<?php echo esc_html( get_the_title( $expert ) ); ?>
				</div>
				<div class="teacher-role">
					<?php esc_html_e( 'Teacher', 'kadence-child' ); ?>
				</div>
			</div>
		</a>
	<?php
		endforeach;
	endif;
	echo '</div>';
}

function kadence_child_woo_archive_product_see_more_button() {
	if ( ! (is_main_query() && is_archive() && ! wc_get_loop_prop( 'is_shortcode' ) ) ) {
		return;
	}
	?>
	<div class="woo-archive-product-see-more-button">
		<a href="<?php the_permalink(); ?>" class="btn btn-green-bg"><?php esc_html_e( 'See More', 'kadence-child' ); ?></a>
	</div>
	<?php
}

/**
 * Single product: remove tabs and strip unwanted items from the right-column summary.
 * Title is rendered full-width in the template; description/reviews are rendered
 * directly in the left column, so the tab system and those summary hooks are not needed.
 */
function kadence_child_single_product_summary_hooks() {
	// Remove the tabs UI — description and reviews are output directly in the template.
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

	// Remove items from the right-column summary that are placed elsewhere or not shown.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
}
add_action( 'woocommerce_init', 'kadence_child_single_product_summary_hooks' );