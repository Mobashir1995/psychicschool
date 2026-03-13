<?php
/**
 * Kadence Child - STM Multy Separator markup.
 *
 * Simple two-color horizontal separator bar.
 *
 * @param array $atts Processed shortcode attributes.
 *
 * @return string
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kadence_child_stm_multy_separator_render( $atts ) {
	$css             = isset( $atts['css'] ) ? $atts['css'] : '';

	$wrapper_classes   = array( 'kadence_multy_separator_wrapper' );
	if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes[] = vc_shortcode_custom_css_class( $css );
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
		<div class="kadence_multy_separator"></div>
	</div>
	<?php

	return ob_get_clean();
}

