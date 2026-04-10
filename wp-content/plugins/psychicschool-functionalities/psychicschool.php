<?php
/**
 * Plugin Name: Psychicschool Functionalities
 * Description: This plugin adds functionalities to the Psychicschool website.
 * Version: 1.0.0
 * Author: PluginDevs
 * Author URI: https://plugin-devs.com/
 * Text Domain: psychicschool-functionalities
 * Requires at least: 6.2
 * Requires PHP: 8.2
 * @package PluginDevs
 * @license GPL-3.0+
 * @link https://plugin-devs.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main plugin class
 */
class Psychicschool_Functionalities {
    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants
     */
    public function define_constants() {
        define( 'PSYCHICSCHOOL_FUNCTIONALITIES_VERSION', '1.0.0' );
        define( 'PSYCHICSCHOOL_FUNCTIONALITIES_DIR', plugin_dir_path( __FILE__ ) );
        define( 'PSYCHICSCHOOL_FUNCTIONALITIES_URL', plugin_dir_url( __FILE__ ) );
    }

    /**
     * Includes
     */
    public function includes() {
        // Helpers.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/helpers/hook-helpers.php';
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/helpers/image-sizes.php';

        // URL Redirect Fixer.
        if( !function_exists('cuf_save_mappings')) {
            require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/url-redirect-fixer.php';
        }

        // AutomateWoo (Bookings-related variables & rules).
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/automatewoo/timezone.php';

        // WooCommerce & My Account.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/woocommerce/general.php';
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/woocommerce/my-account.php';

        // Breeze + WooCommerce Memberships: exclude restricted content from page cache.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/woocommerce/breeze-membership-cache-exclusion.php';

        // AffiliateWP.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/affiliatewp/general.php';

        // Misc.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/misc/maintenance.php';

        // STM Features.
        require_once PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'includes/stm-features/stm-features.php';
    }

    /**
     * Init hooks
     */
    public function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    /**
     * Init
     */
    public function init() {
        $this->load_textdomain();
        //
    }

    /**
     * Admin init
     */
    public function admin_init() {
        //
    }

    /**
     * Load textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'psychicschool-functionalities', false, PSYCHICSCHOOL_FUNCTIONALITIES_DIR . 'languages' );
    }
}

new Psychicschool_Functionalities();

/**
 * Flush rewrite rules on plugin activation so custom My Account endpoint (fs-affiliates-section) is recognized.
 */
function psychicschool_functionalities_activation() {
	// Register endpoint before flushing so it is included in the new rules.
	add_rewrite_endpoint( 'fs-affiliates-section', EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'psychicschool_functionalities_activation' );

/**
 * Flush rewrite rules on deactivation to remove the custom endpoint from rules.
 */
function psychicschool_functionalities_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'psychicschool_functionalities_deactivation' );