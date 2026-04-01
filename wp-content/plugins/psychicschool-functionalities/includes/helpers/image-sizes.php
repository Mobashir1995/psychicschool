<?php

/**
 * Global image sizes registration.
 *
 * These sizes are intended to be available regardless of the active theme.
 * The function name is guarded to avoid conflicts with the Masterstudy child.
 */

if ( ! function_exists( 'abc_set_size' ) ) {
	/**
	 * Register custom image sizes.
	 */
	function abc_set_size() {
		add_image_size( 'img-270-135', 270, 135, true );
		add_image_size( 'img-300-150', 300, 150, true );
		add_image_size( 'img-270-180', 270, 180, true );
		add_image_size( 'img-129-129', 129, 129, true );
		add_image_size( 'img-75-75', 75, 75, true );
	}

	add_action( 'after_setup_theme', 'abc_set_size' );
}

