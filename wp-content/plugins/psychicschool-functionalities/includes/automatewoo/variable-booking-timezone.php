<?php
/**
 * AutomateWoo variable: Booking time zone
 *
 * This file is loaded by AutomateWoo when the variable booking.timezone is used.
 * It must return a Variable instance.
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Variable class for displaying the booking's time zone in AutomateWoo.
 */
class Psychicschool_AutomateWoo_Booking_Timezone extends AutomateWoo\Variable {

    /**
     * Load admin details (description shown in variable picker).
     */
    public function load_admin_details() {
        $this->description = __( "Displays the booking's time zone (customer's time zone when set, otherwise the site time zone).", 'psychicschool-functionalities' );
    }

    /**
     * Get the variable value.
     *
     * @param WC_Booking $booking   The booking object.
     * @param array      $parameters Variable parameters (unused).
     * @return string Time zone string for display, e.g. "America/New_York".
     */
    public function get_value( $booking, $parameters ) {
        if ( ! is_a( $booking, 'WC_Booking' ) || ! method_exists( $booking, 'get_booking_timezone' ) ) {
            return '';
        }
        $timezone = $booking->get_booking_timezone();
        if ( empty( $timezone ) ) {
            return '';
        }
        // Format for display: replace underscores with spaces (e.g. "America/New_York" → "America/New York").
        return str_replace( '_', ' ', $timezone );
    }
}

return new Psychicschool_AutomateWoo_Booking_Timezone();
