<?php
/**
 * Kadence Child - Product Categories markup (based on MasterStudy).
 * No row/col; Kadence-prefixed classes only.
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_product_categories_render( $atts ) {
	$view_type      = isset( $atts['view_type'] ) ? $atts['view_type'] : 'stm_vc_product_cat_carousel';
	$number         = isset( $atts['number'] ) ? $atts['number'] : '';
	$per_row        = isset( $atts['per_row'] ) ? (int) $atts['per_row'] : 6;
	$box_text_color = isset( $atts['box_text_color'] ) ? $atts['box_text_color'] : '#fff';
	$text_align     = isset( $atts['text_align'] ) ? $atts['text_align'] : 'center';
	$icon_size      = isset( $atts['icon_size'] ) ? $atts['icon_size'] : '60';
	$icon_height    = isset( $atts['icon_height'] ) ? $atts['icon_height'] : '69';
	$css_class      = isset( $atts['css_class'] ) ? $atts['css_class'] : '';

	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
	);
	if ( $number !== '' && $number > 0 ) {
		$args['number'] = (int) $number;
	}

	$terms = get_terms( $args );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}

	$view_class = 'kadence_product_categories_view_list';
	if ( $view_type === 'stm_vc_product_cat_carousel' ) {
		$view_class = 'kadence_product_categories_view_carousel';
	} elseif ( $view_type === 'stm_vc_product_cat_card' ) {
		$view_class = 'kadence_product_categories_view_card';
	}
	$per_row_class = 'kadence_product_categories_per_row_' . max( 1, min( 6, $per_row ) );

	ob_start();
	?>
	<div class="kadence_product_categories_main_wrapper <?php echo esc_attr( $view_class . ' ' . $per_row_class . ' ' . $css_class ); ?>">
		<?php foreach ( $terms as $term ) : ?>
			<?php
			$term_meta = get_option( 'taxonomy_' . $term->term_id );
			$item_clr  = ( ! empty( $term_meta['custom_term_meta'] ) ) ? $term_meta['custom_term_meta'] : '#eab830';

			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			if ( $thumbnail_id ) {
				$cat_image = wp_get_attachment_image_src( (int) $thumbnail_id, 'medium' );
			} else {
				$cat_image = false;
			}
			$term_link = get_term_link( $term );
			if ( is_wp_error( $term_link ) ) {
				$term_link = '#';
			}
			?>
			<div class="kadence_product_categories_item_wrapper">
				<a href="<?php echo esc_url( $term_link ); ?>" title="<?php esc_attr_e( 'View category', 'kadence-child' ); ?>">
					<div class="kadence_product_categories_item kadence_product_categories_text_<?php echo esc_attr( $text_align ); ?>"
						style="background-color:<?php echo esc_attr( $item_clr ); ?>;<?php echo $box_text_color ? ' color:' . esc_attr( $box_text_color ) . ';' : ''; ?>">
						<?php if ( ! empty( $term_meta['custom_term_font'] ) && ! $cat_image ) : ?>
							<i class="fa <?php echo esc_attr( $term_meta['custom_term_font'] ); ?>"
								style="font-size:<?php echo esc_attr( $icon_size ); ?>px; height:<?php echo esc_attr( $icon_height ); ?>px;"></i>
						<?php else : ?>
							<div class="kadence_product_categories_cat_image" style="height:<?php echo esc_attr( $icon_height ); ?>px;">
								<?php if ( ! empty( $cat_image[0] ) ) : ?>
									<img src="<?php echo esc_url( $cat_image[0] ); ?>"
										style="height:<?php echo esc_attr( $icon_size ); ?>px;"
										alt="<?php esc_attr_e( 'Category image', 'kadence-child' ); ?>"/>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="kadence_product_categories_title_wrapper">
							<div class="kadence_product_categories_title h5"
								style="<?php echo $box_text_color ? 'color:' . esc_attr( $box_text_color ) . ';' : ''; ?>"><?php echo esc_html( $term->name ); ?></div>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
