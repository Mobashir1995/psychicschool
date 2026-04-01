<?php

/**
 * Generic helpers for working with class-based hooks.
 *
 * These are copied from the Masterstudy child theme and wrapped in
 * function_exists() checks so they can safely coexist.
 */

if ( ! function_exists( 'remove_class_filter' ) ) {

	/**
	 * Remove Class Filter Without Access to Class Object.
	 *
	 * @param string $tag         Filter to remove.
	 * @param string $class_name  Class name for the filter's callback.
	 * @param string $method_name Method name for the filter's callback.
	 * @param int    $priority    Priority of the filter (default 10).
	 *
	 * @return bool Whether the function is removed.
	 */
	function remove_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;

		if ( ! isset( $wp_filter[ $tag ] ) ) {
			return false;
		}

		if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {
			$fob       = $wp_filter[ $tag ];
			$callbacks = &$wp_filter[ $tag ]->callbacks;
		} else {
			$callbacks = &$wp_filter[ $tag ];
		}

		if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) {
			return false;
		}

		foreach ( (array) $callbacks[ $priority ] as $filter_id => $filter ) {
			if ( ! isset( $filter['function'] ) || ! is_array( $filter['function'] ) ) {
				continue;
			}

			if ( ! is_object( $filter['function'][0] ) ) {
				continue;
			}

			if ( $filter['function'][1] !== $method_name ) {
				continue;
			}

			if ( get_class( $filter['function'][0] ) === $class_name ) {
				if ( isset( $fob ) ) {
					$fob->remove_filter( $tag, $filter['function'], $priority );
				} else {
					unset( $callbacks[ $priority ][ $filter_id ] );

					if ( empty( $callbacks[ $priority ] ) ) {
						unset( $callbacks[ $priority ] );
					}

					if ( empty( $callbacks ) ) {
						$callbacks = array();
					}

					unset( $GLOBALS['merged_filters'][ $tag ] );
				}

				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'remove_class_action' ) ) {

	/**
	 * Remove Class Action Without Access to Class Object.
	 *
	 * @param string $tag         Action to remove.
	 * @param string $class_name  Class name for the action's callback.
	 * @param string $method_name Method name for the action's callback.
	 * @param int    $priority    Priority of the action (default 10).
	 *
	 * @return bool Whether the function is removed.
	 */
	function remove_class_action( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		return remove_class_filter( $tag, $class_name, $method_name, $priority );
	}
}

