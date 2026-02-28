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
	$css_class     = isset( $atts['css_class'] ) ? $atts['css_class'] : '';
	$unique        = isset( $atts['unique'] ) ? $atts['unique'] : ( 'stm_color_separator_' . uniqid() );
	$inline_styles = isset( $atts['inline_styles'] ) ? $atts['inline_styles'] : '';

	ob_start();
	?>
	<?php if ( ! empty( $inline_styles ) ) : ?>
		<style type="text/css" id="stm-color-separator-<?php echo esc_attr( $unique ); ?>"><?php echo $inline_styles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
	<?php endif; ?>

	<div class="kadence_colored_separator <?php echo esc_attr( trim( $css_class . ' ' . $unique ) ); ?>">
		<div class="kadence_triangled_colored_separator">
			<div class="triangle"></div>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

