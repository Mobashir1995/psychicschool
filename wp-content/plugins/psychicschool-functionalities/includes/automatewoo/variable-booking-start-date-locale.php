<?php
/**
 * AutomateWoo variable: Booking start date in customer locale timezone
 *
 * Use with the built-in booking.start_time for customer-facing emails:
 *   {{ booking.start_date_locale | format: 'custom', custom-format: 'l, F j, Y' }}
 *   {{ booking.start_time }}
 *   {{ booking.timezone }}
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/automatewoo/booking-start-date-locale-helper.php';

/**
 * Variable class: booking start date in customer timezone (supports format & modify).
 */
class Psychicschool_AutomateWoo_Booking_Start_Date_Locale extends AutomateWoo\Variable_Abstract_Datetime {

    /**
     * Load admin details (description shown in variable picker).
     */
    public function load_admin_details() {
        $this->description = __( "Booking start date in the customer's timezone (from _local_timezone when set). Use with booking.start_time for emails, e.g. format: 'custom', custom-format: 'l, F j, Y'.", 'psychicschool-functionalities' );
        parent::load_admin_details();
    }

    /**
     * Get the variable value: start date in customer timezone.
     *
     * @param WC_Booking $booking    The booking object.
     * @param array      $parameters Variable parameters (format, modify, etc.).
     * @return string|false Formatted date string, or false if no start.
     */
    public function get_value( $booking, $parameters ) {
        if ( ! is_a( $booking, 'WC_Booking' ) ) {
            return false;
        }

        $datetime = psychicschool_get_booking_start_datetime_customer( $booking );

        if ( ! $datetime ) {
            return false;
        }

        $formatted = psychicschool_format_booking_customer_datetime( $datetime, $parameters );

        return $formatted !== '' ? $formatted : false;
    }
}

return new Psychicschool_AutomateWoo_Booking_Start_Date_Locale();
