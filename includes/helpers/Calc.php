<?php


namespace XEROSTRIPECURRENCY;


use LaLit\Array2XML;
use LaLit\XML2Array;

/* Ensure WP is Running */
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

class Calc {

	/**
	 * Check if the current order matches the Stripe / Xero currency
	 *
	 * @param string $invoice_currency
	 *
	 * @return bool
	 */
	public static function is_foreign_currency_order( $invoice_currency = '' ) {
		$stripe_currency = self::get_stripe_currency();
		if (false === $stripe_currency) {
			return false;
		}

		if ( $invoice_currency !== $stripe_currency ) {
			return true;
		}

		return false;

	}

	/**
	 * Calculate Currency Point
	 *
	 * @param int $stripe_fee
	 * @param int $stripe_payout
	 * @param int $invoice_total
	 *
	 * @return float|int
	 */
	public static function get_currency_point( $stripe_fee = 0 , $stripe_payout = 0 , $invoice_total = 0 ) {

		// Add the Payout and Stripe Fee to get the total amount converted
		$stripe_currency_point = ($stripe_fee + $stripe_payout);

		// Divide by the original amount charged in the foreign currency
		$stripe_currency_point = $stripe_currency_point / $invoice_total;

		// Return the currency point
		return $stripe_currency_point;
	}

	/**
	 * Get the Currency set in options for Stripe / Xero
	 * @return bool|mixed|void
	 */
	public static function  get_stripe_currency() {
		$currency = get_option( 'wc_xero_dfc_stripe_currency' , '');
		if (!empty($currency)) {
			return $currency;
		}
		return false;
	}
}