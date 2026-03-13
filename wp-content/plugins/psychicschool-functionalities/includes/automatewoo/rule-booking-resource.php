<?php
/**
 * AutomateWoo Rule: Booking - Resource
 *
 * Searchable select (like WooCommerce product search): no options by default,
 * user types at least 3 characters to search booking resources.
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Rule class for filtering by the booking's assigned resource.
 */
class Psychicschool_AutomateWoo_Rule_Booking_Resource extends AutomateWoo\Rules\Searchable_Select_Rule_Abstract {

    /**
     * Data item provided by the trigger (booking workflows).
     *
     * @var string
     */
    public $data_item = 'booking';

    /**
     * Use WooCommerce product search UI (3-letter minimum, AJAX search).
     *
     * @var string
     */
    public $class = 'wc-product-search';

    /**
     * Init the rule.
     */
    public function init() {
        parent::init();
        $this->title       = __( 'Booking - Resource', 'psychicschool-functionalities' );
        $this->placeholder = __( 'Search for a resource…', 'psychicschool-functionalities' );
        // Single select: use "is" / "is not" instead of includes/not_includes.
        $this->compare_types = $this->get_is_or_not_compare_types();
    }

    /**
     * AJAX action name for searching booking resources.
     *
     * @return string
     */
    public function get_search_ajax_action() {
        return 'psychicschool_json_search_booking_resources';
    }

    /**
     * Display the resource name for a saved value (resource ID).
     *
     * @param string $value Resource ID.
     * @return string
     */
    public function get_object_display_value( $value ) {
        $value = (string) $value;
        if ( empty( $value ) ) {
            return '';
        }
        $post = get_post( (int) $value );
        return $post && $post->post_type === 'bookable_resource'
            ? $post->post_title
            : $value;
    }

    /**
     * Validate the rule.
     *
     * @param \WC_Booking $booking      The booking from the workflow data layer.
     * @param string     $compare_type Compare type: 'is' or 'is_not'.
     * @param string|array $value      Selected resource ID (or empty for no resource).
     * @return bool
     */
    public function validate( $booking, $compare_type, $value ) {
        if ( ! is_a( $booking, 'WC_Booking' ) || ! method_exists( $booking, 'get_resource_id' ) ) {
            return false;
        }

        $resource_id = (string) $booking->get_resource_id();
        return $this->validate_select( $resource_id, $compare_type, $value );
    }
}
