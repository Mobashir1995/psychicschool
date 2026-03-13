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
	$primary_color   = isset( $atts['primary_color'] ) ? $atts['primary_color'] : '#196EC0';
	$secondary_color = isset( $atts['secondary_color'] ) ? $atts['secondary_color'] : '#0AED80';
	$height          = isset( $atts['height'] ) ? (int) $atts['height'] : 3;
	$width           = isset( $atts['width'] ) ? (int) $atts['width'] : 15;
	$align           = isset( $atts['align'] ) ? $atts['align'] : 'center';
	$css             = isset( $atts['css'] ) ? $atts['css'] : '';

	$height = $height > 0 ? $height : 3;
	$width  = $width > 0 ? $width : 15;

	$wrapper_classes   = array( 'kadence_multy_separator_wrapper' );
	$wrapper_classes[] = 'kadence_multy_separator_align_' . $align;
	if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes[] = vc_shortcode_custom_css_class( $css );
	}

	$wrapper_style = '';
	if ( 'left' === $align ) {
		$wrapper_style = 'text-align:left;';
	} elseif ( 'right' === $align ) {
		$wrapper_style = 'text-align:right;';
	} else {
		$wrapper_style = 'text-align:center;';
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>" style="<?php echo esc_attr( $wrapper_style ); ?>">
		<div class="kadence_multy_separator" style="height:<?php echo esc_attr( $height ); ?>px;width:<?php echo esc_attr( $width ); ?>%;">
			<span class="kadence_multy_separator_segment kadence_multy_separator_segment_primary" style="background-color:<?php echo esc_attr( $primary_color ); ?>;"></span>
			<span class="kadence_multy_separator_segment kadence_multy_separator_segment_secondary" style="background-color:<?php echo esc_attr( $secondary_color ); ?>;"></span>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

