<?php


namespace XEROSTRIPECURRENCY;

use XEROSTRIPEFEES\STRIPE;
use XEROSTRIPEFEES\WOO;
use XEROSTRIPEFEES\XERO;

/**
 * Class Accounts
 * @package XEROSTRIPECURRENCY
 */
class Currency {

	private $XERO_CURRENCY_CODE = 'CurrencyCode';

	/**
	 * Accounts constructor.
	 */
	public function __construct() {

		// Update the Currency ( priority needs to be lower then 20 otherwise the stripe fee has already been added )
		add_filter( 'woocommerce_xero_stripe_fees_array', array( $this, 'update' ), 15, 2 );

		// Add the Woocommerce Xero Hooks
		add_filter('woocommerce_xero_payment_amount', array( $this , 'payment'), 10, 2 );

	}

	/**
	 * Payment update amount
	 * @param $amount
	 * @param $obj
	 *
	 * @return float
	 */
	public function payment( $amount = 0 , $obj ) {

		// Load Order ID
		$order = $obj->get_order();
		if (is_object($order)) {
			$order_id = $order->get_id();

			// Check it has a Stripe Payment Method
			//if (WOO::has_stripe_payment_method( $order_id )) {

				// Grab the Stripe Details from the order
				$stripe_fee = get_post_meta( $order_id , '_stripe_fee', true );
				$stripe_amount = get_post_meta( $order_id , '_stripe_net', true );

				$invoice_total = $order->get_total();

				$stripe_currency_point = ($stripe_fee + $stripe_amount) / $invoice_total;

				// Remove it from the payment amount
				$amount = $amount * $stripe_currency_point;

			//}
		}

		return $amount;
	}

	/**
	 * Update Currency
	 * @param $data
	 * @param $order_id
	 *
	 * @return mixed
	 */
	public function update( $data , $order_id ) {

		// Invoice Data
		if (array_key_exists(XERO::DATA_TYPE_INVOICE,$data)) {
			$invoice = $data[ XERO::DATA_TYPE_INVOICE ];

			// Make sure we have line items
			if (array_key_exists(XERO::LINEITEMS,$invoice) && !empty($invoice[XERO::LINEITEMS])) {
				if ( isset( $invoice[ XERO::LINEITEMS ][ XERO::LINEITEM ] ) ) {

					$line_items = $invoice[ XERO::LINEITEMS ][ XERO::LINEITEM ];

					// Make sure our payment method is Stripe, abort otherwise
					if (true !== WOO::has_stripe_payment_method($order_id)) {
						error_log(' - Not Stripe Payment on Order');
						return $data;
					}

					// Grab the Stripe Details from the order
					$stripe_fee = get_post_meta( $order_id , '_stripe_fee', true );
					$stripe_amount = get_post_meta( $order_id , '_stripe_net', true );

					// Get the Invoice Total
					$invoice_total = $invoice[XERO::TOTAL];

					// Currency
					$invoice_currency = $invoice[$this->XERO_CURRENCY_CODE];

					// If the currency is the same, abort currency changes
					if (!Calc::is_foreign_currency_order( $invoice_currency )) {
						error_log(' - Currency is the same');
						return $data;
					}

					// Line Item Currency
					/* translator: The Xero Description added to each line showing the original currency amount. eg; ($100.00 NZD) */
					$line_item_description_addon = __(' ($%d %s)','woocommerce-xero-stripe-currency');

					// Check Basic Data Quick
					if ( $stripe_fee && $stripe_amount && $invoice_total && $invoice_currency ) {

						// Get the Currency Exchange Point
						$stripe_currency_point = Calc::get_currency_point( $stripe_fee , $stripe_amount, $invoice_total );

						// Check if we have just one item, otherwise the array goes funny and will not work for Xero
						if (array_key_exists(XERO::DESCRIPTION, $line_items ) ) {

							// Get Original Amount
							$item_amount_old = $line_items[ XERO::UNITAMOUNT ];
							if ('on' == get_option( 'wc_xero_dfc_stripe_currency_tax' , false) ) {
								$item_amount_old = $line_items[ XERO::UNITAMOUNT ] + $line_items[ XERO::TAXAMOUNT ];
							}
							$currency_description = sprintf( $line_item_description_addon, floatval(round( $item_amount_old ,2) ), $invoice_currency );

							// Description Add the original charge
							$line_items[ XERO::DESCRIPTION ] = $line_items[ XERO::DESCRIPTION ] . $currency_description;

							// Convert amount to new amount
							$line_items[ XERO::UNITAMOUNT ] = $line_items[ XERO::UNITAMOUNT ] * $stripe_currency_point;


							// Is the transaction Tax Exempt?
							if ('on' == get_option( 'wc_xero_dfc_stripe_currency_tax' , false) ) {

								// Add the tax into the item otherwise we lose money
								$line_items[ XERO::UNITAMOUNT ] = $line_items[ XERO::UNITAMOUNT ]  + ( $line_items[ XERO::TAXAMOUNT ] * $stripe_currency_point );

								// Remove Tax from the Line Item
								$line_items[ XERO::TAXTYPE ] = 'NONE';
								$line_items[ XERO::TAXAMOUNT ] = 0;

							} else {
								// If there are taxes in the line item
								if ( 0 !== $line_items[ XERO::TAXAMOUNT ] ) {
									$line_items[ XERO::TAXAMOUNT ] = $line_items[ XERO::TAXAMOUNT ] * $stripe_currency_point;
								}
							}

						} else {

							// Go through each line item and update the account code if one is found
							foreach ( $line_items as &$line_item ) {

								// Check that we are not changing the Stripe Fee
								if (strpos($line_item[ XERO::DESCRIPTION ], STRIPE::get_fee_description() ) !== false) {
									continue;
								}

								// Get Original Amount
								$item_amount_old = $line_item[ XERO::UNITAMOUNT ];
								if ('on' == get_option( 'wc_xero_dfc_stripe_currency_tax' , false) ) {
									$item_amount_old = $line_item[ XERO::UNITAMOUNT ] + $line_item[ XERO::TAXAMOUNT ];
								}

								// Get Original Amount
								$currency_description = sprintf( $line_item_description_addon, floatval(round( $item_amount_old ,2) ), $invoice_currency );

								// Description Add the original charge
								$line_item[ XERO::DESCRIPTION ] = $line_item[ XERO::DESCRIPTION ] . $currency_description;

								// Convert amount to new amount
								$line_item[ XERO::UNITAMOUNT ] = $line_item[ XERO::UNITAMOUNT ] * $stripe_currency_point;

								// Is the transaction Tax Exempt?
								if ('on' == get_option( 'wc_xero_dfc_stripe_currency_tax' , false) ) {

									// Add the tax into the item otherwise we lose money
									$line_item[ XERO::UNITAMOUNT ] = $line_item[ XERO::UNITAMOUNT ]  + ( $line_item[ XERO::TAXAMOUNT ] * $stripe_currency_point );

									// Remove Tax from the Line Item
									$line_item[ XERO::TAXTYPE ] = 'NONE';
									$line_item[ XERO::TAXAMOUNT ] = 0;
								} else {
									// If there are taxes in the line item
									if ( 0 !== $line_item[ XERO::TAXAMOUNT ] ) {
										$line_item[ XERO::TAXAMOUNT ] = $line_item[ XERO::TAXAMOUNT ] * $stripe_currency_point;
									}
								}
							}
						}

						// Add Line Items back to invoice
						$invoice[XERO::LINEITEMS][XERO::LINEITEM] = $line_items;

						// Update Invoice Currency
						$invoice[$this->XERO_CURRENCY_CODE] = $this->get_currency_based_on_country();

						// Merge Data back into Xero Data Packet
						$data[XERO::DATA_TYPE_INVOICE] = $invoice;
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Gets the currency based on supported countries
	 * @param string $country_iso
	 *
	 * @return bool|mixed
	 */
	private function get_currency_based_on_country( $country_iso = '' ) {
		if (empty($country_iso)) {
			$country_iso = get_option( 'wc_xero_dfc_stripe_fee_country','XX');
		}

		$country_currency = array(
			'NZ'=>'NZD',
			'AU'=>'AUD',
			'US'=>'USD',
			'CA'=>'CAD',
			'UK'=>'GBP',
			'EU'=>'EUR',
			'IR'=>'EUR'
		);

		if (array_key_exists($country_iso,$country_currency)) {
			return $country_currency[$country_iso];
		}
		return false;
	}

	/**
	 * Log
	 * @param string $message
	 */
	private function log( $message = '' ) {
		xerostripecurrency()->log( $message );
	}
}

new Currency();