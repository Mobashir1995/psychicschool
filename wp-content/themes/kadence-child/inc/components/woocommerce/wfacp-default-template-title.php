<?php
/**
 * FunnelKit checkout title handling for Default Template only.
 *
 * Keep this isolated so WooCommerce component customizations remain modular.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Detect FunnelKit checkout runtime running on default theme template
 * (not FunnelKit canvas/boxed templates).
 *
 * @return bool
 */
function kadence_child_wfacp_is_default_template_mode() {
	if ( ! class_exists( 'WFACP_Common' ) ) {
		return false;
	}

	$wfacp_id = absint( WFACP_Common::get_id() );
	if ( $wfacp_id < 1 ) {
		return false;
	}

	$page_template = (string) get_post_meta( $wfacp_id, '_wp_page_template', true );

	return ! in_array( $page_template, array( 'wfacp-canvas.php', 'wfacp-full-width.php' ), true );
}

/**
 * Render hero section using Kadence page-title settings/structure.
 * Mirrors Kadence's entry_hero template flow, but hard-targets "page" options.
 */
function kadence_child_wfacp_default_template_hero_title() {
	if ( ! function_exists( '\Kadence\kadence' ) || ! kadence_child_wfacp_is_default_template_mode() ) {
		return;
	}

	$classes   = array();
	$classes[] = 'entry-header';
	$classes[] = 'page-title';
	$classes[] = 'title-align-' . ( \Kadence\kadence()->sub_option( 'page_title_align', 'desktop' ) ? \Kadence\kadence()->sub_option( 'page_title_align', 'desktop' ) : 'inherit' );
	$classes[] = 'title-tablet-align-' . ( \Kadence\kadence()->sub_option( 'page_title_align', 'tablet' ) ? \Kadence\kadence()->sub_option( 'page_title_align', 'tablet' ) : 'inherit' );
	$classes[] = 'title-mobile-align-' . ( \Kadence\kadence()->sub_option( 'page_title_align', 'mobile' ) ? \Kadence\kadence()->sub_option( 'page_title_align', 'mobile' ) : 'inherit' );
	?>
	<section class="entry-hero page-hero-section <?php echo esc_attr( 'entry-hero-layout-' . \Kadence\kadence()->option( 'page_title_inner_layout' ) ); ?>">
		<div class="entry-hero-container-inner">
			<div class="hero-section-overlay"></div>
			<div class="hero-container site-container">
				<header class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<?php
					do_action( 'kadence_single_before_entry_header' );
					\Kadence\kadence()->render_title( 'page', 'above' );
					do_action( 'kadence_single_after_entry_header' );
					?>
				</header>
			</div>
		</div>
	</section>
	<?php kadence_child_wfacp_render_page_hero_background_css(); ?>
	<?php
}

/**
 * Bridge Kadence page-title background styles for FunnelKit default-template requests.
 *
 * Kadence core prints these rules in an is_page() branch. FunnelKit checkout
 * requests may bypass that branch, so we render the same page title background
 * rules here from the same Customizer options.
 */
function kadence_child_wfacp_render_page_hero_background_css() {
	if ( ! class_exists( '\Kadence\Kadence_CSS' ) || ! function_exists( '\Kadence\kadence' ) ) {
		return;
	}

	$css = new \Kadence\Kadence_CSS();

	$css->set_selector( '.page-hero-section .entry-hero-container-inner' );
	$css->render_background( \Kadence\kadence()->sub_option( 'page_title_background', 'desktop' ), $css );
	$css->add_property( 'border-top', $css->render_border( \Kadence\kadence()->sub_option( 'page_title_top_border', 'desktop' ) ) );
	$css->add_property( 'border-bottom', $css->render_border( \Kadence\kadence()->sub_option( 'page_title_bottom_border', 'desktop' ) ) );

	$css->set_selector( '.page-hero-section .hero-section-overlay' );
	$css->add_property( 'background', $css->render_color_or_gradient( \Kadence\kadence()->sub_option( 'page_title_overlay_color', 'color' ) ) );

	$compiled_css = trim( $css->css_output() );
	if ( '' === $compiled_css ) {
		return;
	}
	echo '<style id="kadence-child-wfacp-page-hero-css">' . $compiled_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Setup replacement only for default-template checkout mode.
 */
function kadence_child_wfacp_setup_default_template_title_fix() {
	if ( ! kadence_child_wfacp_is_default_template_mode() ) {
		return;
	}

	remove_action( 'kadence_hero_header', 'Kadence\hero_title' );
	add_action( 'kadence_hero_header', 'kadence_child_wfacp_default_template_hero_title', 10 );
}
add_action( 'wp', 'kadence_child_wfacp_setup_default_template_title_fix', 30 );

