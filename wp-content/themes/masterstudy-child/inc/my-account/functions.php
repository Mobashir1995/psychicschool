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
