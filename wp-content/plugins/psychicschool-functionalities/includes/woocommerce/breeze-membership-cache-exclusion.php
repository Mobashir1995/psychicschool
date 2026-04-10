<?php
declare(strict_types=1);

/**
 * Breeze cache exclusion for WooCommerce Memberships restricted content.
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PS_Breeze_Membership_Cache_Exclusion {
	private const META_BOX_IDS = [
		'wc-memberships-post-memberships-data',
		'wc-memberships-membership-plan-data',
	];

	private const PRODUCT_POST_TYPES = [
		'product',
		'product_variation',
	];

	public function __construct() {
		add_action( 'template_redirect', $this->handle_template_redirect(...), 1 );
		add_action( 'wc_memberships_save_meta_box', $this->handle_memberships_save_meta_box(...), 10, 4 );
	}

	private function handle_template_redirect(): void {
		if ( ! $this->memberships_is_active() ) {
			return;
		}

		if ( ! $this->breeze_is_active() ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post_id = (int) get_queried_object_id();
		if ( $post_id < 1 ) {
			return;
		}

		if ( ! $this->is_restricted_post( $post_id ) ) {
			return;
		}

		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
	}

	/**
	 * @param array<string, mixed> $posted_data
	 */
	private function handle_memberships_save_meta_box( array $_posted_data, string $meta_box_id, int $post_id, \WP_Post $post ): void {
		if ( ! $this->memberships_is_active() ) {
			return;
		}

		if ( ! $this->breeze_is_active() ) {
			return;
		}

		if ( ! $this->is_supported_meta_box( $meta_box_id ) ) {
			return;
		}

		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$purged_post_ids = [];

		if ( 'wc_membership_plan' !== $post->post_type ) {
			$this->purge_single_post_cache( $post_id, $purged_post_ids );
			return;
		}

		$this->purge_for_membership_plan_rules( $purged_post_ids );
	}

	/**
	 * @param int[] $purged_post_ids
	 */
	private function purge_single_post_cache( int $post_id, array &$purged_post_ids ): void {
		$post_id = absint( $post_id );
		if ( $post_id < 1 ) {
			return;
		}

		if ( in_array( $post_id, $purged_post_ids, true ) ) {
			return;
		}

		do_action( 'purge_post_cache', $post_id );
		$purged_post_ids[] = $post_id;
	}

	/**
	 * @param int[] $purged_post_ids
	 */
	private function purge_for_membership_plan_rules( array &$purged_post_ids ): void {
		$memberships = wc_memberships();
		if ( ! $memberships || ! method_exists( $memberships, 'get_rules_instance' ) ) {
			return;
		}

		$rules_instance = $memberships->get_rules_instance();
		if ( ! $rules_instance || ! method_exists( $rules_instance, 'get_rules' ) ) {
			return;
		}

		$rules = $rules_instance->get_rules(
			[
				'rule_type'        => [ 'content_restriction', 'product_restriction' ],
				'exclude_inactive' => true,
			]
		);

		if ( empty( $rules ) || ! is_array( $rules ) ) {
			return;
		}

		foreach ( $rules as $rule ) {
			if ( ! is_object( $rule ) || ! method_exists( $rule, 'get_object_ids' ) ) {
				continue;
			}

			$object_ids = (array) $rule->get_object_ids();
			if ( empty( $object_ids ) ) {
				continue;
			}

			foreach ( $object_ids as $object_id ) {
				$this->purge_single_post_cache( (int) $object_id, $purged_post_ids );
			}
		}
	}

	private function is_restricted_post( int $post_id ): bool {
		$post_type = get_post_type( $post_id );
		if ( ! is_string( $post_type ) || '' === $post_type ) {
			return false;
		}

		if ( in_array( $post_type, self::PRODUCT_POST_TYPES, true ) && function_exists( 'wc_memberships_is_product_viewing_restricted' ) ) {
			return (bool) wc_memberships_is_product_viewing_restricted( $post_id );
		}

		if ( function_exists( 'wc_memberships_is_post_content_restricted' ) ) {
			return (bool) wc_memberships_is_post_content_restricted( $post_id );
		}

		return false;
	}

	private function memberships_is_active(): bool {
		return function_exists( 'wc_memberships' ) && class_exists( 'WC_Memberships' );
	}

	private function breeze_is_active(): bool {
		return class_exists( 'Breeze_PurgeCache' );
	}

	private function is_supported_meta_box( string $meta_box_id ): bool {
		return in_array( $meta_box_id, self::META_BOX_IDS, true );
	}
}

function ps_boot_breeze_membership_cache_exclusion(): void {
	new PS_Breeze_Membership_Cache_Exclusion();
}

add_action( 'plugins_loaded', 'ps_boot_breeze_membership_cache_exclusion', 20 );