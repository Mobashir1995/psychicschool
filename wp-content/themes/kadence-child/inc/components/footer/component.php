<?php
/**
 * MasterStudy-style footer for Kadence child theme.
 * Registers footer_top and footer_bottom sidebars (same IDs as MasterStudy)
 * and outputs the same footer structure so existing widgets are preserved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/footer-options.php';

/**
 * Register footer widget areas (same IDs as MasterStudy).
 */
function kadence_child_register_footer_sidebars() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Top', 'kadence' ),
			'id'            => 'footer_top',
			'description'   => __( 'Widgets in the top footer area.', 'kadence' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title'  => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer Bottom', 'kadence' ),
			'id'            => 'footer_bottom',
			'description'   => __( 'Widgets in the bottom footer area.', 'kadence' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'kadence_child_register_footer_sidebars' );

/**
 * Replace Kadence default footer with MasterStudy-style footer.
 */
function kadence_child_footer_markup() {
	// Respect Kadence layout: hide footer when layout says no footer.
	if ( class_exists( '\Kadence\Layout\Component' ) && method_exists( '\Kadence\Layout\Component', 'no_footer' ) ) {
		if ( \Kadence\Layout\Component::no_footer() ) {
			return;
		}
	}

	$footer_dir = get_stylesheet_directory() . '/partials/footers';
	?>
	<footer id="footer" class="kadence_child_footer parallax-off">
		<div class="footer_wrapper">
			<?php
			if ( file_exists( $footer_dir . '/footer-top.php' ) ) {
				include $footer_dir . '/footer-top.php';
			}
			if ( file_exists( $footer_dir . '/footer-bottom.php' ) ) {
				include $footer_dir . '/footer-bottom.php';
			}
			if ( file_exists( $footer_dir . '/copyright.php' ) ) {
				include $footer_dir . '/copyright.php';
			}
			?>
		</div>
	</footer>
	<?php
}

/**
 * Unhook Kadence footer and hook our MasterStudy-style footer.
 */
function kadence_child_swap_footer() {
	// remove_action( 'kadence_footer', 'Kadence\footer_markup', 10 );
	add_action( 'kadence_before_footer', 'kadence_child_footer_markup', 10 );
}
add_action( 'init', 'kadence_child_swap_footer', 20 );
