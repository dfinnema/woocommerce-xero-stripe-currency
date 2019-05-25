<?php

namespace XEROSTRIPECURRENCY;

use WC_Logger;

/* Ensure WP is Running */
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * Class XEROSTRIPECURRENCY
 * @package XEROSTRIPECURRENCY
 */
class XEROSTRIPECURRENCY {

	/**
	 * XEROSTRIPECURRENCY constructor.
	 */
	public function __construct() {
		/* Do Nothing Here */
	}

	/**
	 * Init XEROSTRIPECURRENCY
	 */
	public function init() {

	    // Check on other needed plugins
		$this->has_needed_plugins();

		// Add Text domain Support
        $this->add_textdomain_support();

		// Load Helpers
		$this->load_helpers(
			array(
				'Calc'
			)
		);

		// Load Modules
		$this->load_modules(
			array(
                'Options',
				'Currency'
			)
		);
	}

	/**
     * Logging in Debug Mode
	 * @param string $message
	 */
    public function log($message='') {

	    if (is_array($message) || is_object($message)) {
		    $message = print_r($message,1);
	    }

	    // Running in Xero Debug Mode?
	    $debug = get_option( 'wc_xero_debug' , false );
	    if (('on' == $debug) && (!empty($message))) {
		    if ( class_exists( 'WC_Logger' ) ) {
			    $logger = new WC_Logger();
			    $logger->add( 'xero', '[Xero Sripe Fees] - '.$message );
		    }
	    }
    }

	/**
	 * Loads any needed Modules
	 * @param array $modules
	 */
	private function load_modules($modules = array() ) {
		$path = plugin_dir_path( XEROSTRIPECURRENCY_FILE ) . 'includes/modules/';
		foreach ($modules as $module) {
			if (file_exists($path.$module.'.php')) {
				require_once($path.$module.'.php');
			}
		}
	}

	/**
	 * Loads any needed Helpers
	 * @param array $helpers
	 */
	private function load_helpers($helpers = array() ) {
		$path = plugin_dir_path( XEROSTRIPECURRENCY_FILE ) . 'includes/helpers/';
		foreach ($helpers as $helper) {
			if (file_exists($path.$helper.'.php')) {
				require_once($path.$helper.'.php');
			}
		}
	}

	/**
	 * In case dependent plugins are removed, lets self-deactivate if needed.
	 */
	private function has_needed_plugins() {
		if (! in_array( 'woocommerce-xero/woocommerce-xero.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
		    ! in_array( 'woocommerce-xero-stripe-fees/woocommerce-xero-stripe-fees.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
			) {

			// Nope not activate, lets deactivate this plugin
			if (!function_exists('deactivate_plugins')) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			\deactivate_plugins( XEROSTRIPECURRENCY_FILE );
			// Tell the user
			if (! in_array( 'woocommerce-xero/woocommerce-xero.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_action('admin_notices', array( $this, 'show_needed_plugins_notice_xero' ) );
			}

			if (! in_array( 'woocommerce-xero-stripe-fees/woocommerce-xero-stripe-fees.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				add_action('admin_notices', array( $this, 'show_needed_plugins_notice_xero_fees' ) );
			}

		}
    }

	/**
	 * Shows an admin notice if missing plugins
	 */
	public function show_needed_plugins_notice_xero_fees() {

		/* translators: Woocommerce Xero Stripe Fees Plugin Name for Missing plugin notices */
		$name = __('WooCommerce Xero Stripe Fees','woocommerce-xero-stripe-currency');

		/* translators: Woocommerce Xero Stripe Fees Plugin is required for this plugin to run */
		$why = __('is required to be installed and activated!','woocommerce-xero-stripe-currency');

		$link = sprintf('<a href="https://github.com/dfinnema/woocommerce-xero-stripe-fees">%s</a> %s', esc_html( $name ), esc_html( $why ) );

		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo($link); ?></p>
		</div>
		<?php
	}

	/**
	 * Shows an admin notice if missing plugins
	 */
    public function show_needed_plugins_notice_xero() {

	    /* translators: Woocommerce Xero Plugin Name */
	    $name = __('WooCommerce Xero Integration','woocommerce-xero-stripe-currency');

	    /* translators: Woocommerce Xero Plugin is required for this plugin to run */
	    $why = __('is required to be installed and activated!','woocommerce-xero-stripe-currency');

	    $link = sprintf('<a href="https://woocommerce.com/products/xero/">%s</a> %s', esc_html( $name ), esc_html( $why ) );

	    ?>
	    <div class="notice notice-error is-dismissible">
		    <p><?php echo($link); ?></p>
	    </div>
	    <?php
    }

	/**
	 * Adds Textdomain Support
	 */
    private function add_textdomain_support() {
	    load_plugin_textdomain(
		    'woocommerce-xero-stripe-currency',
		    false,
		    plugin_dir_path( XEROSTRIPECURRENCY_FILE ) . 'languages/'
	    );
    }
}