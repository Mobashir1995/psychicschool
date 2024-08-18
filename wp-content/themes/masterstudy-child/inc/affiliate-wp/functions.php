<?php

/**
 * Display My Account Partner Contents
 */
function psycics_wc_accountaffiliates_section_endpoint() {
	$user_id      = get_current_user_id () ;
	$has_affiliation = psycics_is_user_affiliate_active( get_current_user_id() );
	if ( $has_affiliation ) {
		wp_redirect( get_permalink( affwp_get_affiliate_area_page_id() ) );
		exit();	
	} else {
		echo do_shortcode( "[insert page='my-account-partners-unregistered' display='content']" );
	}
}
remove_class_action ( 'woocommerce_account_fs-affiliates-section_endpoint' , 'FS_Affiliates_WC_Account_Management', 'PageContent' ) ;
add_action( 'woocommerce_account_fs-affiliates-section_endpoint', 'psycics_wc_accountaffiliates_section_endpoint' );

/**
 * rewrite partnership tab endpoint
 */
function psycics_wc_my_account_register_endpoints() {
	add_rewrite_endpoint( 'fs-affiliates-section', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'psycics_wc_my_account_register_endpoints' );

/**
 * Affiliate WP Create Custom My Account Tab
 */
function my_custom_affiliate_tab( $tabs ) {
    $tabs['wc-my-account'] = __( 'My Account', 'masterstudy-child' );
    return $tabs;
}
add_filter( 'affwp_affiliate_area_tabs', 'my_custom_affiliate_tab' );

/**
 * Reidrect to WooCommerce my account for the Affiliate WP My Account Tab
 */
function psycics_custom_my_account_tab_redirect() {
    wp_redirect( wc_get_page_permalink( 'myaccount' ) );
    exit();
}
add_filter( 'affwp_render_affiliate_dashboard_tab_wc-my-account', 'psycics_custom_my_account_tab_redirect' );