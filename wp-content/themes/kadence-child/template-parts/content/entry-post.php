<?php
/**
 * Template part for displaying a post in the blog archive.
 * Markup matches the STM Post List WPBakery widget for consistent grid/card design.
 *
 * @package kadence-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_date   = true;
$show_cats   = true;
$show_tags   = true;
$show_comments = true;
?>
<li class="entry-list-item kadence_post_list_content_unit">
	<div class="kadence_post_list_inner_content_unit_inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="kadence_post_list_featured_image">
				<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'View post details', 'kadence-child' ); ?>">
					<?php the_post_thumbnail( 'large', array( 'class' => 'img-responsive' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( $show_date ) : ?>
			<div class="kadence_post_list_meta_unit">
				<div class="kadence_post_list_date_d"><?php echo esc_html( get_the_date( 'd' ) ); ?></div>
				<div class="kadence_post_list_date_m"><?php echo esc_html( get_the_date( 'M' ) ); ?></div>
				<?php if ( $show_comments ) : ?>
					<?php $comments_num = get_comments_number( get_the_ID() ); ?>
					<?php if ( $comments_num ) : ?>
						<div class="kadence_post_list_comment_num">
							<span><?php echo esc_html( $comments_num ); ?></span>
							<i class="fa fa-comment"></i>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( is_sticky( get_the_ID() ) ) : ?>
					<div class="kadence_post_list_sticky_post heading_font"><?php esc_html_e( 'Sticky Post', 'kadence-child' ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="kadence_post_list_inner_content_unit <?php echo $show_date ? 'kadence_post_list_inner_content_unit_left' : ''; ?>">
			<a href="<?php the_permalink(); ?>" class="kadence_post_list_item_title text-decoration-none h3"><?php the_title(); ?></a>
			<div class="kadence_post_list_item_excerpt"><?php the_excerpt(); ?></div>
			<div class="kadence_post_list_short_separator"></div>

			<?php if ( $show_cats ) : ?>
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

			<?php if ( $show_tags ) : ?>
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
</li>
