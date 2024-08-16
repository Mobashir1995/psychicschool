<?php
/**
 * WooCommerce Dashboard add new shortcode to customize text
 */
function psycics_woocommerce_account_dashboard_html() {
    echo do_shortcode("[insert page='my-account-dashboard' display='content']");
}
add_action( 'woocommerce_account_dashboard', 'psycics_woocommerce_account_dashboard_html' );

/**
 * Shortcode for Display Name
 */
add_shortcode( 'psycics_user_display_name', 'psycics_user_display_name_shortcode' );
function psycics_user_display_name_shortcode() {
    if( ! is_user_logged_in() ) {
        return;
    }

    $user = wp_get_current_user();
    $name = isset( $user->first_name ) && ! empty( $user->first_name ) ? $user->first_name : $user->display_name;
    return ucfirst( $name );
}

/**
 * Shortcode for Login/Logout Link
 */
add_shortcode( 'psycics_wc_logout', 'psycics_wc_logout_shortcode' );
function psycics_wc_logout_shortcode() {
    if( ! is_user_logged_in() ) {
        return;
    }

    return esc_url( wc_logout_url() );
}

/**
 * Remove Past Bookings from WooCommerce My Account Booking Tabs
 */
function psycics_remove_past_woocommerce_bookings_account_table( $tables ) {
    unset( $tables[ 'past' ] );
    return $tables;
}
add_filter( 'woocommerce_bookings_account_tables', 'psycics_remove_past_woocommerce_bookings_account_table', 99, 1 );

/**
 * Customize WooCommerce MemberShip Table Columns
 */
function psycics_memberships_my_memberships_column_names( $columns ) {
    unset( $columns[ 'membership-start-date' ] );
    unset( $columns[ 'membership-end-date' ] );
    unset( $columns[ 'membership-status' ] );
    unset( $columns[ 'membership-next-bill-on' ] );

    $columns[ 'membership-plan' ] = _x( 'Course', 'Membership plan', 'woocommerce-memberships' );
    
    return $columns;
}
add_filter( 'wc_memberships_my_memberships_column_names', 'psycics_memberships_my_memberships_column_names', 99, 1 );

/**
 * Filter membership actions on My Account and Members Area pages.
 *
 *
 * @param array $actions associative array of actions
 * @param \WC_Memberships_User_Membership $user_membership User Membership object
 * @param \WC_Product|\WP_Post|object $object current object where the action is run (optional)
 */
function psycics_memberships_my_memberships_actions( $actions, $user_membership, $object ) {
    unset( $actions[ 'cancel' ] );
    $actions[ 'view' ][ 'name' ] = __( 'Visit Classroom', 'woocommerce-memberships' );

	$restricted_content = $user_membership->get_plan()->get_restricted_content(1);

	if( is_object( $restricted_content ) && ! empty( $restricted_content ) ) {
		if( isset( $restricted_content->posts ) && is_array( $restricted_content->posts ) && !empty( $restricted_content->posts ) ) {
			$restricted_page = current( $restricted_content->posts );
			$actions[ 'view' ][ 'url' ] = get_permalink( $restricted_page->ID );
		}
	}
	
    return $actions;
}
add_filter( "wc_memberships_members_area_my-memberships_actions", 'psycics_memberships_my_memberships_actions', 99, 3  );

/**
 * Rename WooCommerce Downloads Product Column
 */
function psycics_woocommerce_account_downloads_columns( $item ) {
    $item[ 'download-product' ] = __( 'Course', 'woocommerce' );

    return $item;
}
add_filter( 'woocommerce_account_downloads_columns', 'psycics_woocommerce_account_downloads_columns', 99, 1 );


/**
 * Make sure the function does not exist before defining it
 */
if( ! function_exists( 'remove_class_filter' ) ){

	/**
	 * Remove Class Filter Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_filter() on a filter added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove filters with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 * Updated 2-27-2017 to use internal WordPress removal for 4.7+ (to prevent PHP warnings output)
	 *
	 * @param string $tag         Filter to remove
	 * @param string $class_name  Class name for the filter's callback
	 * @param string $method_name Method name for the filter's callback
	 * @param int    $priority    Priority of the filter (default 10)
	 *
	 * @return bool Whether the function is removed.
	 */
	function remove_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {

		global $wp_filter;

		// Check that filter actually exists first
		if ( ! isset( $wp_filter[ $tag ] ) ) {
			return FALSE;
		}

		/**
		 * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
		 * a simple array, rather it is an object that implements the ArrayAccess interface.
		 *
		 * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
		 *
		 * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		 */
		if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {
			// Create $fob object from filter tag, to use below
			$fob       = $wp_filter[ $tag ];
			$callbacks = &$wp_filter[ $tag ]->callbacks;
		} else {
			$callbacks = &$wp_filter[ $tag ];
		}

		// Exit if there aren't any callbacks for specified priority
		if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) {
			return FALSE;
		}

		// Loop through each filter for the specified priority, looking for our class & method
		foreach ( (array) $callbacks[ $priority ] as $filter_id => $filter ) {

			// Filter should always be an array - array( $this, 'method' ), if not goto next
			if ( ! isset( $filter['function'] ) || ! is_array( $filter['function'] ) ) {
				continue;
			}

			// If first value in array is not an object, it can't be a class
			if ( ! is_object( $filter['function'][0] ) ) {
				continue;
			}

			// Method doesn't match the one we're looking for, goto next
			if ( $filter['function'][1] !== $method_name ) {
				continue;
			}

			// Method matched, now let's check the Class
			if ( get_class( $filter['function'][0] ) === $class_name ) {

				// WordPress 4.7+ use core remove_filter() since we found the class object
				if ( isset( $fob ) ) {
					// Handles removing filter, reseting callback priority keys mid-iteration, etc.
					$fob->remove_filter( $tag, $filter['function'], $priority );

				} else {
					// Use legacy removal process (pre 4.7)
					unset( $callbacks[ $priority ][ $filter_id ] );
					// and if it was the only filter in that priority, unset that priority
					if ( empty( $callbacks[ $priority ] ) ) {
						unset( $callbacks[ $priority ] );
					}
					// and if the only filter for that tag, set the tag to an empty array
					if ( empty( $callbacks ) ) {
						$callbacks = array();
					}
					// Remove this filter from merged_filters, which specifies if filters have been sorted
					unset( $GLOBALS['merged_filters'][ $tag ] );
				}

				return TRUE;
			}
		}

		return FALSE;
	}
}

/**
 * Make sure the function does not exist before defining it
 */
if( ! function_exists( 'remove_class_action') ){

	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_action() on an action added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove actions with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 *
	 * @param string $tag         Action to remove
	 * @param string $class_name  Class name for the action's callback
	 * @param string $method_name Method name for the action's callback
	 * @param int    $priority    Priority of the action (default 10)
	 *
	 * @return bool               Whether the function is removed.
	 */
	function remove_class_action( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		return remove_class_filter( $tag, $class_name, $method_name, $priority );
	}
}

/**
 * remove My Account Partner Contents
 */
function psycics_wc_accountaffiliates_section_endpoint() {
	$user_id      = get_current_user_id () ;
	$fs_affiliate = get_user_meta ( $user_id , 'fs_affiliates_enabled' , true ) == 'yes' ;
	if ( $fs_affiliate ) {
		echo do_shortcode( "[insert page='my-account-partners-registered' display='content']" );
	} else {
		echo do_shortcode( "[insert page='my-account-partners-unregistered' display='content']" );
	}
}
remove_class_action ( 'woocommerce_account_fs-affiliates-section_endpoint' , 'FS_Affiliates_WC_Account_Management', 'PageContent' ) ;
add_action( 'woocommerce_account_fs-affiliates-section_endpoint', 'psycics_wc_accountaffiliates_section_endpoint' );