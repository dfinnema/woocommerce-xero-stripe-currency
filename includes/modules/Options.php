<?php
namespace XEROSTRIPECURRENCY;

/* Ensure WP is Running */
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * Class Options
 * @package XEROSTRIPECURRENCY
 */
class Options {

	/**
	 * Admin constructor.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this , 'settings_page' ), 11);
	}

	/**
	 * Register the settings and fields for options page.
	 *
	 * Uses the existing WooCommerce Xero Extension options page, makes it easy for the user to manage in one place
	 *
	 * @since    1.3
	 */
	public function settings_page() {

	    // Stripe Main Currency
		register_setting(
			'woocommerce_xero',
			'wc_xero_dfc_stripe_currency'
		);

		add_settings_field(
			'wc_xero_dfc_stripe_currency',
			__('Stripe Currency', 'woocommerce-xero-stripe-currency'),
			array( $this, 'currency_input' ),
			'woocommerce_xero',
			'wc_xero_settings'
		);

		// Tax option for transactions in a different currency
		register_setting(
			'woocommerce_xero',
			'wc_xero_dfc_stripe_currency_tax'
		);

		add_settings_field(
			'wc_xero_dfc_stripe_currency_tax',
			__('Stripe Currency Tax', 'woocommerce-xero-stripe-currency'),
			array( $this, 'currency_tax_input' ),
			'woocommerce_xero',
			'wc_xero_settings'
		);
	}

	/**
	 * The CURRENCY option field outputed as HTML
	 *
	 * Outputs the Currency Text Field
	 *
	 * @since    1.0.1
	 */
	public function currency_input() {

		$value = get_option( 'wc_xero_dfc_stripe_currency' , '');
		// echo the field
		?>
        <input id='wc_xero_dfc_stripe_currency' name='wc_xero_dfc_stripe_currency' type='text' value='<?php echo esc_attr( $value ); ?>' />
        <?php /* translators: Currency input field */ ?>
        <p class="description"><?php _e('Currency code of the primary Stripe currency (needs to match Xero too).', 'woocommerce-xero-stripe-currency'); ?></p>
		<?php
	}

	/**
	 * The TAX option field outputed as HTML
	 *
	 * Outputs the Currency Tax Checkbox
	 *
	 * @since    1.0.1
	 */
	public function currency_tax_input() {

		$value = get_option( 'wc_xero_dfc_stripe_currency_tax' , false);
		// echo the field
		?>
        <input type="checkbox" name="wc_xero_dfc_stripe_currency_tax" id="wc_xero_dfc_stripe_currency_tax"<?php if ('on' == $value) {echo(' checked');} ?>>
		<?php /* translators: Foreign currency transactions tickbox input field */ ?>
        <p class="description"><?php _e('Foreign currency transactions are tax free', 'woocommerce-xero-stripe-currency'); ?></p>
		<?php
	}
}

new Options();