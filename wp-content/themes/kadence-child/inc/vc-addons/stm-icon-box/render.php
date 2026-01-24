<?php
/**
 * Self-contained Icon Box markup and styles.
 * Used when MasterStudy is not the parent theme.
 *
 * @param array $atts Processed shortcode attributes.
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_icon_box_render( $atts ) {
	$unique            = isset( $atts['unique'] ) ? $atts['unique'] : uniqid( 'stm_icon_box_' );
	$css_class         = isset( $atts['css_class'] ) ? $atts['css_class'] : '';
	$link              = isset( $atts['link'] ) && is_array( $atts['link'] ) ? $atts['link'] : array();
	$link_color_style  = isset( $atts['link_color_style'] ) ? $atts['link_color_style'] : 'standart';
	$box_align         = isset( $atts['box_align'] ) ? $atts['box_align'] : 'left';
	$hover_pos         = isset( $atts['hover_pos'] ) ? $atts['hover_pos'] : 'none';
	$icon_align        = isset( $atts['icon_align'] ) ? $atts['icon_align'] : 'center';
	$icon_height       = isset( $atts['icon_height'] ) ? $atts['icon_height'] : 65;
	$icon_width        = isset( $atts['icon_width'] ) ? $atts['icon_width'] : 65;
	$icon_size         = isset( $atts['icon_size'] ) ? $atts['icon_size'] : 60;
	$icon_color        = isset( $atts['icon_color'] ) ? $atts['icon_color'] : '#fff';
	$box_bg_color      = isset( $atts['box_bg_color'] ) ? $atts['box_bg_color'] : '';
	$box_text_color    = isset( $atts['box_text_color'] ) ? $atts['box_text_color'] : '';
	$box_icon_bg_color = isset( $atts['box_icon_bg_color'] ) ? $atts['box_icon_bg_color'] : '';
	$icon              = isset( $atts['icon'] ) ? $atts['icon'] : '';
	$css_icon_class    = isset( $atts['css_icon_class'] ) ? $atts['css_icon_class'] : '';
	$title             = isset( $atts['title'] ) ? $atts['title'] : '';
	$title_holder      = isset( $atts['title_holder'] ) ? $atts['title_holder'] : 'h3';
	$content           = isset( $atts['content'] ) ? $atts['content'] : '';

	// Per-element inline CSS
	$icon_inline_css = ( 'center' === $icon_align )
		? 'height:' . esc_attr( $icon_height ) . 'px;'
		: 'width:' . esc_attr( $icon_width ) . 'px;';
	if ( ! empty( $box_icon_bg_color ) ) {
		$icon_inline_css .= ' background-color: ' . esc_attr( $box_icon_bg_color ) . ';';
	}

	$inline_css = ".{$unique} { background:{$box_bg_color} !important; color:{$box_text_color}; }
.{$unique} .icon { {$icon_inline_css} }
.{$unique} .icon svg { width: {$icon_size}px; height: {$icon_size}px; }
.{$unique} .icon svg, .{$unique} .icon i { font-size: {$icon_size}px; color: {$icon_color} !important; }
.icon_alignment_center .{$unique} { text-align: center; margin-right: auto; margin-left: auto; }
.icon_alignment_left .{$unique} { text-align: left; }
.icon_alignment_right .{$unique} { text-align: right; margin-right: 0; margin-left: auto; }";

	$css_class       .= ' stm_icon_box_hover_' . $hover_pos;
	$icon_b_classes   = array( $css_class, $link_color_style, $unique, 'box_align_' . $box_align, 'clearfix' );

	// Enqueue base + per-element styles
	stm_icon_box_enqueue_styles( $inline_css );

	ob_start();
	?>
	<div class="kadence-child-stm-icon-box">
		<?php if ( ! empty( $link['url'] ) ) : ?>
			<a href="<?php echo esc_url( $link['url'] ); ?>" title="<?php echo esc_attr( ! empty( $link['title'] ) ? $link['title'] : '' ); ?>"
				<?php echo ! empty( $link['target'] ) ? ' target="_blank"' : ''; ?>>
		<?php endif; ?>
		<div class="icon_box <?php echo esc_attr( implode( ' ', $icon_b_classes ) ); ?>">
			<div class="icon_alignment_<?php echo esc_attr( $icon_align ); ?>">
				<?php if ( $icon ) : ?>
					<div class="icon <?php echo esc_attr( $css_icon_class ); ?>">
						<i class="<?php echo esc_attr( $icon ); ?>"></i>
					</div>
				<?php endif; ?>
				<div class="icon_text">
					<?php if ( $title ) : ?>
						<<?php echo esc_attr( $title_holder ); ?> style="color:<?php echo esc_attr( $box_text_color ); ?>"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_holder ); ?>>
					<?php endif; ?>
					<?php echo function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( $content, true ) : $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
		<?php if ( ! empty( $link['url'] ) ) : ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Enqueue base iconbox CSS and per-element inline styles.
 * Uses stm_module_styles when available (MasterStudy), otherwise local enqueue.
 *
 * @param string $inline_css Per-element CSS.
 */
function stm_icon_box_enqueue_styles( $inline_css ) {
	if ( function_exists( 'stm_module_styles' ) ) {
		stm_module_styles( 'iconbox', 'style_1', array(), $inline_css );
		return;
	}

	static $base_loaded = false;
	$handle = 'kadence-child-stm-iconbox';

	if ( ! $base_loaded ) {
		wp_enqueue_style( $handle, false, array(), KADENCE_CHILD_VERSION );
		$base_loaded = true;
	}

	wp_add_inline_style( $handle, $inline_css );
}
