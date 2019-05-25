<?php
/**
 * Plugin Name:       WooCommerce Xero Stripe Currency
 * Plugin URI:        https://github.com/dfinnema/woocommerce-xero-stripe-currency
 * Description:       Extends the WooCommerce Xero Extension with Currency Conversion for single currency xero subscriptions
 * Version:           2.0
 * Author:            IT Chef
 * Author URI:        https://itchef.nz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-xero-stripe-currency
 * Domain Path:       /languages
 *
 * @woocommerce-extension
 * WC requires at least: 3.6
 * WC tested up to: 3.6.3
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

define('XEROSTRIPECURRENCY_VERSION','1.3');
define('XEROSTRIPECURRENCY_FILE',__FILE__);

/**
 * The core plugin class
 */
require_once plugin_dir_path( XEROSTRIPECURRENCY_FILE ) . 'includes/class-xerostripecurrency.php';

/**
 * Gets the main Class Instance
 * @return XEROSTRIPECURRENCY\XEROSTRIPECURRENCY
 */
function xerostripecurrency() {

	// globals
	global $xerostripecurrency;

	// initialize
	if( !isset($xerostripecurrency) ) {
		$xerostripecurrency = new \XEROSTRIPECURRENCY\XEROSTRIPECURRENCY();
		$xerostripecurrency->init();
	}

	// return
	return $xerostripecurrency;
}
add_action( 'plugins_loaded', 'xerostripecurrency' );

/**
 * Updater
 */
try {
	require 'updater/plugin-update-checker.php';
	$dfc_currency_puc = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/dfinnema/woocommerce-xero-stripe-currency',
		__FILE__,
		'woocommerce-xero-stripe-currency'
	);
	$dfc_currency_puc->setBranch('release');
} catch (Exception $e) {
	wp_die('Updater Error: '.$e->getMessage(), '', array('back_link'=>true));
}

