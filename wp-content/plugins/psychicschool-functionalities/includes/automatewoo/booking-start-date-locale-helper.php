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
 * Resolve the site/booking storage timezone string.
 *
 * @return string IANA timezone identifier.
 */
function psychicschool_get_booking_site_timezone_string() {
    if ( function_exists( 'wc_booking_get_timezone_string' ) ) {
        $timezone = wc_booking_get_timezone_string();
        if ( ! empty( $timezone ) ) {
            return $timezone;
        }
    }

    if ( function_exists( 'wp_timezone_string' ) ) {
        $timezone = wp_timezone_string();
        if ( ! empty( $timezone ) ) {
            return $timezone;
        }
    }

    return 'UTC';
}

/**
 * Parse the raw _booking_start meta value (YmdHis wall clock in site TZ).
 *
 * WC Bookings stores start/end as date( 'YmdHis', $unix ) in site timezone but reads
 * them back with strtotime(), which mis-parses the compact string. Parse explicitly here.
 *
 * @param string $stored                Raw _booking_start meta (e.g. 20260709173000).
 * @param string $site_timezone_string  IANA site timezone identifier.
 * @return DateTime|null DateTime in site timezone, or null on failure.
 */
function psychicschool_booking_start_datetime_from_stored_meta( $stored, $site_timezone_string ) {
    if ( $stored === null || $stored === '' || empty( $site_timezone_string ) ) {
        return null;
    }

    try {
        $timezone = new DateTimeZone( $site_timezone_string );
        $datetime = DateTime::createFromFormat( 'YmdHis', (string) $stored, $timezone );

        return $datetime instanceof DateTime ? $datetime : null;
    } catch ( Exception $e ) {
        return null;
    }
}

/**
 * Build a DateTime for the booking start in the customer's timezone.
 *
 * Reads _booking_start meta directly (site TZ wall clock) — not get_start(), which
 * mis-parses the stored YmdHis string via strtotime().
 *
 * @param WC_Booking $booking Booking object.
 * @return DateTime|null DateTime in customer timezone, or null on failure.
 */
function psychicschool_get_booking_start_datetime_customer( $booking ) {
    if ( ! is_object( $booking ) || ! method_exists( $booking, 'get_id' ) ) {
        return null;
    }

    $stored = get_post_meta( $booking->get_id(), '_booking_start', true );
    if ( $stored === null || $stored === '' ) {
        return null;
    }

    $site_datetime = psychicschool_booking_start_datetime_from_stored_meta(
        $stored,
        psychicschool_get_booking_site_timezone_string()
    );

    if ( ! $site_datetime ) {
        return null;
    }

    $customer_timezone_string = psychicschool_get_booking_customer_timezone_string( $booking );

    try {
        $site_datetime->setTimezone( new DateTimeZone( $customer_timezone_string ) );
        return $site_datetime;
    } catch ( Exception $e ) {
        return null;
    }
}

/**
 * Resolve AutomateWoo-style format parameter to a PHP date format string.
 *
 * @param array $parameters Variable parameters (format, custom-format).
 * @return string PHP date format.
 */
function psychicschool_resolve_booking_datetime_format_param( $parameters ) {
    if ( empty( $parameters['format'] ) ) {
        return 'Y-m-d H:i:s';
    }

    if ( $parameters['format'] === 'custom' ) {
        return ! empty( $parameters['custom-format'] ) ? $parameters['custom-format'] : 'Y-m-d H:i:s';
    }

    if ( $parameters['format'] === 'mysql' ) {
        return 'Y-m-d H:i:s';
    }

    if ( $parameters['format'] === 'site' ) {
        return function_exists( 'wc_date_format' ) ? wc_date_format() : get_option( 'date_format' );
    }

    return $parameters['format'];
}

/**
 * Format a booking DateTime for customer-facing output using wp_date() in the DateTime TZ.
 *
 * AutomateWoo's Variable_Abstract_Datetime::format_datetime() always uses date_i18n()
 * in site timezone, which breaks customer-local dates. Use this instead.
 *
 * @param DateTime|null $datetime   DateTime (with target timezone set).
 * @param array         $parameters Variable parameters (format, modify, custom-format).
 * @return string Formatted string, or empty string on failure.
 */
function psychicschool_format_booking_customer_datetime( $datetime, $parameters ) {
    if ( ! $datetime instanceof DateTime ) {
        return '';
    }

    if ( ! empty( $parameters['modify'] ) ) {
        try {
            $datetime = clone $datetime;
            $datetime->modify( $parameters['modify'] );
        } catch ( Exception $e ) {
            return '';
        }
    }

    $format   = psychicschool_resolve_booking_datetime_format_param( $parameters );
    $timezone = $datetime->getTimezone();

    return wp_date( $format, $datetime->getTimestamp(), $timezone );
}

/**
 * Build a DateTime for the booking start instant in the customer's timezone.
 *
 * Low-level helper for tests and Unix-timestamp inputs. Production code should use
 * psychicschool_get_booking_start_datetime_customer() which reads _booking_start meta.
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
