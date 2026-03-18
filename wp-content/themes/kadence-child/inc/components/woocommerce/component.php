<?php
require_once get_stylesheet_directory() . '/inc/components/woocommerce/product-rating-summary.php';

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
 * Set up global $wp_query->comments so have_comments() works correctly when
 * single-product-reviews.php is rendered directly (without comments_template()).
 * This mirrors what WordPress's comments_template() does internally.
 */
function kadence_child_setup_product_reviews_query() {
	global $wp_query;

	$reviews_query = new WP_Comment_Query(
		array(
			'post_id' => get_the_ID(),
			'status'  => 'approve',
			'type'    => 'review',
		)
	);

	$wp_query->comments      = apply_filters( 'comments_array', $reviews_query->comments, get_the_ID() );
	$GLOBALS['comments']     = &$wp_query->comments;
	$wp_query->comment_count = count( $wp_query->comments );

	update_comment_cache( $wp_query->comments );
	$wp_query->rewind_comments();
}

/**
 * Single product: output the "About Instructor(s)" section.
 * Mirrors MasterStudy's if/else structure:
 *   - is_array( $experts )                               → multiple instructors
 *   - ! empty( $experts ) && 'no_expert' !== $experts   → single instructor
 * Reads the 'course_expert' post meta (post ID or array of post IDs).
 * Cleans up MasterStudy's Bootstrap/clearfix markup: uses class="clear", minimal DOM.
 */
function kadence_child_single_product_about_instructors() {
	$experts = get_post_meta( get_the_ID(), 'course_expert', true );

	if ( empty( $experts ) || 'no_expert' === $experts ) {
		return;
	}

	if ( is_array( $experts ) && in_array( 'no_expert', $experts, true ) ) {
		return;
	}

	$socials      = array( 'facebook', 'linkedin', 'twitter', 'google-plus', 'youtube-play' );
	$is_multiple  = is_array( $experts );
	$experts_list = $is_multiple ? $experts : array( $experts );
	?>

	<div class="single-product-instructors">

		<h3 class="instructors-title">
			<?php echo $is_multiple ? esc_html__( 'About Instructors', 'kadence-child' ) : esc_html__( 'About Instructor', 'kadence-child' ); ?>
		</h3>

		<?php
		$last_expert_id = end( $experts_list );
		foreach ( $experts_list as $expert_id ) :
			$teacher_post = get_post( $expert_id );
			$teacher_job  = get_post_meta( $expert_id, 'expert_sphere', true );
			$expert_image = wp_get_attachment_image_src( get_post_thumbnail_id( $expert_id ), 'img-129-129', false );
			$permalink = get_the_permalink( $expert_id );
		?>
			<div class="instructor-card">
				<div class="instructor-card-top">
					<a class="instructor-thumbnail" href="<?php echo esc_url( $permalink ); ?>">
						<?php if ( ! empty( $expert_image[0] ) ) : ?>
							<img src="<?php echo esc_url( $expert_image[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $expert_id ) ); ?>" />
						<?php endif; ?>
					</a>
					<div class="instructor-info">
						<a class="instructor-name" href="<?php echo esc_url( $permalink ); ?>">
							<?php echo esc_html( get_the_title( $expert_id ) ); ?>
						</a>
						<?php if ( ! empty( $teacher_job ) ) : ?>
							<span class="instructor-job"><?php echo esc_html( $teacher_job ); ?></span>
						<?php endif; ?>

						<div class="instructor-socials">
							<?php foreach ( $socials as $social ) :
								$social_url = get_post_meta( $expert_id, $social, true );
								if ( empty( $social_url ) ) {
									continue;
								}
							?>
								<a href="<?php echo esc_url( $social_url ); ?>" class="instructor-social-<?php echo esc_attr( $social ); ?>">
									<i class="fab fa-<?php echo esc_attr( str_replace( 'youtube-play', 'youtube', $social ) ); ?>"></i>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<?php if ( ! empty( $teacher_post->post_excerpt ) ) : ?>
					<div class="instructor-bio"><?php echo esc_html( $teacher_post->post_excerpt ); ?></div>
				<?php endif; ?>
				
				<?php echo do_shortcode( '[stm_multy_separator]' ); ?>

			</div>
		<?php endforeach; ?>

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

function kadence_child_single_product_sidebar(){
	?>
		<div class="single-product-sidebar">
			<div class="summary entry-summary">
				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 * Title, rating, excerpt, meta and sharing are removed via component.php.
				 *
				 * @hooked woocommerce_template_single_price        - 10
				 * @hooked woocommerce_template_single_add_to_cart  - 30
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>
			</div>
		</div>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'kadence_child_single_product_sidebar', 10 );	

add_filter( 'woocommerce_product_description_heading', '__return_empty_string', 10 );

// Force quantity = 1 and hide the quantity input on single product pages.
add_filter( 'woocommerce_is_sold_individually', '__return_true' );

// Output a "Price" label just before the price in the sidebar summary.
add_action( 'woocommerce_single_product_summary', function() {
	echo '<p class="single-product-price-label">' . esc_html__( 'Price', 'kadence-child' ) . '</p>';
}, 9 );

/**
 * Display course detail meta fields below the add-to-cart form in the sidebar.
 * Meta keys match the "Course Details" meta box used by the MasterStudy theme:
 *   status, duration, lectures, video, certificate.
 * Icons are Font Awesome 6 equivalents of the STM icon font.
 */
function kadence_child_single_product_course_meta() {
	$fields = array(
		'course_status'      => array(
			'label' => __( 'Status', 'kadence-child' ),
			'icon'  => 'fa fa-signal',
		),
		'duration'    => array(
			'label' => __( 'Duration', 'kadence-child' ),
			'icon'  => 'fa fa-clock',        // stm_icon_clock
		),
		'lectures'    => array(
			'label' => __( 'Lectures', 'kadence-child' ),
			'icon'  => 'fa fa-bullhorn',     // stm_icon_bullhorn
		),
		'video'       => array(
			'label' => __( 'Video', 'kadence-child' ),
			'icon'  => 'fa fa-film',         // stm_icon_film-play
		),
		'certificate' => array(
			'label' => __( 'Certificate', 'kadence-child' ),
			'icon'  => 'fa fa-certificate',  // stm_icon_license
		),
	);

	$post_id = get_the_ID();

	ob_start();
	foreach ( $fields as $key => $field ) :
		$meta_value = get_post_meta( $post_id, $key, true );

		if( $key == 'course_status' && $meta_value == 'no_status' ) {
			continue;
		}
		
		if ( empty( $meta_value ) ) {
			continue;
		}
		?>
		<div class="course-meta-item course-meta-<?php echo esc_attr( $key ); ?>">
			<span class="course-meta-icon"><i class="<?php echo esc_attr( $field['icon'] ); ?>"></i></span>
			<span class="course-meta-label"><?php echo esc_html( $field['label'] ); ?>:</span>
			<span class="course-meta-value"><?php echo esc_html( $meta_value ); ?></span>
		</div>
		<?php
	endforeach;
	$inner_html = ob_get_clean();

	if ( empty( $inner_html ) ) {
		return;
	}
	?>
	<div class="single-product-course-meta">
		<?php echo $inner_html; // phpcs:ignore WordPress.Security.EscapeOutput -- already escaped in loop ?>
	</div>
	<?php
}
add_action( 'woocommerce_after_add_to_cart_form', 'kadence_child_single_product_course_meta' );