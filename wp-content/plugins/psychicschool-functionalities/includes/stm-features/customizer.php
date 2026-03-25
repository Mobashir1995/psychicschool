<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
namespace PS\STM_Features;

add_action( 'customize_register', __NAMESPACE__ . '\ps_migrated_customize_register' );
function ps_migrated_customize_register( $wp_customize ) {
	
	// Add our new panel and section
	$wp_customize->add_section( 'ps_mailchimp_settings', array(
		'title'       => __( 'MailChimp Settings (Migrated)', 'psychicschool-functionalities' ),
		'description' => __( 'Configure the MailChimp API Key and List ID for the newsletter widgets.', 'psychicschool-functionalities' ),
		'priority'    => 120,
	) );
	
	// API Key
	$wp_customize->add_setting( 'ps_mailchimp_api_key', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ps_mailchimp_api_key', array(
		'label'    => __( 'MailChimp API Key', 'psychicschool-functionalities' ),
		'section'  => 'ps_mailchimp_settings',
		'type'     => 'text',
	) );
	
	// List ID
	$wp_customize->add_setting( 'ps_mailchimp_list_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ps_mailchimp_list_id', array(
		'label'    => __( 'MailChimp Audience List ID', 'psychicschool-functionalities' ),
		'section'  => 'ps_mailchimp_settings',
		'type'     => 'text',
	) );
}
