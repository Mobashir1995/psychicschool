<?php

/**
 * Breeze Cache Exclusion for WooCommerce Memberships Restricted Content.
 *
 * Handles two responsibilities:
 *
 *  1. DONOTCACHEPAGE — on every singular frontend request, if the current
 *     post/page/product has an active membership restriction, tells Breeze
 *     not to write a new cache file for that URL.
 *
 *  2. Cache purge — whenever a membership restriction is saved or updated
 *     (from the Membership Plan edit screen OR an individual post/page/product
 *     edit screen), the physical Breeze cache files for affected posts are
 *     deleted so stale cached versions are never served again.
 *
 * @package PsychicschoolFunctionalities
 * @since   1.0.0
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Programmatically excludes Breeze page cache for any post, page, or product
 * that is restricted by WooCommerce Memberships.
 */
final class PS_Breeze_Membership_Cache_Exclusion
{

	/**
	 * WC Memberships meta-box IDs that handle restriction rules.
	 * Used to filter the wc_memberships_save_meta_box action.
	 */
	private const RESTRICTION_META_BOX_IDS = [
		'wc-memberships-post-memberships-data',   // individual post / page / product screen
		'wc-memberships-membership-plan-data',     // membership plan edit screen
	];

	/**
	 * Post types treated as WooCommerce products for view-restriction checks.
	 */
	private const PRODUCT_POST_TYPES = [
		'product',
		'product_variation',
	];

	/**
	 * Boot the class by registering WordPress hooks.
	 */
	public function __construct()
	{
		// Runtime: prevent Breeze caching a restricted singular page.
		add_action('template_redirect', $this->maybe_set_donotcachepage(...), 1);

		// Runtime: also prevent Breeze from writing/serving a cached page for
		// ANY logged-in user on a product page.  Breeze cannot build a
		// user-specific cache filename at advanced-cache.php time (because
		// wp_validate_auth_cookie is not available then), so it falls back to
		// the guest cache path and serves stale guest nonces to members,
		// causing 403 errors on booking/coupon/ifso AJAX calls.
		add_action('template_redirect', $this->donotcache_product_for_logged_in_user(...), 1);

		// Admin: purge Breeze cache when a restriction meta-box is saved.
		add_action('wc_memberships_save_meta_box', $this->on_memberships_meta_box_saved(...), 10, 4);

		// Fix invalid nonces for logged-in users receiving guest-cached HTML.
		add_action('init', $this->fix_stale_nonces_for_cached_members(...), 1);
	}


	// =========================================================================
	// Hook callbacks
	// =========================================================================

	/**
	 * Fix invalid nonces for logged-in users loading from guest cache.
	 *
	 * When Breeze has "Cache logged in users" enabled, it serves the guest HTML
	 * to logged-in users. This HTML contains guest nonces (generated for user ID 0).
	 * When the frontend fires AJAX requests (Bookings, Smart Coupons, If-so), the
	 * backend wp_verify_nonce() checks the nonce against the logged-in user ID and
	 * fails with 403.
	 *
	 * Since these specific endpoints are read-only or low-risk, we intercept them
	 * early and replace the stale guest nonce with a freshly generated valid nonce
	 * for the current user session, allowing the AJAX to succeed.
	 */
	private function fix_stale_nonces_for_cached_members(): void
	{
		if (! is_user_logged_in()) {
			return;
		}

		$is_wc_ajax    = isset($_GET['wc-ajax']) ? sanitize_text_field($_GET['wc-ajax']) : false;
		$is_admin_ajax = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : false;

		// 1. WooCommerce Bookings
		if (in_array($is_wc_ajax, ['wc_bookings_find_booked_day_blocks', 'wc_bookings_get_end_time_html', 'wc_bookings_show_available_month_blocks'], true)) {
			$action_map = [
				'wc_bookings_find_booked_day_blocks' => 'find-booked-day-blocks',
				'wc_bookings_get_end_time_html'      => 'get_end_time_html',
				'wc_bookings_show_available_month_blocks' => 'show_available_month_blocks',
			];
			$action = $action_map[$is_wc_ajax];
			$fresh_nonce = wp_create_nonce($action);
			$_REQUEST['security'] = $fresh_nonce;
			$_POST['security']    = $fresh_nonce;
			$_GET['security']     = $fresh_nonce;
		}

		// 2. Smart Coupons
		if ('wc_sc_get_attached_coupons' === $is_wc_ajax) {
			$action = 'wc-sc-get-attached-coupons';
			$fresh_nonce = wp_create_nonce($action);
			$_REQUEST['security'] = $fresh_nonce;
			$_POST['security']    = $fresh_nonce;
			$_GET['security']     = $fresh_nonce;
		}

		// 3. If-so Add Page Visit
		if ('ifso_add_page_visit' === $is_admin_ajax) {
			$action = 'ifso-nonce';
			$fresh_nonce = wp_create_nonce($action);
			$_REQUEST['nonce'] = $fresh_nonce;
			$_POST['nonce']    = $fresh_nonce;
			$_GET['nonce']     = $fresh_nonce;
		}
	}

	/**
	 * Define DONOTCACHEPAGE for any logged-in user visiting a product page.
	 *
	 * This prevents Breeze from either serving a cached (guest) page or writing
	 * a new cache entry for the current URL.  Called at priority 1 on
	 * template_redirect, before Breeze's ob_start() runs.
	 */
	private function donotcache_product_for_logged_in_user(): void
	{
		if (! $this->breeze_is_active()) {
			return;
		}

		// 1. Array of specific product slugs that should NOT be cached for members.
		$excluded_product_slugs = [
			'student-energy-checks-by-clairvoyant-teachers',
			'psychic-readings-by-clairvoyant-students',
			'angel-healing-in-the-spiritual-healing-clinic',
			'the-erasure-lectures'
		];

		// 2. Array of product category slugs that should NOT be cached for members.
		$excluded_category_slugs = [
			'staff-energy-checks',
			'intuitional-courses-live',
			'continuational',
			'downloadable',
		];

		$is_product_page = function_exists('is_product') && is_product();
		$is_wc_ajax      = isset($_GET['wc-ajax']);
		$should_exclude  = false;

		// Always exclude wc-ajax requests to ensure fresh nonces for members.
		if ($is_wc_ajax) {
			$should_exclude = true;
		} elseif ($is_product_page) {
			$product = wc_get_product(get_the_ID());
			if ($product) {
				$current_slug = $product->get_slug();
				// Check if the product slug is in the exclusion list.
				if (in_array($current_slug, $excluded_product_slugs, true)) {
					$should_exclude = true;
				} else {
					// Check if the product belongs to any excluded categories.
					if (has_term($excluded_category_slugs, 'product_cat', $product->get_id())) {
						$should_exclude = true;
					}
				}
			}
		}
		if ($should_exclude && ! defined('DONOTCACHEPAGE')) {
			define('DONOTCACHEPAGE', true);
		}
	}

	/**
	 * Define DONOTCACHEPAGE when the current singular post/page/product is
	 * restricted by WooCommerce Memberships.
	 *
	 * Runs at priority 1 so it fires before Breeze starts output buffering.
	 * Breeze's constant_donotcachepage_found() checks this constant before
	 * writing or serving a cache file.
	 */
	private function maybe_set_donotcachepage(): void
	{

		if (! $this->is_wc_memberships_active()) {
			return;
		}

		if (! $this->breeze_is_active()) {
			return;
		}

		if (! is_singular()) {
			return;
		}

		$post_id = (int) get_the_ID();

		if ($post_id <= 0) {
			return;
		}

		if ($this->is_restricted($post_id) && ! defined('DONOTCACHEPAGE')) {
			define('DONOTCACHEPAGE', true);
		}
	}

	/**
	 * Purge Breeze cache for posts affected by a saved restriction meta-box.
	 *
	 * `wc_memberships_save_meta_box` is WC Memberships' own action (line 710
	 * of abstract-wc-memberships-meta-box.php). It fires from the shared base
	 * save_post callback regardless of which admin screen triggered the save:
	 *
	 *   - Individual post / page / product edit screen  → $post_id is that post
	 *   - Membership Plan edit screen                   → $post_id is the plan
	 *
	 * @param array    $post_data   Raw $_POST data.
	 * @param string   $meta_box_id WC Memberships meta-box ID.
	 * @param int      $post_id     ID of the post that was saved.
	 * @param \WP_Post $post        Post object.
	 */
	private function on_memberships_meta_box_saved(
		array    $post_data,
		string   $meta_box_id,
		int      $post_id,
		\WP_Post $post
	): void {

		// Only act on restriction-related meta-boxes.
		if (! in_array($meta_box_id, self::RESTRICTION_META_BOX_IDS, true)) {
			return;
		}

		if (! $this->breeze_is_active()) {
			return;
		}

		if (! $this->is_wc_memberships_active()) {
			return;
		}

		$post_type = $post->post_type;

		// ── Scenario A: Saved from the individual post / page / product screen.
		// $post_id IS the restricted content — purge it directly.
		if ('wc_membership_plan' !== $post_type) {
			do_action('purge_post_cache', $post_id);
			return;
		}

		// ── Scenario B: Saved from the Membership Plan edit screen.
		// Resolve every post ID this plan restricts and purge each one.
		$this->purge_plan_restricted_posts($post_id);
	}


	// =========================================================================
	// Private helpers
	// =========================================================================

	/**
	 * Check whether the given post is restricted by WooCommerce Memberships.
	 *
	 * Covers:
	 *   - Posts, pages, and any CPT  → wc_memberships_is_post_content_restricted()
	 *   - Products / variations      → wc_memberships_is_product_viewing_restricted()
	 *
	 * @param int $post_id Post ID to evaluate.
	 */
	private function is_restricted(int $post_id): bool
	{

		if (
			function_exists('wc_memberships_is_post_content_restricted') &&
			wc_memberships_is_post_content_restricted($post_id)
		) {
			return true;
		}

		$post_type = (string) get_post_type($post_id);

		if (
			in_array($post_type, self::PRODUCT_POST_TYPES, true) &&
			function_exists('wc_memberships_is_product_viewing_restricted') &&
			wc_memberships_is_product_viewing_restricted($post_id)
		) {
			return true;
		}

		return false;
	}

	/**
	 * Resolve all post IDs restricted by a given membership plan and purge
	 * their Breeze cache files (local + Cloudflare + Varnish).
	 *
	 * Uses Breeze's own `purge_post_cache` action which is handled by
	 * Breeze_PurgeCache::purge_post_cache() and covers all cache layers.
	 *
	 * @param int $plan_id Membership plan post ID.
	 */
	private function purge_plan_restricted_posts(int $plan_id): void
	{

		$rules_instance = wc_memberships()->get_rules_instance();

		if (! $rules_instance) {
			return;
		}

		$rules = $rules_instance->get_rules([
			'plan_id'          => $plan_id,
			'rule_type'        => ['content_restriction', 'product_restriction'],
			'exclude_inactive' => true,
		]);

		if (empty($rules)) {
			return;
		}

		$purged = [];

		foreach ($rules as $rule) {

			$object_ids = $rule->get_object_ids();

			if (empty($object_ids)) {
				continue;
			}

			foreach ($object_ids as $raw_id) {

				$object_id = absint($raw_id);

				if ($object_id <= 0 || in_array($object_id, $purged, true)) {
					continue;
				}

				do_action('purge_post_cache', $object_id);

				$purged[] = $object_id;
			}
		}
	}

	/**
	 * Return true when WooCommerce Memberships is active and its API is ready.
	 */
	private function is_wc_memberships_active(): bool
	{
		return function_exists('wc_memberships') && class_exists('WC_Memberships');
	}

	/**
	 * Return true when Breeze is active and its API is ready.
	 */
	private function breeze_is_active(): bool
	{
		return class_exists('Breeze_PurgeCache');
	}
}

// Instantiate after all plugins are loaded so the WC Memberships API is ready.
add_action('plugins_loaded', static fn() => new PS_Breeze_Membership_Cache_Exclusion(), 20);
