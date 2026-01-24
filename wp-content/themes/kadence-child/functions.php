<?php

require_once get_stylesheet_directory() . '/inc/vc-addons/index.php';
add_action( 'ppw_sitewide_above_password_form_container', 'add_sitewide_custom_content' );

function add_sitewide_custom_content() {
    echo '<h2>We\'re performing scheduled maintenance to improve your experience.</h2>';
    echo '<p>The site will be back online shortly — thank you for your patience.</p>';
}

function kadence_child_create_unique_id( $atts ) {
	return 'module__' . md5( serialize( $atts ) ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
}