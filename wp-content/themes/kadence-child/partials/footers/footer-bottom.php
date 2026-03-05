<?php
/**
 * Footer bottom widget area (MasterStudy-style). Same sidebar ID: footer_bottom.
 */

if ( ! is_active_sidebar( 'footer_bottom' ) ) {
	return;
}

$footer_enabled = kadence_child_footer_option( 'footer_bottom', true );
$widget_areas   = kadence_child_footer_option( 'footer_bottom_columns', 4 );
$widget_areas  = max( 1, min( 6, (int) $widget_areas ) );

if ( ! $footer_enabled ) {
	return;
}
?>
<div id="footer_bottom">
	<div class="footer_widgets_wrapper">
		<div class="footer-container">
			<div class="widgets cols_<?php echo esc_attr( $widget_areas ); ?> clearfix">
				<?php dynamic_sidebar( 'footer_bottom' ); ?>
			</div>
		</div>
	</div>
</div>
