<?php

namespace PS\STM_Features;

function stm_subscribe()
{
	$json = array();
	$email = urldecode(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$json['error'] = __('Enter a valid email', 'stm-post-type');
		echo json_encode($json);
		exit;
	} else {
		require_once(PSYCHICSCHOOL_FUNCTIONALITIES_DIR . "includes/stm-features/mailchimp/Handling.class.php");
		Handling::handling_request_with_confirmation($email, NULL);
	}
}

add_action('wp_ajax_stm_subscribe', __NAMESPACE__ . '\stm_subscribe');
add_action('wp_ajax_nopriv_stm_subscribe', __NAMESPACE__ . '\stm_subscribe');
