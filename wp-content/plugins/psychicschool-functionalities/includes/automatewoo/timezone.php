<?php
/**
 * AutomateWoo Timezone
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Booking Time Zone variable to AutomateWoo.
 *
 * Use in workflows as: {{ booking.timezone }}
 *
 * @param array $variables AutomateWoo variables list by data_type and data_field.
 * @return array
 */
add_filter( 'automatewoo/variables', 'psychicschool_automatewoo_booking_timezone_variable', 10, 1 );
function psychicschool_automatewoo_booking_timezone_variable( $variables ) {
    if ( ! isset( $variables['booking'] ) || ! is_array( $variables['booking'] ) ) {
        return $variables;
    }
    $variables['booking']['timezone'] = PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/automatewoo/variable-booking-timezone.php';
    return $variables;
}