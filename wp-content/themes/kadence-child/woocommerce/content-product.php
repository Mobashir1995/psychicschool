<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Get custom meta
$experts = get_post_meta( get_the_id(), 'course_expert', true );
$stock = get_post_meta( get_the_id(), '_stock', true );
$comments_num = get_comments_number( get_the_id() );
$rating = $product->get_average_rating();

// Extra post classes for grid
$classes = array( 'kadence-course-card' );
?>

<li <?php post_class( $classes ); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>

	<div class="kadence-course-card-inner">
		<!-- Person icon with green halo in top left -->
		<div class="course-card-icon">
			<i class="fas fa-user-circle"></i>
		</div>

		<!-- Course Image -->
		<div class="course-card-image">
			<?php if ( has_post_thumbnail() ): ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-responsive' ) ); ?>
				</a>
			<?php else: ?>
				<div class="no-image-placeholder"></div>
			<?php endif; ?>
		</div>

		<!-- Course Content -->
		<div class="course-card-content">
			<!-- Course Title -->
			<h3 class="course-card-title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>

			<!-- Instructors -->
			<div class="course-card-instructors">
				<?php if ( ! empty( $experts ) && $experts != 'no_expert' && ( ! is_array( $experts ) || ! in_array( "no_expert", $experts ) ) ): ?>
					<?php if ( is_array( $experts ) ): ?>
						<?php 
						$expert_names = array();
						foreach ( $experts as $expert ) {
							$expert_names[] = get_the_title( $expert );
						}
						echo esc_html( implode( ', ', $expert_names ) );
						?>
					<?php else: ?>
						<?php echo esc_html( get_the_title( $experts ) ); ?>
					<?php endif; ?>
				<?php else: ?>
					&nbsp;
				<?php endif; ?>
			</div>

			<!-- Engagement Metrics -->
			<div class="course-card-metrics">
				<!-- Comments -->
				<div class="metric-item metric-comments">
					<i class="fas fa-comment"></i>
					<span><?php echo esc_html( $comments_num ); ?></span>
				</div>

				<!-- Participants -->
				<div class="metric-item metric-participants">
					<i class="fas fa-user"></i>
					<span><?php echo esc_html( ! empty( $stock ) ? floatval( $stock ) : 0 ); ?></span>
				</div>

				<!-- Star Rating -->
				<div class="metric-item metric-rating">
					<?php if ( $rating > 0 ): ?>
						<?php 
						$full_stars = floor( $rating );
						$half_star = ( $rating - $full_stars ) >= 0.5;
						$empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );
						?>
						<div class="star-rating">
							<?php for ( $i = 0; $i < $full_stars; $i++ ): ?>
								<i class="fas fa-star"></i>
							<?php endfor; ?>
							<?php if ( $half_star ): ?>
								<i class="fas fa-star-half-alt"></i>
							<?php endif; ?>
							<?php for ( $i = 0; $i < $empty_stars; $i++ ): ?>
								<i class="far fa-star"></i>
							<?php endfor; ?>
						</div>
					<?php else: ?>
						<div class="star-rating">
							<?php for ( $i = 0; $i < 5; $i++ ): ?>
								<i class="far fa-star"></i>
							<?php endfor; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php
	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
