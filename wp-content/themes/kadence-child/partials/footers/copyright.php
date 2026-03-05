<?php
/**
 * Footer copyright bar (MasterStudy-style): logo, copyright text, socials, secondary menu.
 */

$footer_copyright_enabled = kadence_child_footer_option( 'footer_copyright', true );
if ( ! $footer_copyright_enabled ) {
	return;
}

$footer_copyright_text = kadence_child_footer_option( 'footer_copyright_text', '' );
if ( '' === (string) $footer_copyright_text && ! function_exists( 'stm_option' ) ) {
	$footer_copyright_text = '&copy; ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' );
}


?>
<?php if ( $footer_copyright_text ) : ?>
	<div id="footer_copyright">
		<div class="copyright_text">
			<?php echo wp_kses_post( $footer_copyright_text ); ?>
		</div>
	</div>
<?php endif; ?>
