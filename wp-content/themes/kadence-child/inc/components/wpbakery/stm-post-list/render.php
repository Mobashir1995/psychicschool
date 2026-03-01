<?php
/**
 * Kadence Child - Post List markup (based on MasterStudy).
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_post_list_render( $atts ) {
	$name                  = isset( $atts['name'] ) ? $atts['name'] : '';
	$image                 = isset( $atts['image'] ) ? $atts['image'] : '';
	$post_list_data_source = isset( $atts['post_list_data_source'] ) ? $atts['post_list_data_source'] : 'post';
	$post_list_per_page    = isset( $atts['post_list_per_page'] ) ? (int) $atts['post_list_per_page'] : 3;
	$post_list_per_row     = isset( $atts['post_list_per_row'] ) ? (int) $atts['post_list_per_row'] : 3;
	$post_list_show_date   = ! empty( $atts['post_list_show_date'] );
	$post_list_show_cats   = ! empty( $atts['post_list_show_cats'] );
	$post_list_show_tags   = ! empty( $atts['post_list_show_tags'] );
	$post_list_show_comments = ! empty( $atts['post_list_show_comments'] );
	$custom_color          = isset( $atts['custom_color'] ) ? $atts['custom_color'] : '';

	$rand_class = 'kadence_post_list_' . wp_rand( 0, 9999 );

	$query = new WP_Query(
		array(
			'post_type'           => $post_list_data_source,
			'posts_per_page'      => $post_list_per_page,
			'ignore_sticky_posts' => 1,
		)
	);

	ob_start();

	// Calculate bootstrap cols.
	$post_list_item_col = $post_list_per_row > 0 ? intval( 12 / $post_list_per_row ) : 12;
	$current_list_item  = 0;

	if ( ! empty( $custom_color ) ) :
		?>
		<style>
			.<?php echo esc_attr( $rand_class ); ?> * {
				color: <?php echo esc_attr( $custom_color ); ?> !important;
			}
		</style>
	<?php
	endif;

	if ( $query->have_posts() ) :
		?>
		<div class="kadence_post_list_main_section_wrapper <?php echo esc_attr( $rand_class ); ?>">
			<div class="row">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					++$current_list_item;
					?>
					<div class="col-md-<?php echo esc_attr( $post_list_item_col ); ?> col-sm-<?php echo esc_attr( $post_list_item_col ); ?> col-xs-12">
						<div class="kadence_post_list_content_unit">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="kadence_post_list_featured_image">
									<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'View post details', 'kadence-child' ); ?>">
										<?php the_post_thumbnail( 'large', array( 'class' => 'img-responsive' ) ); ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="row">
								<?php if ( $post_list_show_date ) : ?>
									<div class="col-md-3 col-sm-4 col-xs-12">
										<div class="kadence_post_list_meta_unit">
											<div class="kadence_post_list_date_d"><?php echo esc_html( get_the_date( 'd' ) ); ?></div>
											<div class="kadence_post_list_date_m"><?php echo esc_html( get_the_date( 'M' ) ); ?></div>
											<?php if ( $post_list_show_comments ) : ?>
												<?php $comments_num = get_comments_number( get_the_ID() ); ?>
												<?php if ( $comments_num ) : ?>
													<div class="kadence_post_list_comment_num">
														<span><?php echo esc_html( $comments_num ); ?></span><i class="fa-icon-stm_icon_comment_o"></i>
													</div>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ( is_sticky( get_the_ID() ) ) : ?>
												<div class="kadence_post_list_sticky_post heading_font"><?php esc_html_e( 'Sticky Post', 'kadence-child' ); ?></div>
											<?php endif; ?>
										</div>
									</div>
								<?php endif; ?>
								<div class="<?php echo $post_list_show_date ? 'col-md-9 col-sm-8 col-xs-12' : 'col-md-12'; ?>">
									<div class="kadence_post_list_inner_content_unit <?php echo $post_list_show_date ? 'kadence_post_list_inner_content_unit_left' : ''; ?>">
										<a href="<?php the_permalink(); ?>" class="kadence_post_list_item_title h3"><?php the_title(); ?></a>
										<div class="kadence_post_list_item_excerpt"><?php the_excerpt(); ?></div>
										<div class="kadence_post_list_short_separator"></div>

										<?php if ( $post_list_show_cats ) : ?>
											<?php
											$post_list_item_categories = wp_get_post_categories( get_the_ID() );
											if ( ! empty( $post_list_item_categories ) ) :
												?>
												<div class="kadence_post_list_cats">
													<span class="kadence_post_list_cats_label"><?php esc_html_e( 'Posted in:', 'kadence-child' ); ?></span>
													<?php foreach ( $post_list_item_categories as $post_list_single_cat ) : ?>
														<?php $post_list_cat = get_category( $post_list_single_cat ); ?>
														<a href="<?php echo esc_url( get_term_link( $post_list_cat ) ); ?>"><?php echo esc_html( $post_list_cat->name ); ?></a><span class="kadence_post_list_divider">,</span>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										<?php endif; ?>

										<?php if ( $post_list_show_tags ) : ?>
											<?php
											$posttags = get_the_tags();
											if ( $posttags ) :
												?>
												<div class="kadence_post_list_item_tags">
													<span class="kadence_post_list_tags_label"><?php esc_html_e( 'Tags:', 'kadence-child' ); ?></span>
													<?php foreach ( $posttags as $tag_object ) : ?>
														<a href="<?php echo esc_url( get_tag_link( $tag_object->term_id ) ); ?>"><?php echo esc_html( $tag_object->name ); ?></a><span class="kadence_post_list_divider">,</span>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ( 0 === $current_list_item % max( 1, $post_list_per_row ) ) : ?>
						</div>
						<div class="row">
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	endif;

	return ob_get_clean();
}

