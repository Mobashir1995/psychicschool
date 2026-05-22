<?php
/**
 * Google reCAPTCHA v2 (checkbox) for WordPress and WooCommerce login flows.
 *
 * @package Psychicschool_Functionalities
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Login / registration reCAPTCHA handler.
 */
final class PS_Login_Recaptcha {

	public const OPTION_NAME       = 'ps_login_recaptcha';
	public const LEGACY_SITE_KEY   = 'login_nocaptcha_key';
	public const LEGACY_SECRET_KEY = 'login_nocaptcha_secret';
	public const SCRIPT_HANDLE     = 'ps-login-recaptcha-google';
	public const KEY_LENGTH        = 40;

	private static ?self $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		if ( ! $this->is_configured() ) {
			add_action( 'admin_notices', [ $this, 'render_configuration_notice' ] );
			return;
		}

		add_action( 'login_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'login_form', [ $this, 'render_widget' ] );
		add_action( 'register_form', [ $this, 'render_widget' ], 99 );
		add_action( 'lostpassword_form', [ $this, 'render_widget' ] );

		add_filter( 'authenticate', [ $this, 'validate_login' ], 30, 3 );
		add_filter( 'registration_errors', [ $this, 'validate_registration' ], 10, 3 );
		add_action( 'lostpassword_post', [ $this, 'validate_lost_password' ], 10, 1 );
		add_filter( 'shake_error_codes', [ $this, 'add_shake_error_codes' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'maybe_enqueue_frontend_assets' ], 25 );

		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_login_form', [ $this, 'render_widget' ] );
			add_action( 'woocommerce_register_form', [ $this, 'render_widget' ] );
			add_action( 'woocommerce_lostpassword_form', [ $this, 'render_widget' ] );
			add_filter( 'woocommerce_process_login_errors', [ $this, 'validate_woocommerce_login' ], 10, 3 );
			add_action( 'woocommerce_register_post', [ $this, 'validate_woocommerce_registration' ], 10, 3 );
		}
	}

	/**
	 * Whether site and secret keys are stored and valid length.
	 */
	public function is_configured(): bool {
		$site_key   = $this->get_site_key();
		$secret_key = $this->get_secret_key();

		return $this->is_valid_key( $site_key ) && $this->is_valid_key( $secret_key );
	}

	/**
	 * Stored settings array from Fieldmanager.
	 *
	 * @return array{site_key?: string, secret_key?: string}
	 */
	public static function get_settings(): array {
		$settings = get_option( self::OPTION_NAME, [] );
		return is_array( $settings ) ? $settings : [];
	}

	/**
	 * Copy legacy Login NoCaptcha options into the Fieldmanager option once.
	 */
	public static function maybe_migrate_legacy_options(): void {
		if ( false !== get_option( self::OPTION_NAME, false ) ) {
			return;
		}

		$site_key   = (string) get_option( self::LEGACY_SITE_KEY, '' );
		$secret_key = (string) get_option( self::LEGACY_SECRET_KEY, '' );

		if ( '' === $site_key && '' === $secret_key ) {
			add_option( self::OPTION_NAME, [ 'site_key' => '', 'secret_key' => '' ], '', false );
			return;
		}

		add_option(
			self::OPTION_NAME,
			[
				'site_key'   => $site_key,
				'secret_key' => $secret_key,
			],
			'',
			false
		);
	}

	public function get_site_key(): string {
		$settings = self::get_settings();
		if ( ! empty( $settings['site_key'] ) ) {
			return (string) $settings['site_key'];
		}

		return (string) get_option( self::LEGACY_SITE_KEY, '' );
	}

	public function get_secret_key(): string {
		$settings = self::get_settings();
		if ( ! empty( $settings['secret_key'] ) ) {
			return (string) $settings['secret_key'];
		}

		return (string) get_option( self::LEGACY_SECRET_KEY, '' );
	}

	/**
	 * @param mixed $value Submitted site key.
	 */
	public static function sanitize_site_key( $value ): string {
		return self::sanitize_key_value( $value, 'site_key' );
	}

	/**
	 * @param mixed $value Submitted secret key.
	 */
	public static function sanitize_secret_key( $value ): string {
		return self::sanitize_key_value( $value, 'secret_key' );
	}

	/**
	 * @param mixed  $value     Submitted value.
	 * @param string $field_key site_key|secret_key.
	 */
	private static function sanitize_key_value( $value, string $field_key ): string {
		$value = sanitize_text_field( (string) $value );
		if ( '' === $value ) {
			return '';
		}

		if ( self::instance()->is_valid_key( $value ) ) {
			return $value;
		}

		$settings = self::get_settings();
		if ( ! empty( $settings[ $field_key ] ) ) {
			return (string) $settings[ $field_key ];
		}

		$legacy_option = 'site_key' === $field_key ? self::LEGACY_SITE_KEY : self::LEGACY_SECRET_KEY;
		return (string) get_option( $legacy_option, '' );
	}

	/**
	 * @param string $key reCAPTCHA key.
	 */
	public function is_valid_key( string $key ): bool {
		return strlen( $key ) === self::KEY_LENGTH && (bool) preg_match( '/^[a-zA-Z0-9_-]+$/', $key );
	}

	public function render_configuration_notice(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( class_exists( 'PS_Login_Recaptcha_Settings' ) && PS_Login_Recaptcha_Settings::is_settings_screen() ) {
			return;
		}

		$url = admin_url( 'admin.php?page=' . PS_Login_Recaptcha_Settings::PAGE_SLUG );
		echo '<div class="notice notice-warning"><p>';
		printf(
			/* translators: %s: settings page URL */
			esc_html__( 'Login reCAPTCHA is not configured. %s', 'psychicschool-functionalities' ),
			'<a href="' . esc_url( $url ) . '">' . esc_html__( 'Add your Google reCAPTCHA keys', 'psychicschool-functionalities' ) . '</a>'
		);
		echo '</p></div>';
	}

	/**
	 * Enqueue Google API on wp-login and admin (settings preview).
	 */
	public function enqueue_assets(): void {
		$this->register_google_script();
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/**
	 * Front-end: logged-out visitors (Kadence login drawer, WC account pages, etc.).
	 */
	public function maybe_enqueue_frontend_assets(): void {
		if ( is_user_logged_in() || is_admin() ) {
			return;
		}

		$this->register_google_script();
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	private function register_google_script(): void {
		if ( wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {
			return;
		}

		wp_register_script(
			self::SCRIPT_HANDLE,
			'https://www.google.com/recaptcha/api.js?onload=psRecaptchaSubmitDisable',
			[],
			null,
			true
		);

		wp_add_inline_script(
			self::SCRIPT_HANDLE,
			$this->get_button_toggle_script(),
			'before'
		);
	}

	/**
	 * Disable submit until the user completes the captcha (WP + WooCommerce forms).
	 */
	private function get_button_toggle_script(): string {
		$woo_selectors = '';
		if ( class_exists( 'WooCommerce' ) ) {
			$woo_selectors = json_encode(
				[
					'.woocommerce-form-login button',
					'.woocommerce-form-register button',
					'.woocommerce-ResetPassword button',
				],
				JSON_UNESCAPED_SLASHES
			);
		}

		return <<<JS
window.psRecaptchaSubmitEnable = function () {
	var button = document.getElementById('wp-submit');
	if (!button) {
		button = document.getElementById('submit');
	}
	if (button) {
		button.removeAttribute('disabled');
	}
	var wooButtons = {$woo_selectors};
	if (wooButtons.length && typeof jQuery !== 'undefined') {
		jQuery.each(wooButtons, function (i, btn) {
			jQuery(btn).removeAttr('disabled');
		});
	}
};
window.psRecaptchaSubmitDisable = function () {
	var button = document.getElementById('wp-submit');
	if (!button && !document.body.classList.contains('wp-admin')) {
		button = document.getElementById('submit');
	}
	if (button) {
		button.setAttribute('disabled', 'disabled');
	}
	var wooButtons = {$woo_selectors};
	if (wooButtons.length && typeof jQuery !== 'undefined') {
		jQuery.each(wooButtons, function (i, btn) {
			jQuery(btn).attr('disabled', 'disabled');
		});
	}
};
JS;
	}

	/**
	 * Output reCAPTCHA widget markup.
	 */
	public function render_widget(): void {
		$site_key = $this->get_site_key();
		if ( ! $this->is_valid_key( $site_key ) ) {
			return;
		}

		printf(
			'<div class="g-recaptcha ps-login-recaptcha" data-sitekey="%s" data-callback="psRecaptchaSubmitEnable" data-expired-callback="psRecaptchaSubmitDisable"></div>',
			esc_attr( $site_key )
		);
	}

	/**
	 * @param WP_User|WP_Error|null $user     User or error.
	 * @param string                $username Username.
	 * @param string                $password Password.
	 * @return WP_User|WP_Error|null
	 */
	public function validate_login( $user, $username, $password ) {
		if ( isset( $_POST['woocommerce-login-nonce'] ) ) {
			return $user;
		}

		if ( ! $this->should_validate_request() ) {
			return $user;
		}

		return $this->verify_or_error( $user );
	}

	/**
	 * @param WP_Error $validation_error Validation errors.
	 * @param string   $username         Username.
	 * @param string   $password         Password.
	 * @return WP_Error
	 */
	public function validate_woocommerce_login( $validation_error, $username, $password ) {
		unset( $username, $password );

		if ( ! $this->should_validate_request() ) {
			return $validation_error;
		}

		return $this->verify_or_error( $validation_error );
	}

	/**
	 * @param WP_Error $errors Registration errors.
	 * @param string   $sanitized_user_login Sanitized login.
	 * @param string   $user_email           Email.
	 * @return WP_Error
	 */
	public function validate_registration( $errors, $sanitized_user_login, $user_email ) {
		if ( ! $this->should_validate_request() ) {
			return $errors;
		}

		return $this->verify_or_error( $errors );
	}

	/**
	 * @param WP_Error $errors Registration errors.
	 */
	public function validate_woocommerce_registration( $username, $email, $errors ): void {
		if ( ! $this->should_validate_request() ) {
			return;
		}

		$this->verify_or_error( $errors );
	}

	/**
	 * @param WP_Error        $errors     Errors object.
	 * @param WP_User|WP_Error|null $user_data User data (optional).
	 */
	public function validate_lost_password( $errors, $user_data = null ): void {
		unset( $user_data );

		if ( ! is_wp_error( $errors ) || ! $this->should_validate_request() ) {
			return;
		}

		$this->verify_or_error( $errors );
	}

	/**
	 * Only validate POST requests from wp-login or WooCommerce auth forms.
	 */
	private function should_validate_request(): bool {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return false;
		}

		if ( $this->is_woocommerce_auth_request() ) {
			return true;
		}

		return isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'];
	}

	private function is_woocommerce_auth_request(): bool {
		return isset( $_POST['woocommerce-login-nonce'] )
			|| isset( $_POST['woocommerce-lost-password-nonce'] )
			|| isset( $_POST['woocommerce-register-nonce'] );
	}

	/**
	 * @param WP_User|WP_Error|null $context User or error object.
	 * @return WP_User|WP_Error|null
	 */
	private function verify_or_error( $context ) {
		$response_token = isset( $_POST['g-recaptcha-response'] )
			? sanitize_text_field( wp_unslash( (string) $_POST['g-recaptcha-response'] ) )
			: '';

		if ( '' === $response_token ) {
			return $this->add_captcha_error( $context, 'missing' );
		}

		$verified = $this->verify_token( $response_token );

		if ( true === $verified ) {
			return $context;
		}

		if ( 'misconfigured' === $verified ) {
			// Invalid secret — do not lock admins out; allow request through.
			return $context;
		}

		return $this->add_captcha_error( $context, 'invalid' );
	}

	/**
	 * @param string $token User response token.
	 * @return bool|string True on success, 'invalid', or 'misconfigured'.
	 */
	private function verify_token( string $token ) {
		$result = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			[
				'timeout' => 10,
				'body'    => [
					'secret'   => $this->get_secret_key(),
					'response' => $token,
					'remoteip' => $this->get_client_ip(),
				],
			]
		);

		if ( is_wp_error( $result ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'PS_Login_Recaptcha: ' . $result->get_error_message() );
			}
			return 'invalid';
		}

		$body = json_decode( wp_remote_retrieve_body( $result ), true );
		if ( ! is_array( $body ) ) {
			return 'invalid';
		}

		if ( ! empty( $body['success'] ) ) {
			return true;
		}

		$error_codes = isset( $body['error-codes'] ) && is_array( $body['error-codes'] )
			? $body['error-codes']
			: [];

		if ( array_intersect( [ 'missing-input-secret', 'invalid-input-secret' ], $error_codes ) ) {
			return 'misconfigured';
		}

		return 'invalid';
	}

	/**
	 * Client IP for Google verification (REMOTE_ADDR only — avoids spoofable forwarded headers).
	 */
	private function get_client_ip(): string {
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( (string) $_SERVER['REMOTE_ADDR'] ) );
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}
		return '';
	}

	/**
	 * @param WP_User|WP_Error|null $context Context object.
	 * @param string                $type    missing|invalid.
	 * @return WP_Error
	 */
	private function add_captcha_error( $context, string $type ) {
		$code    = 'missing' === $type ? 'no_captcha' : 'invalid_captcha';
		$message = '<strong>' . esc_html__( 'ERROR', 'psychicschool-functionalities' ) . ':</strong> '
			. esc_html__( 'Please complete the reCAPTCHA verification.', 'psychicschool-functionalities' );

		if ( is_wp_error( $context ) ) {
			$context->add( $code, $message );
			return $context;
		}

		return new WP_Error( 'authentication_failed', $message );
	}

	/**
	 * @param string[] $shake_error_codes Error codes that trigger shake animation.
	 * @return string[]
	 */
	public function add_shake_error_codes( array $shake_error_codes ): array {
		$shake_error_codes[] = 'no_captcha';
		$shake_error_codes[] = 'invalid_captcha';
		return $shake_error_codes;
	}
}

add_action(
	'plugins_loaded',
	static function (): void {
		PS_Login_Recaptcha::instance();
	},
	25
);
