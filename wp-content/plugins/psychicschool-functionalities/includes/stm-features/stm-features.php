<?php
namespace PS\STM_Features;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Post Type Class
require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/stm-features/post_type.class.php';

// 2. Extracted Post Types and Meta boxes
require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/stm-features/post_types.php';

// 3. MailChimp Widget
require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/stm-features/widget-mailchimp.php';

// 4. MailChimp AJAX Endpoints
require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/stm-features/ajax-mailchimp.php';

// 5. Shared helper function for the migrated components.
if ( ! function_exists( 'stm_post_type_filtered_output' ) ) {
	function stm_post_type_filtered_output( $data ) {
		return apply_filters( 'stm_post_type_filtered_output', $data );
	}
}
