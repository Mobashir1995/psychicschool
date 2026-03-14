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

if ( ! function_exists( 'psychicschool_my_account_menu_items' ) ) {
	/**
	 * Customize My Account menu: order and labels (theme-agnostic).
	 * Uses woocommerce_account_menu_items so any theme can use the default navigation template.
	 *
	 * @param array $items     Menu items (endpoint => label).
	 * @param array $endpoints Endpoints (endpoint => slug).
	 * @return array
	 */
	function psychicschool_my_account_menu_items( $items, $endpoints ) {
		$order = array(
			'dashboard',
			'orders',
			'bookings',
			'members-area',
			'downloads',
			'fs-affiliates-section',
			'edit-account',
			'customer-logout',
		);

		$labels = array(
			'dashboard'             => __( 'Dashboard', 'psychicschool-functionalities' ),
			'orders'                => __( 'Orders', 'psychicschool-functionalities' ),
			'bookings'              => __( 'Bookings', 'psychicschool-functionalities' ),
			'members-area'          => __( 'Classrooms', 'psychicschool-functionalities' ),
			'downloads'             => __( 'Workshops', 'psychicschool-functionalities' ),
			'fs-affiliates-section' => __( 'Partnership', 'psychicschool-functionalities' ),
			'edit-account'          => __( 'User Info', 'psychicschool-functionalities' ),
			'customer-logout'       => __( 'Logout', 'psychicschool-functionalities' ),
		);

		$ordered = array();
		foreach ( $order as $endpoint ) {
			if ( isset( $items[ $endpoint ] ) ) {
				$ordered[ $endpoint ] = isset( $labels[ $endpoint ] ) ? $labels[ $endpoint ] : $items[ $endpoint ];
			}
		}

		// Append any items added by other plugins that are not in our order.
		foreach ( $items as $endpoint => $label ) {
			if ( ! isset( $ordered[ $endpoint ] ) ) {
				$ordered[ $endpoint ] = isset( $labels[ $endpoint ] ) ? $labels[ $endpoint ] : $label;
			}
		}

		return $ordered;
	}

	add_filter( 'woocommerce_account_menu_items', 'psychicschool_my_account_menu_items', 20, 2 );
}

