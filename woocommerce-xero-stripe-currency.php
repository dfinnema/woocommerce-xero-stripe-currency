<?php

/**
 * @link              https://itchef.nz
 * @since             1.0.1
 * @package           Woocommerce_Xero_Stripe_Currency
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Xero Stripe Currency
 * Plugin URI:        https://github.com/dfinnema/woocommerce-xero-stripe-currency
 * Description:       Extends the WooCommerce Xero Extension with Currency Conversion for single currency xero subscriptions
 * Version:           1.1
 * Author:            IT Chef
 * Author URI:        https://itchef.nz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-xero-stripe-currency
 * Domain Path:       /languages
 * 
 * @woocommerce-extension
 * WC requires at least: 3.0
 * WC tested up to: 3.0.9
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * 
 * Some hooks used in this plugin that can be used
 * 
 *  FILTER wc_xero_stripe_currency_line_text            = add onto the Xero Line description for each line item (eg; Product1 (20 EUR) )
 *  FILTER wc_xero_stripe_currency_not_found_text       = changes the ORDER NOTE Text when no stripe fee is found in the order
 *  FILTER wc_xero_stripe_currency_not_found_net_text   = changes the ORDER NOTE Text when no stripe net amount is found in the order
 *  FILTER wc_xero_stripe_currency_order_note           = changes the ORDER NOTE text that a stripe currency conversion has been done to the order
 *  FILTER wc_xero_stripe_currency_total_mismatch_text  = changes the ORDER NOTE text that a stripe currency conversion has encountered a rounding error
 *  
 */  

// Plugin Updater
require 'plugin-update-checker/plugin-update-checker.php';
$dfc_currency_puc = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/dfinnema/woocommerce-xero-stripe-currency',
	__FILE__,
	'woocommerce-xero-stripe-currency'
);
$dfc_currency_puc->setBranch('release');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-xero-stripe-currency-activator.php
 */
function activate_woocommerce_xero_stripe_currency() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-xero-stripe-currency-activator.php';
	Woocommerce_Xero_Stripe_Currency_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-xero-stripe-currency-deactivator.php
 */
function deactivate_woocommerce_xero_stripe_currency() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-xero-stripe-currency-deactivator.php';
	Woocommerce_Xero_Stripe_Currency_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_xero_stripe_currency' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_xero_stripe_currency' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-xero-stripe-currency.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_woocommerce_xero_stripe_currency() {

	$plugin = new Woocommerce_Xero_Stripe_Currency();
	$plugin->run();

}
run_woocommerce_xero_stripe_currency();

/**
 * Gets the Plugin Path.
 *
 * Some functions require this file's full path.
 *
 * @since    1.0.1
 */
function getfile_woocommerce_xero_stripe_currency() {
     
     return __FILE__;
 }