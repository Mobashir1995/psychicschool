<?php
/**
 * Footer option helper: use MasterStudy stm_option when available, else theme_mod with defaults.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a footer-related option. Uses stm_option() when available (MasterStudy), else get_theme_mod.
 *
 * @param string $key    Option key (e.g. 'footer_top', 'footer_first_columns').
 * @param mixed  $default Default value.
 * @return mixed
 */
function kadence_child_footer_option( $key, $default = null ) {
	if ( function_exists( 'stm_option' ) ) {
		$val = stm_option( $key, $default );
		return $val !== false && $val !== null ? $val : $default;
	}
	$mod_key = 'kadence_footer_' . $key;
	$val     = get_theme_mod( $mod_key, $default );
	return $val !== false && $val !== null ? $val : $default;
}
