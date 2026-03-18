<?php
/**
 * Single product content: two-column layout.
 * Left  (main):    sale flash, gallery, description, reviews.
 * Right (sidebar): price, add to cart.
 * Title sits full-width above the columns.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

$experts = get_post_meta( get_the_ID(), 'course_expert', true );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
	<div class="single-product-layout">
		<div class="single-product-title">
			<?php wc_get_template( 'single-product/title.php' ); ?>
		</div>
		<div class="single-product-after-title">
			<?php if ( ! empty( $experts ) && 'no_expert' != $experts && ( is_array( $experts ) && ! in_array( 'no_expert', $experts ) ) ) : ?>
				<div class="single-product-meta-item single-product-teachers-info">
					<div class="single-product-meta-icon">
						<i class="fa fa-person-chalkboard"></i>
					</div>
					<div class="single-product-meta-content">
					<?php if ( is_array( $experts ) ) { ?>
						<h3><?php esc_html_e( 'Teachers', 'kadence-child' ); ?></h3>
						<?php foreach ( $experts as $expert ) { ?>
							<a href="<?php echo esc_url( get_permalink( $expert ) ); ?>">
								<?php echo esc_attr( get_the_title( $expert ) ); ?>
							</a>
						<?php } ?>
					<?php } else { ?>
						<h3><?php esc_html_e( 'Teacher', 'kadence-child' ); ?></h3>
						<a href="<?php echo esc_url( get_permalink( $experts ) ); ?>">
							<?php echo esc_attr( get_the_title( $experts ) ); ?>
						</a>
					<?php } ?>
					</div>
				</div>
			<?php endif; ?>

			<?php
				$product_cats = get_the_terms( get_the_ID(), 'product_cat' );
				if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) :
			?>
				<div class="single-product-meta-item single-product-categories-info">
					<div class="single-product-meta-icon">
						<i class="fa fa-bookmark"></i>
					</div>
					<div class="single-product-meta-content">
						<h3><?php esc_html_e( 'Category', 'kadence-child' ); ?></h3>
					<?php $last_cat = count( $product_cats ) > 1 ? end( $product_cats ) : null; ?>
					<?php foreach ( $product_cats as $product_cat ) : ?>
						<a href="<?php echo esc_url( get_term_link( $product_cat ) ); ?>">
							<?php echo esc_html( $product_cat->name ); ?>
						</a>
						<?php if ( $last_cat && $product_cat !== $last_cat ) : ?><span>/</span><?php endif; ?>
					<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php
			$rating_count = $product->get_rating_count();
			$average      = $product->get_average_rating();
			if ( $rating_count > 0 ) :
		?>
			<div class="single-product-meta-item single-product-rating-info">
				<div class="single-product-meta-content">
					<?php woocommerce_template_single_rating(); ?>
				</div>
			</div>
		<?php endif; ?>

		</div>

		<div class="single-product-main">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images   - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>

			<div class="single-product-description">
				<?php woocommerce_product_description_tab(); ?>
			</div>

			<?php echo do_shortcode( '[stm_multy_separator]' ); ?>

			<?php kadence_child_single_product_about_instructors(); ?>

		<div class="single-product-reviews">
			<?php
			kadence_child_setup_product_reviews_query();
			/**
			 * Hook: kadence_child_before_product_reviews.
			 *
			 * @hooked kadence_child_product_rating_summary - 10
			 */
			do_action( 'kadence_child_before_product_reviews' );
			wc_get_template( 'single-product-reviews.php' );
			?>
		</div>
		</div>
	</div>
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 * woocommerce_output_product_data_tabs removed via component.php.
	 *
	 * @hooked woocommerce_upsell_display          - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
