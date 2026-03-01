<?php
/**
 * Stats Counter markup and data attributes.
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_stats_counter_render( $atts ) {
	$title                 = isset( $atts['title'] ) ? $atts['title'] : '';
	$counter_value         = isset( $atts['counter_value'] ) ? $atts['counter_value'] : '1000';
	$duration              = isset( $atts['duration'] ) ? $atts['duration'] : '2.5';
	$icon                  = isset( $atts['icon'] ) ? trim( $atts['icon'] ) : '';
	$icon_size             = isset( $atts['icon_size'] ) ? $atts['icon_size'] : '65';
	$icon_height           = isset( $atts['icon_height'] ) ? $atts['icon_height'] : '90';
	$icon_width            = isset( $atts['icon_width'] ) ? $atts['icon_width'] : '';
	$icon_text_alignment   = isset( $atts['icon_text_alignment'] ) ? $atts['icon_text_alignment'] : 'center';
	$icon_text_color       = isset( $atts['icon_text_color'] ) ? $atts['icon_text_color'] : '#fff';
	$counter_text_color    = isset( $atts['counter_text_color'] ) ? $atts['counter_text_color'] : '#eab830';
	$text_font_size        = isset( $atts['text_font_size'] ) ? $atts['text_font_size'] : '';
	$counter_text_font_size= isset( $atts['counter_text_font_size'] ) ? $atts['counter_text_font_size'] : '';
	$border                = isset( $atts['border'] ) ? $atts['border'] : '';
	$icon_background_color = isset( $atts['icon_background_color'] ) ? $atts['icon_background_color'] : '';
	$css_class             = isset( $atts['css_class'] ) ? $atts['css_class'] : '';
	$id                    = isset( $atts['id'] ) ? $atts['id'] : ( 'kadence_stats_counter_' . uniqid() );

	// Build styles similar to MasterStudy template.
	$text_styles = array( 'color:' . esc_attr( $counter_text_color ) );
	if ( ! empty( $text_font_size ) ) {
		$text_styles[] = 'font-size:' . floatval( $text_font_size ) . 'px; line-height:' . floatval( $text_font_size ) . 'px; margin-bottom:20px;';
	}
	$text_style_attr = "style='" . esc_attr( implode( ';', $text_styles ) ) . "'";

	$counter_styles = array( 'color:' . esc_attr( $icon_text_color ) );
	if ( ! empty( $counter_text_font_size ) ) {
		$counter_styles[] = 'font-size:' . floatval( $counter_text_font_size ) . 'px; line-height:' . floatval( $counter_text_font_size ) . 'px';
	}
	$counter_style_attr = "style='" . esc_attr( implode( ';', $counter_styles ) ) . "'";

	if ( ! empty( $border ) && 'none' !== $border ) {
		$css_class .= ' with_border_' . $border;
	}
	if ( ! empty( $icon_width ) ) {
		$css_class .= ' icon_width_enabled';
	}

	$wrapper_classes = 'kadence_stats_counter text-uppercase ' . $css_class . ' text-' . $icon_text_alignment;

	ob_start();
	?>
	<div class="<?php echo esc_attr( trim( $wrapper_classes ) ); ?>"
	     style="color:<?php echo esc_attr( $icon_text_color ); ?>"
	     data-id="<?php echo esc_attr( $id ); ?>"
	     data-value="<?php echo esc_attr( $counter_value ); ?>"
	     data-duration="<?php echo esc_attr( $duration ); ?>">
		<?php if ( $icon ) : ?>
			<div class="icon"
			     style="height: <?php echo esc_attr( $icon_height ); ?>px;
			     <?php echo $icon_width ? 'width:' . esc_attr( $icon_width ) . 'px;' : ''; ?>
			     <?php echo $icon_background_color ? 'background-color:' . esc_attr( $icon_background_color ) . ';' : ''; ?>">
				<i style="font-size: <?php echo esc_attr( $icon_size ); ?>px;"
				   class="fa <?php echo esc_attr( $icon ); ?>"></i>
			</div>
		<?php endif; ?>

		<?php if ( wp_is_mobile() ) : ?>
			<div class="h1" id="<?php echo esc_attr( $id ); ?>" <?php echo $text_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo esc_html( $counter_value ); ?>
			</div>
		<?php else : ?>
			<div class="h1" id="<?php echo esc_attr( $id ); ?>" <?php echo $text_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>
		<?php endif; ?>

		<?php if ( $title ) : ?>
			<div class="stats_counter_title h5" <?php echo $counter_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo esc_html( $title ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php

	return ob_get_clean();
}

