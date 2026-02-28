<?php
/**
 * Colored Separator markup.
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_color_separator_render( $atts ) {
	$color     = isset( $atts['color'] ) ? $atts['color'] : '';
	$css_class = isset( $atts['css_class'] ) ? $atts['css_class'] : '';

	$style = '';
	if ( ! empty( $color ) ) {
		$style .= 'background-color:' . esc_attr( $color ) . ';';
	}

	// Basic default height and full-width line; can be overridden by custom css.
	if ( false === strpos( $style, 'height:' ) ) {
		$style .= 'height:3px;';
	}

	ob_start();
	?>
	<div class="stm-color-separator<?php echo $css_class ? ' ' . esc_attr( $css_class ) : ''; ?>"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>></div>
	<?php

	return ob_get_clean();
}

