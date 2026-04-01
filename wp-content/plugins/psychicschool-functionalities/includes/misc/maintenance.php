<?php

/**
 * Miscellaneous site-wide helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'psycics_sitewide_maintenance_message' ) ) {
	/**
	 * Optional maintenance message above password form container.
	 *
	 * Hooked to `ppw_sitewide_above_password_form_container` to match existing usage.
	 */
	function psycics_sitewide_maintenance_message() {
		echo '<h2>We\'re performing scheduled maintenance to improve your experience.</h2>';
		echo '<p>The site will be back online shortly — thank you for your patience.</p>';
	}

	add_action( 'ppw_sitewide_above_password_form_container', 'psycics_sitewide_maintenance_message' );
}

