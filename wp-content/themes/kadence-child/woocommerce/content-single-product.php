<?php
/**
 * Single product content: structure only. All content is hooked (see kadence_child_single_product_* actions).
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php do_action( 'kadence_child_single_product_header' ); ?>

	<div class="single-product-layout wp-block-columns alignwide">
		<div class="single-product-main wp-block-column" style="flex-basis:75%">
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
			<div class="single-product-content">
				<?php do_action( 'kadence_child_single_product_description' ); ?>
			</div>
		</div>
		<div class="single-product-sidebar wp-block-column" style="flex-basis:25%">
			<div class="summary entry-summary">
				<?php do_action( 'woocommerce_single_product_summary' ); ?>
			</div>
		</div>
	</div>

	<div class="single-product-reviews">
		<?php do_action( 'kadence_child_single_product_reviews' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
