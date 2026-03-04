<?php
/**
 * Footer top widget area (MasterStudy-style). Same sidebar ID: footer_top.
 */

if ( ! is_active_sidebar( 'footer_top' ) ) {
	return;
}

$footer_enabled  = kadence_child_footer_option( 'footer_top', true );
$widget_areas    = kadence_child_footer_option( 'footer_first_columns', 4 );
$widget_areas    = max( 1, min( 6, (int) $widget_areas ) );

if ( ! $footer_enabled ) {
	return;
}
?>
<div id="footer_top">
	<div class="footer_widgets_wrapper">
		<div class="container">
			<div class="widgets cols_<?php echo esc_attr( $widget_areas ); ?> clearfix">
				<?php dynamic_sidebar( 'footer_top' ); ?>
			</div>
		</div>
	</div>
</div>
