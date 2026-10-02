<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PS_Classroom_Exclusion_Settings {
	public const OPTION_NAME = 'ps_classroom_exclusions';
	public const PAGE_SLUG   = 'ps_classroom_exclusions';
	public const MENU_SLUG   = 'psychicschool-settings';

	public function __construct() {
		// Register submenu in Fieldmanager registry before Fieldmanager adds submenus at admin_menu priority 15.
		add_action( 'admin_menu', [ $this, 'register_submenu_page' ] );
		add_action( 'admin_init', [ $this, 'maybe_bootstrap_submenu_context' ], 5 );
		add_action( 'fm_submenu_' . self::PAGE_SLUG, [ $this, 'register_fields' ] );
	}

	public function register_submenu_page(): void {
		if ( ! function_exists( 'fm_register_submenu_page' ) ) {
			return;
		}

		fm_register_submenu_page(
			self::PAGE_SLUG,
			self::MENU_SLUG,
			__( 'Classroom Exclusions', 'psychicschool-functionalities' )
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

		$field = new Fieldmanager_Group(
			[
				'label'    => __( 'Classroom Exclusion Rules', 'psychicschool-functionalities' ),
				'description' => __( 'Add membership plan slugs to hide them from the My Account > Classroom (Members Area) page.', 'psychicschool-functionalities' ),
				'name'     => self::OPTION_NAME,
				'children' => [
					'excluded_memberships'   => new Fieldmanager_TextField(
						[
							'label'      => __( 'Excluded Membership Slug', 'psychicschool-functionalities' ),
							'add_more_label' => __( 'Add Membership Slug', 'psychicschool-functionalities' ),
							'limit'      => 0,
							'sortable'   => true,
							'extra_elements' => 0,
							'description' => __( 'Enter WooCommerce Membership Plan Slug (slug only). Example: energy-check-access', 'psychicschool-functionalities' ),
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
		new PS_Classroom_Exclusion_Settings();
	},
	30
);
