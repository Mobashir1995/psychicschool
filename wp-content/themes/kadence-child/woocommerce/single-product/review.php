<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $comment;
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<?php
		/**
		 * The woocommerce_review_before hook
		 *
		 * @hooked woocommerce_review_display_gravatar - 10
		 */
		do_action( 'woocommerce_review_before', $comment );
		?>

		<div class="comment-text">

			<?php if ( '0' === $comment->comment_approved ) : ?>

				<p class="meta">
					<em class="woocommerce-review__awaiting-approval">
						<?php esc_html_e( 'Your review is awaiting approval', 'woocommerce' ); ?>
					</em>
				</p>

			<?php else : ?>

				<p class="woocommerce-review__author-name">
					<strong><?php comment_author(); ?></strong>
					<?php if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) : ?>
						<em class="woocommerce-review__verified verified"><?php esc_html_e( '(verified owner)', 'woocommerce' ); ?></em>
					<?php endif; ?>
				</p>

				<div class="woocommerce-review__rating-time">
					<?php
					/**
					 * The woocommerce_review_before_comment_meta hook.
					 *
					 * @hooked woocommerce_review_display_rating - 10
					 */
					do_action( 'woocommerce_review_before_comment_meta', $comment );
					?>
					<time class="woocommerce-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>">
						<?php
						/* translators: %s: human-readable time difference, e.g. "7 years ago" */
						echo esc_html( sprintf( __( '%s ago', 'woocommerce' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ) );
						?>
					</time>
				</div>

			<?php endif; ?>

			<?php
			do_action( 'woocommerce_review_before_comment_text', $comment );

			/**
			 * The woocommerce_review_comment_text hook
			 *
			 * @hooked woocommerce_review_display_comment_text - 10
			 */
			do_action( 'woocommerce_review_comment_text', $comment );

			do_action( 'woocommerce_review_after_comment_text', $comment );
			?>

		</div>
	</div>
