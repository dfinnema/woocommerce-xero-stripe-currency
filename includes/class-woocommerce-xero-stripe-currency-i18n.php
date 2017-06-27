<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://itchef.nz
 * @since      1.0.1
 *
 * @package    Woocommerce_Xero_Stripe_Currency
 * @subpackage Woocommerce_Xero_Stripe_Currency/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.1
 * @package    Woocommerce_Xero_Stripe_Currency
 * @subpackage Woocommerce_Xero_Stripe_Currency/includes
 * @author     IT Chef <hello@itchef.nz>
 */
class Woocommerce_Xero_Stripe_Currency_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-xero-stripe-currency',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
