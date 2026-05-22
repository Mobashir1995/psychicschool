<?php
/**
 * Login reCAPTCHA settings (Fieldmanager submenu).
 *
 * @package Psychicschool_Functionalities
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers reCAPTCHA keys under Psychicschool Settings via Fieldmanager.
 */
final class PS_Login_Recaptcha_Settings {

	public const PAGE_SLUG = 'ps_login_recaptcha';
	public const MENU_SLUG = 'psychicschool-settings';

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_submenu_page' ] );
		add_action( 'admin_init', [ $this, 'maybe_bootstrap_submenu_context' ], 5 );
		add_action( 'fm_submenu_' . self::PAGE_SLUG, [ $this, 'register_fields' ] );
		add_action( 'admin_notices', [ $this, 'maybe_warn_old_plugin_active' ] );
		add_action( 'admin_footer', [ $this, 'maybe_render_preview' ] );
	}

	public static function is_settings_screen(): bool {
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		return self::PAGE_SLUG === $page;
	}

	public function register_submenu_page(): void {
		if ( ! function_exists( 'fm_register_submenu_page' ) ) {
			return;
		}

		fm_register_submenu_page(
			self::PAGE_SLUG,
			self::MENU_SLUG,
			__( 'Login reCAPTCHA', 'psychicschool-functionalities' )
		);
	}

	public function maybe_bootstrap_submenu_context(): void {
		if ( ! is_admin() ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( self::PAGE_SLUG !== $page ) {
			return;
		}

		$this->register_fields();
	}

	public function register_fields(): void {
		if ( ! class_exists( 'Fieldmanager_Group' ) || ! class_exists( 'Fieldmanager_TextField' ) ) {
			return;
		}

		$description = sprintf(
			/* translators: %s: Google reCAPTCHA admin URL */
			__( 'Use Google reCAPTCHA v2 (“I\'m not a robot” checkbox). Create keys at %s.', 'psychicschool-functionalities' ),
			'<a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Google reCAPTCHA admin', 'psychicschool-functionalities' ) . '</a>'
		);

		$field = new Fieldmanager_Group(
			[
				'label'       => __( 'Login reCAPTCHA', 'psychicschool-functionalities' ),
				'description' => $description,
				'name'        => PS_Login_Recaptcha::OPTION_NAME,
				'children'    => [
					'site_key'   => new Fieldmanager_TextField(
						[
							'label'       => __( 'Site key', 'psychicschool-functionalities' ),
							'description' => __( 'Public site key (40 characters).', 'psychicschool-functionalities' ),
							'sanitize'    => [ PS_Login_Recaptcha::class, 'sanitize_site_key' ],
							'attributes'  => [
								'autocomplete' => 'off',
								'class'        => 'regular-text',
							],
						]
					),
					'secret_key' => new Fieldmanager_TextField(
						[
							'label'       => __( 'Secret key', 'psychicschool-functionalities' ),
							'description' => __( 'Private secret key (40 characters).', 'psychicschool-functionalities' ),
							'sanitize'    => [ PS_Login_Recaptcha::class, 'sanitize_secret_key' ],
							'attributes'  => [
								'type'         => 'password',
								'autocomplete' => 'off',
								'class'        => 'regular-text',
							],
						]
					),
				],
			]
		);

		$field->activate_submenu_page();
	}

	public function maybe_render_preview(): void {
		if ( ! self::is_settings_screen() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$recaptcha = PS_Login_Recaptcha::instance();
		if ( ! $recaptcha->is_configured() ) {
			return;
		}

		echo '<div class="wrap" style="margin-top:1em;">';
		echo '<h2>' . esc_html__( 'Preview', 'psychicschool-functionalities' ) . '</h2>';
		$recaptcha->enqueue_assets();
		$recaptcha->render_widget();
		echo '</div>';
	}

	public function maybe_warn_old_plugin_active(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) || ! is_plugin_active( 'login-recaptcha/login-nocaptcha.php' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>';
		esc_html_e( 'The abandoned “Login NoCaptcha reCAPTCHA” plugin is still active. Deactivate it to avoid duplicate captchas and conflicts.', 'psychicschool-functionalities' );
		echo '</p></div>';
	}
}

add_action(
	'plugins_loaded',
	static function (): void {
		PS_Login_Recaptcha::maybe_migrate_legacy_options();
		if ( is_admin() ) {
			new PS_Login_Recaptcha_Settings();
		}
	},
	20
);
