<?php
add_filter('woocommerce_account_menu_items', 'filter_function_name_5824', 10, 2);
function filter_function_name_5824($items, $endpoints)
{
	// filter...
//	unset($items['subscriptions']);
//	unset($items['edit-address']);
//	echo "123";
//	echo "<pre>";
//		print_r($endpoints);
//		print_r($items);
//	echo "</pre>";
//	die;
	return $items;
}

add_action('fue_before_variable_replacements', 'vi_register_variable_replacements', 12, 4);
function vi_register_variable_replacements($var, $email_data, $email, $queue_item)
{

	if ($email->type != 'wc_bookings') {
		return;
	}
	$variables = array(
		'booking_zone' => ''
	);
	if (isset($email_data['test']) && $email_data['test']) {
		$variables['booking_zone'] = 'THIS IS A TEST LOCATION: America/Los Angeles';
		// booking data
		$meta = maybe_unserialize($queue_item->meta);
		$booking_id = !empty($meta['booking_id']) ? $meta['booking_id'] : 0;
		if (!empty($booking_id)) {
			$booking_zone = get_post_meta($booking_id, '_local_timezone', true);
			if (!empty($booking_zone)) {
				$variables['booking_zone'] = $booking_zone;
			}
		}
	} else {
		if (!empty($queue_item->order_id)) {
			// booking data
			$meta = maybe_unserialize($queue_item->meta);
			$booking_id = !empty($meta['booking_id']) ? $meta['booking_id'] : 0;
			if (!empty($booking_id)) {
				$booking_zone = get_post_meta($booking_id, '_local_timezone', true);
				if (!empty($booking_zone)) {
					$variables['booking_zone'] = $booking_zone;
				}
			}
		}
	}
	$var->register($variables);
}

add_action('fue_email_variables_list', 'vi_email_variables_list', 11);
function vi_email_variables_list($email)
{
	global $woocommerce;
	if ($email->type != 'wc_bookings') {
		return;
	}
	?>
    <li class="var hideable var_wc_bookings"><strong>{booking_zone}</strong> <img class="help_tip"
                                                                                  title="<?php esc_attr_e('The zone in which the booking was made.', 'follow_up_emails'); ?>"
                                                                                  src="<?php echo esc_url(FUE_TEMPLATES_URL); ?>/images/help.png"
                                                                                  width="16" height="16"/></li>
	<?php
}

add_action('save_post', 'vi_save_teacher_metabox', 1, 2);
function vi_save_teacher_metabox($post_id, $post)
{

	// Don't wanna save this now, right?
	if (empty($post_id) || empty($post)) {
		return;
	}
	if ($post->post_type !== 'follow_up_email') {
		return;
	}
	// Dont' save meta boxes for revisions or autosaves
	if (defined('DOING_AUTOSAVE') || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
		return;
	}
	// Check the nonce.
	if (empty($_POST['fue_meta_nonce']) || !wp_verify_nonce(wp_unslash($_POST['fue_meta_nonce']), 'fue_save_data')) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return;
	}
	// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
	if (empty($_POST['post_ID']) || $_POST['post_ID'] != $post_id) {
		return;
	}
	// Check user has permission to edit.
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	// We do want to save? Ok!
	$key = 'vi_add_teacher';
	$value = $_POST["meta"]["vi_add_teacher"];
	if (get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
		update_post_meta($post->ID, $key, $value);
	} else { // If the custom field doesn't have a value
		add_post_meta($post->ID, $key, $value);
	}
	if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
}

global $wccs;
remove_action('wp_footer', array($GLOBALS['WCCS'], 'wccs_add_sticky_callback'));
add_action('wp_footer', 'vi_wccs_add_sticky_callback');
function vi_wccs_add_sticky_callback()
{
	if (get_option('wccs_sticky_switcher', 0) && '1' != get_option('wccs_zp_toggle')) {
		$default_currency = $GLOBALS['WCCS']->wccs_get_default_currency();
		$default_label = wccs_get_currency_label($default_currency);
		// $default_symbol = get_woocommerce_currency_symbol($default_currency);
		$currencies = $GLOBALS['WCCS']->wccs_get_currencies();
		$currency = $GLOBALS['WCCS']->wccs_get_currency();
		// $show_currency = get_option('wccs_show_currency', 1);
		$show_flag = get_option('wccs_show_flag', 1);
		if (is_shop() || is_product_category() || is_product() || is_cart() || is_checkout() || is_front_page() || is_page(1895)) {
			if (count($currencies)) {
				wp_enqueue_style('wccs_flags_style', WCCS_PLUGIN_URL . 'assets/lib/flag-icon/flag-icon.css', '', '1.0');
				wp_enqueue_style('wccs_slick_css', WCCS_PLUGIN_URL . 'assets/frontend/css/wccs_slick.css', '', '1.0');
				wp_enqueue_style('wccs_sticky_css', WCCS_PLUGIN_URL . 'assets/frontend/themes/sticky/theme-05.css', '', '1.0&t=' . gmdate('dmYHis'));
				wp_enqueue_script('wccs_slick_script', WCCS_PLUGIN_URL . 'assets/frontend/js/wccs_slick.min.js', array('jquery'), '1.0');
				wp_enqueue_script('wccs_sticky_script', WCCS_PLUGIN_URL . 'assets/frontend/themes/sticky/sticky.js', array('jquery'), '1.0&t=' . gmdate('dmYHis'));
				?>

                <div id="wcc-sticky-list-wrapper" class="<?php if (count($currencies) > 4) { ?>

					wcc-with-more<?php } ?>

					<?php if (get_option('wccs_sticky_position', 'right') == 'left') { ?>

					wcc-sticky-left<?php } ?>">

                    <div id="wccs_sticky_container" class="noMoreTop">

                        <a href="#" id="wccs_sticky_up"></a>

                        <ul class="wcc-sticky-list">

                            <li class="d-flex sticky-def <?php if (!$currency) { ?>

								crnt<?php } ?>" data-code="<?php echo esc_attr($default_currency); ?>">

                                <span class="wcc-name"><?php echo esc_html($default_currency); ?></span>

								<?php if (!empty($GLOBALS['WCCS']->wccs_get_default_currency_flag())) : ?>

                                    <span class="wcc-flag <?php if ($show_flag && $GLOBALS['WCCS']->wccs_get_default_currency_flag()) { ?>

										flag-icon flag-icon-<?php echo esc_attr($GLOBALS['WCCS']->wccs_get_default_currency_flag());
									} ?>"></span>

								<?php else : ?>

                                    <span class="wcc-flag"><?php echo esc_html__('Def', 'wccs'); ?></span>

								<?php endif; ?>

                            </li>

							<?php
							foreach ($currencies as $code => $info) {

								?>

                                <li class="d-flex <?php if ($code == $currency) { ?>

								crnt<?php } ?>" data-code="<?php echo esc_attr($code); ?>">

                                    <span class="wcc-name"><?php echo esc_html($code); ?></span>

                                    <span class="wcc-flag <?php if ($show_flag && $info['flag']) { ?>

									flag-icon flag-icon-<?php echo esc_attr($info['flag']);
									} ?>"></span>

                                </li>

								<?php

							}
							?>

                        </ul>

                        <a href="#" id="wccs_sticky_down"></a>

                    </div>

                </div>

                <form class="wccs_sticky_form" method="post" action="" style="display: none;">

                    <input type="hidden" name="wcc_switcher" class="wcc_switcher" value="">

                </form>
				<?php
			}
		}
	}
}

function my_filter_plugin_updates($value)
{
	if (isset($value->response['woocommerce-follow-up-emails/woocommerce-follow-up-emails.php'])) {
		unset($value->response['woocommerce-follow-up-emails/woocommerce-follow-up-emails.php']);
	}
	return $value;
}

add_filter('site_transient_update_plugins', 'my_filter_plugin_updates');
?>