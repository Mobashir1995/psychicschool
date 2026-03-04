<?php
/**
 * Footer copyright bar (MasterStudy-style): logo, copyright text, socials, secondary menu.
 */

$footer_copyright_enabled = kadence_child_footer_option( 'footer_copyright', true );
if ( ! $footer_copyright_enabled ) {
	return;
}

$footer_logo_enabled   = kadence_child_footer_option( 'footer_logo_enabled', false );
$footer_logo           = kadence_child_footer_option( 'footer_logo', array() );
$footer_copyright_text = kadence_child_footer_option( 'footer_copyright_text', '' );
if ( '' === (string) $footer_copyright_text && ! function_exists( 'stm_option' ) ) {
	$footer_copyright_text = '&copy; ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' );
}

// When using stm_option, footer_logo might be an array with 'id' or 'url'.
$logo_url = '';
if ( is_array( $footer_logo ) && ! empty( $footer_logo ) ) {
	if ( ! empty( $footer_logo['id'] ) ) {
		$img = wp_get_attachment_image_src( $footer_logo['id'], 'medium' );
		$logo_url = $img ? $img[0] : '';
	} elseif ( ! empty( $footer_logo['url'] ) ) {
		$logo_url = $footer_logo['url'];
	}
} elseif ( is_string( $footer_logo ) ) {
	$logo_url = $footer_logo;
}

$copyright_use_social = function_exists( 'stm_option' ) ? stm_option( 'copyright_use_social' ) : false;
$social_links = array();
if ( $copyright_use_social && function_exists( 'stm_option' ) ) {
	$global = stm_option( null );
	if ( is_array( $global ) ) {
		$keys = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok' );
		foreach ( $keys as $key ) {
			if ( ! empty( $global[ $key ] ) && ! empty( $global['copyright_use_social'][ $key ] ) ) {
				$social_links[ $key ] = $global[ $key ];
			}
		}
	}
}
?>
<div id="footer_copyright">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-8">
				<div class="clearfix">
					<?php if ( $footer_logo_enabled && $logo_url ) : ?>
						<div class="pull-left">
							<img class="footer_logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'Footer logo', 'kadence' ); ?>"/>
						</div>
					<?php endif; ?>
					<?php if ( $footer_copyright_text ) : ?>
						<div class="copyright_text">
							<?php echo wp_kses_post( $footer_copyright_text ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-md-6 col-sm-4">
				<div class="clearfix">
					<div class="pull-right xs-pull-left">
						<?php if ( ! empty( $social_links ) ) : ?>
							<div class="pull-right">
								<div class="copyright_socials">
									<ul class="clearfix">
										<?php
										$icon_map = array(
											'twitter'  => 'fa-brands fa-x-twitter',
											'facebook' => 'fab fa-facebook',
											'instagram'=> 'fab fa-instagram',
											'linkedin' => 'fab fa-linkedin',
											'youtube'  => 'fab fa-youtube',
											'tiktok'   => 'fab fa-tiktok',
										);
										foreach ( $social_links as $key => $url ) :
											$icon = isset( $icon_map[ $key ] ) ? $icon_map[ $key ] : 'fab fa-' . $key;
											?>
											<li><a href="<?php echo esc_url( $url ); ?>"><i class="<?php echo esc_attr( $icon ); ?>"></i></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="pull-right xs-pull-left hidden-sm hidden-xs">
						<ul class="footer_menu heading_font clearfix">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'secondary',
									'depth'         => 1,
									'container'     => false,
									'menu_class'    => 'header-menu clearfix',
									'items_wrap'    => '%3$s',
									'fallback_cb'   => false,
								)
							);
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
