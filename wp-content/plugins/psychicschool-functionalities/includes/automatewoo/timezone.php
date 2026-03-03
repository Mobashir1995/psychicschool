<?php
/**
 * AutomateWoo: variables and rules
 *
 * Format options for date variables (e.g. booking.start_date, booking.start_date_site_tz):
 *   format: 'mysql'        → MySQL datetime (Y-m-d H:i:s)
 *   format: 'custom', custom-format: 'Y-m-d H:i' → PHP date format (see php.net/date)
 *   format: 'date'         → Date only (if supported)
 *   format: 'time'         → Time only (if supported)
 *   modify: '-1 hour'      → Relative change (+/- time, e.g. '-1 day', '+2 hours')
 * Example: {{ booking.start_date_site_tz | format: 'mysql', modify: '-1 hour' }}
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
    $variables['booking']['start_date_site_tz'] = PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/automatewoo/variable-booking-start-date-site-tz.php';
    return $variables;
}

/**
 * Add Booking Resource rule to AutomateWoo.
 *
 * Use in workflow rules: "Booking - Resource" is / is not [resource].
 *
 * @param array $includes Rule name => class name.
 * @return array
 */
add_filter( 'automatewoo/rules/includes', 'psychicschool_automatewoo_booking_resource_rule', 10, 1 );
function psychicschool_automatewoo_booking_resource_rule( $includes ) {
    if ( ! is_array( $includes ) ) {
        return $includes;
    }
    // Load rule class only when AutomateWoo is building rules (so its base classes exist).
    require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/automatewoo/rule-booking-resource.php';
    $includes['booking_resource'] = 'Psychicschool_AutomateWoo_Rule_Booking_Resource';
    return $includes;
}

/**
 * AJAX: Search booking resources for the rule select (wc-product-search style).
 * Requires at least 3 characters. Returns JSON object id => text for select2.
 */
add_action( 'wp_ajax_psychicschool_json_search_booking_resources', 'psychicschool_ajax_json_search_booking_resources' );
function psychicschool_ajax_json_search_booking_resources() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_send_json( [] );
    }

    $term = isset( $_REQUEST['term'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['term'] ) ) : '';
    if ( strlen( $term ) < 3 ) {
        wp_send_json( [] );
    }

    $results = [];

    if ( post_type_exists( 'bookable_resource' ) ) {
        $posts = get_posts( [
            'post_type'      => 'bookable_resource',
            'posts_per_page' => 30,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
            's'              => $term,
        ] );
        foreach ( $posts as $post ) {
            $results[ (string) $post->ID ] = $post->post_title;
        }
    }

    wp_send_json( $results );
}