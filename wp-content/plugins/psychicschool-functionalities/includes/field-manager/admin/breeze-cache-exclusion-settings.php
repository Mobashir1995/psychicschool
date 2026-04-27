<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PS_Breeze_Cache_Exclusion_Settings {
	public const OPTION_NAME = 'ps_breeze_cache_exclusions';
	public const PAGE_SLUG   = 'ps_breeze_cache_exclusions';
	public const MENU_SLUG   = 'psychicschool-settings';

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 10 );
		// Register submenu in Fieldmanager registry before Fieldmanager adds submenus at admin_menu priority 15.
		add_action( 'admin_menu', [ $this, 'register_submenu_page' ] );
		add_action( 'admin_init', [ $this, 'maybe_bootstrap_submenu_context' ], 5 );
		add_action( 'admin_init', [ $this, 'maybe_redirect_main_menu' ] );
		add_action( 'fm_submenu_' . self::PAGE_SLUG, [ $this, 'register_fields' ] );
	}

	public function register_admin_menu(): void {
		add_menu_page(
			__( 'Psychicschool Settings', 'psychicschool-functionalities' ),
			__( 'Psychicschool Settings', 'psychicschool-functionalities' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'render_settings_landing_page' ],
			'dashicons-admin-generic',
			58
		);
	}

	public function register_submenu_page(): void {
		if ( ! function_exists( 'fm_register_submenu_page' ) ) {
			return;
		}

		fm_register_submenu_page(
			self::PAGE_SLUG,
			self::MENU_SLUG,
			__( 'Breeze Cache Exclusions', 'psychicschool-functionalities' )
		);
	}

	public function maybe_redirect_main_menu(): void {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( self::MENU_SLUG !== $page ) {
			return;
		}

		wp_safe_redirect(
			add_query_arg(
				[
					'page' => self::PAGE_SLUG,
				],
				admin_url( 'admin.php' )
			)
		);
		exit;
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

	public function render_settings_landing_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Psychicschool Settings', 'psychicschool-functionalities' ) . '</h1>';
		echo '<p>' . esc_html__( 'Use the submenu items to configure plugin settings.', 'psychicschool-functionalities' ) . '</p>';
		echo '</div>';
	}

	public function register_fields(): void {
		if ( ! class_exists( 'Fieldmanager_Group' ) || ! class_exists( 'Fieldmanager_Autocomplete' ) ) {
			return;
		}

		$field = new Fieldmanager_Group(
			[
				'label'    => __( 'Breeze Cache Exclusion Rules', 'psychicschool-functionalities' ),
				'description' => __( 'Add products or product categories below to bypass Breeze cache. Use autocomplete to search and select items.', 'psychicschool-functionalities' ),
				'name'     => self::OPTION_NAME,
				'children' => [
					'excluded_products'   => new Fieldmanager_Autocomplete(
						[
							'label'      => __( 'Excluded Products', 'psychicschool-functionalities' ),
							'add_more_label' => __( 'Add Product', 'psychicschool-functionalities' ),
							'limit'      => 0,
							'sortable'   => true,
							'extra_elements' => 0,
							'datasource' => new Fieldmanager_Datasource_Post(
								[
									'use_ajax'   => true,
									'query_args' => [
										'post_type'      => 'product',
										'post_status'    => 'publish',
										'posts_per_page' => 20,
									],
								]
							),
							'description' => __( 'Select product(s) to always bypass Breeze cache.', 'psychicschool-functionalities' ),
						]
					),
					'excluded_categories' => new Fieldmanager_Autocomplete(
						[
							'label'      => __( 'Excluded Product Categories', 'psychicschool-functionalities' ),
							'add_more_label' => __( 'Add Product Category', 'psychicschool-functionalities' ),
							'limit'      => 0,
							'sortable'   => true,
							'extra_elements' => 0,
							'datasource' => new Fieldmanager_Datasource_Term(
								[
									'use_ajax'   => true,
									'taxonomy'   => 'product_cat',
									'taxonomy_args' => [
										'hide_empty' => false,
										'number'     => 20,
									],
								]
							),
							'description' => __( 'Select product category(s) to always bypass Breeze cache for products assigned to them.', 'psychicschool-functionalities' ),
						]
					),
				],
			]
		);

		$field->activate_submenu_page();
	}
}

add_action(
	'plugins_loaded',
	static function (): void {
		new PS_Breeze_Cache_Exclusion_Settings();
	},
	30
);

