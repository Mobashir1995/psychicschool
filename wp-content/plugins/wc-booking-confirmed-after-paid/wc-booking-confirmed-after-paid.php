<?php
/**
 * Plugin Name: WooCommerce Bookings Autocomplete Confirmed
 * Plugin URI: https://plugin-devs.com/
 * Description: Automatically change Bookings status from Paid to "Confirmed" 
 * Version: 1.2.0
 * Author: Mobashir
 * Author URI: https://plugin-devs.com/
 * Text Domain: wc-booking-change-status
 * Tested up to: 6.4
 * Requires at least: 6.2
 * WC tested up to: 8.4
 * WC requires at least: 8.2
 * Requires PHP: 7.4
 *
 *
 * @package Mobashir
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 * Change Booking Status to Confirmed once it is paid
 * 
 * @param string  $from     old status
 * @param string  $to       new status
 * @param integer $id       Booking ID
 * @param object  $booking  Booking Object
 * 
 * @return void
 * 
 */
function pd_wc_booking_confirmed_after_paid( $from, $to, $id, $booking ) {
   $paid_to_confirm = get_option( 'pdwc_paid_to_confirm' );
  if( 'paid' === $to && 'yes' === $paid_to_confirm ) {
     $booking->update_status( 'confirmed' );
  }
}
add_action( 'woocommerce_booking_status_changed', 'pd_wc_booking_confirmed_after_paid', 99, 4 );
 

/**
 * 
 * Add Booking Settings Fields to WooCommerce General Settings 
 * 
 * @param array $settings Settings Fields
 * 
 * @return array $settings new Settings Fields
 * 
 */
function pdwc_add_booking_fields_to_wc_settings( $settings ) {
    
   $new_settings = array(
    
      array(
         'title' => 'Booking Options',
         'type'  => 'title',
         'id'    => 'pdwc_booking_option',
      ),
 
      array(
         'title'    => __( 'Automatically change Status to Confirm after Paid', 'woocommerce' ),
         'id'       => 'pdwc_paid_to_confirm',
         'type'     => 'checkbox',
      ),
 
      array(
         'type' => 'sectionend',
         'id'   => 'pdwc_booking_option',
      ),
 
   );
    
   return array_merge( $settings, $new_settings );
    
}
add_filter( 'woocommerce_general_settings', 'pdwc_add_booking_fields_to_wc_settings', 9999 );