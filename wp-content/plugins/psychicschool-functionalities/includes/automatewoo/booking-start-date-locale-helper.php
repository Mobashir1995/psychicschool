<?php
/**
 * Helpers for booking.start_date_locale AutomateWoo variable.
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Resolve the customer timezone string for a booking.
 *
 * @param WC_Booking $booking Booking object.
 * @return string IANA timezone identifier.
 */
function psychicschool_get_booking_customer_timezone_string( $booking ) {
    if ( is_object( $booking ) && method_exists( $booking, 'get_local_timezone' ) ) {
        $local_timezone = $booking->get_local_timezone();
        if ( ! empty( $local_timezone ) ) {
            return $local_timezone;
        }

        if ( method_exists( $booking, 'get_booking_timezone' ) ) {
            $booking_timezone = $booking->get_booking_timezone();
            if ( ! empty( $booking_timezone ) ) {
                return $booking_timezone;
            }
        }
    }

    if ( function_exists( 'wc_booking_get_timezone_string' ) ) {
        return wc_booking_get_timezone_string();
    }

    $site_timezone = function_exists( 'wp_timezone_string' ) ? wp_timezone_string() : '';

    return $site_timezone ? $site_timezone : 'UTC';
}

/**
 * Build a DateTime for the booking start instant in the customer's timezone.
 *
 * Uses the raw Unix start (get_start) — not get_start('view', true) / get_localized_date().
 *
 * @param int|string|null $start            Booking start Unix timestamp or datetime string.
 * @param string          $timezone_string  IANA timezone identifier.
 * @return DateTime|null DateTime in customer timezone, or null on failure.
 */
function psychicschool_booking_start_datetime_in_timezone( $start, $timezone_string ) {
    if ( $start === null || $start === '' ) {
        return null;
    }

    if ( empty( $timezone_string ) ) {
        return null;
    }

    try {
        $timezone = new DateTimeZone( $timezone_string );

        if ( is_numeric( $start ) ) {
            $datetime = new DateTime( '@' . (int) $start );
        } else {
            $datetime = new DateTime( (string) $start );
        }

        $datetime->setTimezone( $timezone );

        return $datetime;
    } catch ( Exception $e ) {
        return null;
    }
}
