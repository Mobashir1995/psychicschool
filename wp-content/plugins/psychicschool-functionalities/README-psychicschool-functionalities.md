## Psychicschool Functionalities Plugin

This plugin centralizes business logic for WooCommerce, WooCommerce Bookings, WooCommerce Memberships, and AffiliateWP so that it works regardless of the active theme (Masterstudy or Kadence).

### Structure

- `includes/helpers/hook-helpers.php`  
  - Generic `remove_class_filter()` and `remove_class_action()` helpers for working with class-based hooks.

- `includes/helpers/image-sizes.php`  
  - Registers global image sizes:
    - `img-270-135`
    - `img-300-150`  
  - Uses `after_setup_theme` and is guarded with `function_exists` so it can safely coexist with the Masterstudy child theme.

- `includes/automatewoo/timezone.php`  
  - Loaded only when **AutomateWoo** is active (`class_exists( 'AutomateWoo' )`).
  - Registers custom **booking-related variables** for AutomateWoo:
    - `booking.timezone` → implemented in `variable-booking-timezone.php`
    - `booking.start_date_site_tz` → implemented in `variable-booking-start-date-site-tz.php`
  - Registers a custom **Booking Resource rule**:
    - Adds a `booking_resource` rule (class `Psychicschool_AutomateWoo_Rule_Booking_Resource` in `rule-booking-resource.php`) to allow workflows like “Booking – Resource is / is not [resource]”.
  - Adds an AJAX endpoint `psychicschool_json_search_booking_resources` to power a searchable select of `bookable_resource` posts in the AutomateWoo UI.

- `includes/woocommerce/general.php`  
  - Renders shortcodes in:
    - `woocommerce_short_description`
    - `the_excerpt`
    - the WooCommerce single product excerpt filter `woocommerce_template_single_excerpt`.
  - Customizes the single product add to cart button text to **“Register Now”**.

- `includes/woocommerce/my-account.php`  
  - My Account dashboard:
    - Hooks `psycics_woocommerce_account_dashboard_html()` into `woocommerce_account_dashboard` to render `[insert page='my-account-dashboard' display='content']`.
  - Shortcodes:
    - `[psycics_user_display_name]` – outputs the current user’s first name or display name.
    - `[psycics_wc_logout]` – outputs a WooCommerce logout URL when the user is logged in.
  - WooCommerce Bookings:
    - Filters `woocommerce_bookings_account_tables` to remove the **past** bookings table.
  - WooCommerce Memberships:
    - Filters `wc_memberships_my_memberships_column_names` to:
      - Drop start/end/status/next-bill columns.
      - Rename the membership plan column label to **Course**.
    - Filters `wc_memberships_members_area_my-memberships_actions` to:
      - Remove the **cancel** action.
      - Rename the **view** action to **Visit Classroom**.
      - Point **Visit Classroom** links to the first restricted content page for the plan (if available).
  - WooCommerce Downloads:
    - Filters `woocommerce_account_downloads_columns` to rename the **Product** column to **Course**.
  - WooCommerce account menu:
    - Adds `filter_function_name_5824` on `woocommerce_account_menu_items` (currently a passthrough) so any future menu tweaks live in the plugin instead of a theme.

- `includes/affiliatewp/general.php`  
  - Helper:
    - `psycics_is_user_affiliate_active( $user_id )` – checks AffiliateWP affiliate status for a user.
  - Woo My Account “Partnership” endpoint:
    - Replaces the `woocommerce_account_fs-affiliates-section_endpoint` handler (using `remove_class_action`) and injects:
      - Redirect to Affiliate Area page if the current user is an active affiliate.
      - Otherwise renders `[insert page='my-account-partners-unregistered' display='content']`.
    - Registers the `fs-affiliates-section` endpoint via `add_rewrite_endpoint` on `init`.
  - AffiliateWP dashboard:
    - Adds a **My Account** tab to the AffiliateWP dashboard (`affwp_affiliate_area_tabs`).
    - Redirects that tab (`wc-my-account`) to the WooCommerce **My Account** page.

- `includes/misc/maintenance.php`  
  - Hooks `psycics_sitewide_maintenance_message()` to `ppw_sitewide_above_password_form_container` to output a standard maintenance message above the password form when needed.

### What was intentionally NOT migrated

- FollowUp Emails integration (FUE)
  - Any functions and hooks under the Masterstudy child theme that integrate with `FollowUp Emails` (for example, adding `{booking_zone}` variables) remain in the theme and are **not** copied into this plugin.
- WCCS Currency Switcher
  - All sticky currency switcher logic and related hooks for `WCCS` remain in the Masterstudy child theme and are **not** present in this plugin.
  - This keeps the plugin clean from abandoned/unused dependencies.

### Theme and template responsibilities

- Functional code (hooks, filters, endpoints, shortcodes) now lives in this plugin so it is available under:
  - `masterstudy-child`
  - `kadence-child`
- View templates continue to live in themes:
  - WooCommerce, WooCommerce Bookings, WooCommerce Memberships, WooCommerce Subscriptions, and AffiliateWP dashboard templates should be overridden in the active theme using standard paths, e.g.:
    - `kadence-child/woocommerce/...`
    - `kadence-child/woocommerce-bookings/...`
    - `kadence-child/affiliatewp/...`
  - The Masterstudy child versions remain in place for reference and for the legacy Masterstudy front-end.

