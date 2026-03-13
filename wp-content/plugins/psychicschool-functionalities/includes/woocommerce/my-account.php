<?php

/**
 * WooCommerce My Account and related customizations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'psycics_woocommerce_account_dashboard_html' ) ) {
	/**
	 * Output custom dashboard content via shortcode.
	 */
	function psycics_woocommerce_account_dashboard_html() {
		echo do_shortcode( "[insert page='my-account-dashboard' display='content']" );
	}

	add_action( 'woocommerce_account_dashboard', 'psycics_woocommerce_account_dashboard_html' );
}

if ( ! function_exists( 'psycics_user_display_name_shortcode' ) ) {
	/**
	 * Shortcode to output the current user's display name.
	 *
	 * @return string|null
	 */
	function psycics_user_display_name_shortcode() {
		if ( ! is_user_logged_in() ) {
			return '';
		}

		$user = wp_get_current_user();
		$name = isset( $user->first_name ) && ! empty( $user->first_name ) ? $user->first_name : $user->display_name;

		return ucfirst( $name );
	}

	add_shortcode( 'psycics_user_display_name', 'psycics_user_display_name_shortcode' );
}

if ( ! function_exists( 'psycics_wc_logout_shortcode' ) ) {
	/**
	 * Shortcode to output a logout URL.
	 *
	 * @return string
	 */
	function psycics_wc_logout_shortcode() {
		if ( ! is_user_logged_in() ) {
			return '';
		}

		return esc_url( wc_logout_url() );
	}

	add_shortcode( 'psycics_wc_logout', 'psycics_wc_logout_shortcode' );
}

if ( ! function_exists( 'psycics_remove_past_woocommerce_bookings_account_table' ) ) {
	/**
	 * Remove "past" bookings table from My Account > Bookings.
	 *
	 * @param array $tables Bookings tables.
	 * @return array
	 */
	function psycics_remove_past_woocommerce_bookings_account_table( $tables ) {
		if ( isset( $tables['past'] ) ) {
			unset( $tables['past'] );
		}

		return $tables;
	}

	add_filter( 'woocommerce_bookings_account_tables', 'psycics_remove_past_woocommerce_bookings_account_table', 99, 1 );
}

if ( ! function_exists( 'psycics_memberships_my_memberships_column_names' ) ) {
	/**
	 * Customize WooCommerce Memberships table columns in My Account.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	function psycics_memberships_my_memberships_column_names( $columns ) {
		unset( $columns['membership-start-date'] );
		unset( $columns['membership-end-date'] );
		unset( $columns['membership-status'] );
		unset( $columns['membership-next-bill-on'] );

		$columns['membership-plan'] = _x( 'Course', 'Membership plan', 'woocommerce-memberships' );

		return $columns;
	}

	add_filter( 'wc_memberships_my_memberships_column_names', 'psycics_memberships_my_memberships_column_names', 99, 1 );
}

if ( ! function_exists( 'psycics_memberships_my_memberships_actions' ) ) {
	/**
	 * Filter membership actions links on My Account and Members Area pages.
	 *
	 * @param array                          $actions         Actions.
	 * @param \WC_Memberships_User_Membership $user_membership User Membership object.
	 * @param mixed                          $object          Current object.
	 * @return array
	 */
	function psycics_memberships_my_memberships_actions( $actions, $user_membership, $object ) {
		if ( isset( $actions['cancel'] ) ) {
			unset( $actions['cancel'] );
		}

		if ( isset( $actions['view'] ) ) {
			$actions['view']['name'] = __( 'Visit Classroom', 'woocommerce-memberships' );
		}

		$restricted_content = $user_membership->get_plan()->get_restricted_content( 1 );

		if ( is_object( $restricted_content ) && ! empty( $restricted_content ) ) {
			if ( isset( $restricted_content->posts ) && is_array( $restricted_content->posts ) && ! empty( $restricted_content->posts ) ) {
				$restricted_page           = current( $restricted_content->posts );
				$actions['view']['url'] = get_permalink( $restricted_page->ID );
			}
		}

		return $actions;
	}

	add_filter( 'wc_memberships_members_area_my-memberships_actions', 'psycics_memberships_my_memberships_actions', 99, 3 );
}

if ( ! function_exists( 'psycics_woocommerce_account_downloads_columns' ) ) {
	/**
	 * Rename the downloads product column label.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	function psycics_woocommerce_account_downloads_columns( $columns ) {
		$columns['download-product'] = __( 'Course', 'woocommerce' );

		return $columns;
	}

	add_filter( 'woocommerce_account_downloads_columns', 'psycics_woocommerce_account_downloads_columns', 99, 1 );
}

if ( ! function_exists( 'filter_function_name_5824' ) ) {
	/**
	 * Placeholder filter for account menu items (currently passthrough).
	 *
	 * @param array $items      Menu items.
	 * @param array $endpoints  Endpoints.
	 * @return array
	 */
	function filter_function_name_5824( $items, $endpoints ) {
		return $items;
	}

	add_filter( 'woocommerce_account_menu_items', 'filter_function_name_5824', 10, 2 );
}

