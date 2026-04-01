<?php

/**
 * AffiliateWP-related customizations and helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'psycics_is_user_affiliate_active' ) ) {
	/**
	 * Check if a user has an active AffiliateWP affiliate account.
	 *
	 * @param int $user_id User ID.
	 * @return bool
	 */
	function psycics_is_user_affiliate_active( $user_id ) {
		if ( class_exists( 'Affiliate_WP' ) ) {
			$affiliate_id = affwp_get_affiliate_id( $user_id );

			if ( $affiliate_id ) {
				$status = affwp_get_affiliate_status( $affiliate_id );

				return ( 'active' === $status );
			}
		}

		return false;
	}
}

if ( ! function_exists( 'psycics_wc_accountaffiliates_section_endpoint' ) ) {
	/**
	 * Custom handler for the WooCommerce My Account "Partnership" section.
	 */
	function psycics_wc_accountaffiliates_section_endpoint() {
		$has_affiliation = psycics_is_user_affiliate_active( get_current_user_id() );

		if ( $has_affiliation ) {
			wp_redirect( get_permalink( affwp_get_affiliate_area_page_id() ) );
			exit;
		}

		echo do_shortcode( "[insert page='my-account-partners-unregistered' display='content']" );
	}

	// Replace the default FS Affiliates endpoint content, if present.
	remove_class_action( 'woocommerce_account_fs-affiliates-section_endpoint', 'FS_Affiliates_WC_Account_Management', 'PageContent' );
	add_action( 'woocommerce_account_fs-affiliates-section_endpoint', 'psycics_wc_accountaffiliates_section_endpoint' );
}

if ( ! function_exists( 'psycics_wc_my_account_register_endpoints' ) ) {
	/**
	 * Register custom endpoint for the partners section.
	 */
	function psycics_wc_my_account_register_endpoints() {
		add_rewrite_endpoint( 'fs-affiliates-section', EP_ROOT | EP_PAGES );
	}

	add_action( 'init', 'psycics_wc_my_account_register_endpoints' );
}

if ( ! function_exists( 'psychicschool_add_fs_affiliates_section_query_var' ) ) {
	/**
	 * Expose the fs-affiliates-section endpoint as a query var so WordPress and WooCommerce recognize it.
	 * Without this, the URL /my-account/fs-affiliates-section may not set the query var and can redirect to homepage.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	function psychicschool_add_fs_affiliates_section_query_var( $vars ) {
		$vars[] = 'fs-affiliates-section';
		return $vars;
	}

	add_filter( 'query_vars', 'psychicschool_add_fs_affiliates_section_query_var', 0 );
}

if ( ! function_exists( 'psycics_affwp_custom_affiliate_tab' ) ) {
	/**
	 * Add a custom AffiliateWP tab that links to Woo My Account.
	 *
	 * @param array $tabs Tabs array.
	 * @return array
	 */
	function psycics_affwp_custom_affiliate_tab( $tabs ) {
		$tabs['wc-my-account'] = __( 'My Account', 'masterstudy-child' );

		return $tabs;
	}

	add_filter( 'affwp_affiliate_area_tabs', 'psycics_affwp_custom_affiliate_tab' );
}

if ( ! function_exists( 'psycics_custom_my_account_tab_redirect' ) ) {
	/**
	 * Redirect the AffiliateWP "My Account" tab to WooCommerce My Account.
	 */
	function psycics_custom_my_account_tab_redirect() {
		wp_redirect( wc_get_page_permalink( 'myaccount' ) );
		exit;
	}

	add_filter( 'affwp_render_affiliate_dashboard_tab_wc-my-account', 'psycics_custom_my_account_tab_redirect' );
}

