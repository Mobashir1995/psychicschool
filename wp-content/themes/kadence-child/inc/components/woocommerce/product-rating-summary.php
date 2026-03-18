<?php
/**
 * Single product: average + detailed rating summary block.
 * Ported from MasterStudy theme's content-single-product.php.
 * Hooked to 'kadence_child_before_product_reviews' (fired in
 * woocommerce/content-single-product.php before the reviews list).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the average-rating widget and the star-breakdown bar table.
 * Only shown when WooCommerce reviews are enabled, comments are open,
 * and the product has at least one rating.
 */
function kadence_child_product_rating_summary( $product ) {
	if ( ! wc_review_ratings_enabled() || ! comments_open() ) {
		return;
	}
	$rating_count = $product->get_rating_count();
	$average      = round( $product->get_average_rating(), 1 );

	if ( $rating_count < 1 ) {
		return;
	}

	// Tally ratings per star value (1–5).
	$rates   = array_fill( 1, 5, 0 );
	$comments = get_approved_comments( get_the_ID() );
	foreach ( $comments as $comment ) {
		$star = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
		if ( isset( $rates[ $star ] ) ) {
			$rates[ $star ]++;
		}
	}
	// Descending order for the table (5 → 1).
	$rates_desc = array_reverse( $rates, true );
	?>

	<div class="single-product-rating-summary">

		<div class="average_rating">
			<div class="average_rating_unit">
				<div class="average_rating_value"><?php echo esc_html( $average ); ?></div>
				<div class="average-rating-stars">
					<?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="average_rating_num">
					<?php
					/* translators: %d: number of ratings */
					echo esc_html( sprintf( _n( '%d Rating', '%d Ratings', $rating_count, 'kadence-child' ), $rating_count ) );
					?>
				</div>
			</div>
		</div>

		<div class="detailed_rating">
			<table class="detail_rating_unit">
				<?php foreach ( $rates_desc as $star => $count ) :
					$fill = ( $rating_count > 0 ) ? round( $count * 100 / $rating_count, 2 ) : 0;
				?>
					<tr class="stars_<?php echo esc_attr( $star ); ?>">
						<td class="key">
							<?php
							/* translators: %d: star number */
							echo esc_html( sprintf( __( 'Stars %d', 'kadence-child' ), $star ) );
							?>
						</td>
						<td class="bar">
							<div class="full_bar">
								<div class="bar_filler" style="width:<?php echo esc_attr( $fill ); ?>%"></div>
							</div>
						</td>
						<td class="value"><?php echo esc_html( $count ); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>

	</div>
	<?php
}
add_action( 'kadence_child_before_product_reviews', 'kadence_child_product_rating_summary', 10, 1 );
