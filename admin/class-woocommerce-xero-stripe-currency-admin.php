<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://itchef.nz
 * @since      1.0.1
 *
 * @package    Woocommerce_Xero_Stripe_Currency
 * @subpackage Woocommerce_Xero_Stripe_Currency/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Xero_Stripe_Currency
 * @subpackage Woocommerce_Xero_Stripe_Currency/admin
 * @author     IT Chef <hello@itchef.nz>
 */
class Woocommerce_Xero_Stripe_Currency_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Xero_Stripe_Currency_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Xero_Stripe_Currency_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-xero-stripe-currency-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Xero_Stripe_Currency_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Xero_Stripe_Currency_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-xero-stripe-currency-admin.js', array( 'jquery' ), $this->version, false );

	}
    
    /**
	 * Display a notice when incompatibilities are found.
	 * 
	 * Looks at enabled options for WooCommerce Xero and tells the user about issues 
	 *
	 * @since    1.1.0
	 */
    public function compatibility() {
        
        // Make sure the user is admin (no point displaying it otherwise)
        if (is_admin()) {
            // Get Current WooCommerce Xero Options
            $xero_send_payments = get_option( 'wc_xero_send_payments' , '');

            if ('on' == $xero_send_payments) {
                // Tell the user that sending Payments to Xero will not work with this plugin active (TODO in the future)
                add_action('admin_notices', function() {
                    echo('<div class="notice notice-warning"><p>['.$this->plugin_name.'] '.__('Sending payments to Xero does not work with this plugin enabled. Please turn off this option to avoid getting errors','woocommerce-xero-stripe-fees').'</p></div>');
                });
            }
        }
        
    }
    
    /**
	 * Register the settings and fields for options page.
	 * 
	 * Uses the existing WooCommerce Xero Extension options page, makes it easy for the user to manage in one place
	 *
	 * @since    1.0.1
	 */
    
    public function settings_page() {


        register_setting(
            'woocommerce_xero',                 // settings page
            'wc_xero_dfc_stripe_currency'          // option name
        );

        register_setting(
            'woocommerce_xero',                 // settings page
            'wc_xero_dfc_stripe_currency_tax'          // option name
        );


        add_settings_field(
            'wc_xero_dfc_stripe_currency',      // id
            __('Stripe Currency', 'woocommerce-xero-stripe-currency'),              // setting title
            array( $this, 'currency_input' ),    // display callback
            'woocommerce_xero',                 // settings page
            'wc_xero_settings'                  // settings section
        );

        add_settings_field(
            'wc_xero_dfc_stripe_currency_tax',      // id
            __('Stripe Currency Tax', 'woocommerce-xero-stripe-currency'),              // setting title
            array( $this, 'currency_tax_input' ),    // display callback
            'woocommerce_xero',                 // settings page
            'wc_xero_settings'                  // settings section
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
        <p class="description"><?php _e('Foreign currency transactions are tax free', 'woocommerce-xero-stripe-currency'); ?></p>
        <?php
    }

    
    /**
	 * The actual code that makes this plugin work.
	 * 
	 * Takes the XML that the WooCommerce Xero Extension creates and changes it 
	 * with the required code before handing it back to the Extension to Send to Xero. Uses a filter to apply it.
	 *
	 * @since     1.0.1
	 * @return    string    XML output used for Xero.
	 */
    public function currency_edit( $xml_input ) {
        
        // Debug Log
        $this->log('PLUGIN START - '.$this->plugin_name.' ('.$this->version.')');
        
        // This is the Stripe Primary Currency Code 
        $xero_stripe_currency = get_option('wc_xero_dfc_stripe_currency', '');

        if (empty($xero_stripe_currency)) {

            // DEBUG
            $this->log("> Abort Stripe Currency -> No Xero Stripe Currency Code Set");

            return $xml_input;
        }

        // Read XML to object, with Wrappers, otherwise the xml parser panics...
        $xml = new SimpleXMLElement('<root>'.$xml_input.'</root>');

        // Object to Array (the dirty way)
        $data_array = json_decode(json_encode($xml),true);

        // Get Order ID from Data
        $arr = explode('post.php?post=', $data_array['Invoice']['Url']);
        $order_id = $arr[1];
        $arr = explode('&', $order_id);
        $order_id = $arr[0];

        if (empty($order_id)) {

            // DEBUG
            $this->log("> Abort Stripe Currency -> Unable to find order id" );

            return $xml_input;
        }


        // Not using Stripe, lets abort
        if ('stripe' !== get_post_meta( $order_id, '_payment_method', true ) ) {

            // DEBUG
            $this->log("> Abort Stripe Currency -> Payment Method (id: ".$order_id."): ".get_post_meta( $order_id, '_payment_method', true ) );

            return $xml_input;
        }

        // Has the payment been proccssed? (to get Stripe Fee)
        $stripe_fee = get_post_meta( $order_id , '_stripe_fee', true );
        $stripe_amount = get_post_meta( $order_id , '_stripe_net', true );

        // No fee found? lets abort
        if (empty($stripe_fee)) {
            
            // Add Order Note
            $this->add_order_note($order_id,
                apply_filters( 'wc_xero_stripe_currency_not_found_text' , __('ERROR: No Stripe Fee found for order','woocommerce-xero-stripe-currency'))
            );

            // DEBUG
            $this->log("> Abort Stripe Currency -> No Stripe Fee Found" );

            return $xml_input;
        }
        
        // No stripe net amount found? lets abort
        if (empty($stripe_amount)) {
            
            // Add Order Note
            $this->add_order_note($order_id,
                apply_filters( 'wc_xero_stripe_currency_not_found_net_text' , __('ERROR: No Stripe NET Amount found for order','woocommerce-xero-stripe-currency'))
            );

            // DEBUG
            $this->log("> Abort Stripe Currency -> No Stripe Amount Found" );

            return $xml_input;
        }

        
        // What Currency was this charged in?
        $order_currency = $data_array['Invoice']['CurrencyCode'];

        // Lets find the currency conversion percentage, based on total and stripe
        $stipe_currency_point = ($stripe_fee + $stripe_amount) / $data_array['Invoice']['Total'];
        
        //DEBUG
        $this->log(' > Stripe Currency Point: '.$stipe_currency_point);

        // Are Foreign Currency transactions tax free?
        if ('on' == get_option('wc_xero_dfc_stripe_currency_tax', false)) {
            $stipe_currency_taxfree = true;
        } else {
            $stipe_currency_taxfree = false;
        }
        
        //DEBUG
        $this->log('RAW: '.json_encode($data_array['Invoice']['LineItems']));

        // Setup the Group
        $group = array();

        // How many items to we have in the order?
        if ($this->has_string_keys($data_array['Invoice']['LineItems']['LineItem'])) {
            
            $order_items_i = count($data_array['Invoice']['LineItems']);
        } else {
            
            $order_items_i = count($data_array['Invoice']['LineItems']['LineItem']);
        }
        
        // Set Total 
        $stripe_currency_invoice_total = 0;
        
        // DEBUG
        $this->log('Amount of Items: '.$order_items_i);

        // If more then 1, use the group method, else just add.
        if (1 < $order_items_i) {
            // Split the products
            foreach($data_array['Invoice']['LineItems']['LineItem'] as $item) { 
                
                // DEBUG STUFF
                $this->log( "   Item Start" );
                $this->log( "    > ".json_encode($item) );
                $this->log( "   Item  End" );
                
                // Is the Foreign currency tax free? (most places, depending on your business)
                if ($stipe_currency_taxfree) {
                    
                    // Add the unit amount and per unit tax amount, tax is total of all quantities combined
                    $tmp = ( $item['UnitAmount'] + ( $item['TaxAmount'] / $item['Quantity'] ) );

                    // Keep the orginal amount for the description
                    $tmp_org = $tmp;

                    // Lets Apply the currency conversion (based on total and stripe conversions)
                    $tmp = $tmp * $stipe_currency_point;
                    
                    // Merge it back into the line item
                    $item['UnitAmount'] = "$tmp";
                    $item['TaxAmount'] = "0";
                    
                } else {
                    $tmp = $item['UnitAmount'] * $stipe_currency_point;
                    
                    // Keep the orginal amount for the description
                    $tmp_org = $item['UnitAmount'];

                    // Add the tax into local currency (asumes the tax rate is the same, will fix this later)
                    $tmp_tax = $item['TaxAmount']  * $stipe_currency_point;
                    
                    // Merge it back into the line item
                    $item['UnitAmount'] = "$tmp";
                    $item['TaxAmount'] = "$tmp_tax";
                }
                
                // Add orginal amount currency to description (eg; Product1 (20 EUR) )
                $item['Description'] .= ' '.apply_filters( 'wc_xero_stripe_currency_line_text' , 
                    sprintf(esc_html__('(%1$s %2$s)','woocommerce-xero-stripe-currency'), round($tmp_org,2) , $order_currency)
                );
                
                // More Tax Stuff, lets make sure if no tax is needed we tell Xero
                if ($stipe_currency_taxfree) {
                    $item['TaxType'] = "NONE";
                } else {

                    // Sets Tax Type to New Zealand GST on Income (as per XERO docs), TODO: make this dynamic
                    $item['TaxType'] = "OUTPUT2";
                }
                
                // DEBUG STUFF
                $this->log( "   Item MOD Start" );
                $this->log( "    > ".json_encode($item) );
                $this->log( "   Item MOD End" );
                
                // Is the Item NOT a rounding Adjustment? (no point when it is worked into the currency conversion)
                if ('Rounding adjustment' !== substr($item['Description'],0,19)) {
                    
                    // Add the Totals up
                    $stripe_currency_invoice_total += $item['Quantity'] * $tmp;
                    
                    // Add the Tax to the total (if tax is included)
                    if ($stipe_currency_taxfree) {
                        $stripe_currency_invoice_total += $tmp_tax;
                    }
                    
                    // Merge it back into the the rest of the line items
                    $group[] = $item;
                }
                
                
            }
        } else {
            
            // TMP
            $item = $data_array['Invoice']['LineItems']['LineItem'];
            
            // DEBUG STUFF
            $this->log( "   Item Start" );
            $this->log( "    > ".json_encode($item) );
            $this->log( "   Item  End" );

            // Is the Foreign currency tax free? (most places, depending on your business)
            if ($stipe_currency_taxfree) {

                // Add the unit amount and per unit tax amount, tax is total of all quantities combined
                $tmp = ( $item['UnitAmount'] + ( $item['TaxAmount'] / $item['Quantity'] ) );  
                
                // Keep the orginal amount for the description
                $tmp_org = $tmp;
                    
                // Lets Apply the currency conversion (based on total and stripe conversions)
                $tmp = $tmp * $stipe_currency_point;
                
                // Add it to the total
                $stripe_currency_invoice_total += $tmp;

                // Merge it back into the line item
                $item['UnitAmount'] = "$tmp";
                $item['TaxAmount'] = "0";

            } else {
                $tmp = $item['UnitAmount'] * $stipe_currency_point;
                
                // Keep the orginal amount for the description
                $tmp_org = $item['UnitAmount'];

                // Add the tax into local currency (asumes the tax rate is the same, will fix this later)
                $tmp_tax = $item['TaxAmount']  * $stipe_currency_point;

                // Merge it back into the line item
                $item['UnitAmount'] = "$tmp";
                $item['TaxAmount'] = "$tmp_tax";
                
                // Add it to the total
                $stripe_currency_invoice_total += ($tmp + $tmp_tax);
                
            }
            
            // Add orginal amount currency to description (eg; Product1 (20 EUR) )
            $item['Description'] .= ' '.apply_filters( 'wc_xero_stripe_currency_line_text' , 
                sprintf(esc_html__('(%1$s %2$s)','woocommerce-xero-stripe-currency'), round($tmp_org,2) , $order_currency)
            );

            // More Tax Stuff, lets make sure if no tax is needed we tell Xero
            if ($stipe_currency_taxfree) {
                $item['TaxType'] = "NONE";
            } else {

                // Sets Tax Type to New Zealand GST on Income (as per XERO docs), TODO: make this dynamic
                $item['TaxType'] = "OUTPUT2";
            }
            
            // DEBUG STUFF
            $this->log( "   Item MOD Start" );
            $this->log( "    > ".json_encode($item) );
            $this->log( "   Item MOD End" );
            
            
            // Just add the product (just 1) to the group
            $group[] = $item;
        }


        // Add the Line Item per Array (for XML Keys) (if more then 1 product)
        if (1 < $order_items_i) {
            $group = array('LineItem'=>$group);
        }

        // Merge it back into the main Data Stream
        $data_array['Invoice']['LineItems'] = $group;

        //DEBUG
        $this->log('MERGED: '.json_encode($data_array['Invoice']['LineItems']));

        // Change the Total to the Stripe Currency and do tax calculations
        if ($stipe_currency_taxfree) {
            $data_array['Invoice']['Total'] = ( $data_array['Invoice']['Total'] + $data_array['Invoice']['TotalTax'] ) * $stipe_currency_point;
            $data_array['Invoice']['TotalTax'] = 0;
        } else {
            $data_array['Invoice']['Total'] = $data_array['Invoice']['Total'] * $stipe_currency_point;
            $data_array['Invoice']['TotalTax'] = data_array['Invoice']['TotalTax'] * $stipe_currency_point;
        }
        
        // Check if the totals match, rounding can cause it to be out by a few cents
        $stripe_total = $stripe_fee + $stripe_amount;
        if ($stripe_total !== round($stripe_currency_invoice_total,2)) {
            
            // Not the same, lets see by how much we are out
            if ( ($stripe_currency_invoice_total-$stripe_total) > 0.1) {
                
                // Greater then 10 cents (XERO's limit), abort
                
                // Add Order Note
                $this->add_order_note($order_id,
                    apply_filters( 'wc_xero_stripe_currency_total_mismatch_text' , __('ERROR: Total rounding too high','woocommerce-xero-stripe-currency'))
                );

                // DEBUG
                $tmp = $data_array['Invoice']['Total'] - $stripe_total;
                $this->log("> Stripe  Total: $stripe_total" );
                $this->log("> AUTO Invoice Total: ".$data_array['Invoice']['Total'] );
                $this->log("> MAN  Invoice Total: ".$stripe_currency_invoice_total );
                $this->log("> Abort Stripe Currency -> Total rounding too high ($tmp)" );
                
                //throw new Exception('DEBUG ROUNDING');

                return $xml_input;
                
            } else {
                
                // Correct the total 
                $stripe_currency_invoice_total -=  $stripe_total;
                $data_array['Invoice']['Total'] = "$stripe_currency_invoice_total";
                
                // DEBUG
                $this->log("> NOTICE: Rounding adjustments made of ".$data_array['Invoice']['Total']-$stripe_total );
            } 
        }


        // Change it back to a string (xml issues otherwise...)
        $data_array['Invoice']['TotalTax'] = (string)$data_array['Invoice']['TotalTax'];

        // Change the Xero Invoice to the Xero Supported Currency
        $data_array['Invoice']['CurrencyCode'] = $xero_stripe_currency;
        
        

        /*

        // Make the changes to the XML Data
        if (array_key_exists('Invoice',$data_array)) {
            if (array_key_exists('LineItems',$data_array['Invoice'])) {
                if (array_key_exists('Total',$data_array['Invoice'])) {

                    // What Currency was this charged in?
                    $order_currency = $data_array['Invoice']['CurrencyCode'];

                    // Lets find the currency conversion percentage, based on total and stripe
                    $stipe_currency_point = ($stripe_fee_org + $stripe_amount_org) / $data_array['Invoice']['Total'];

                    // Debug
                    $this->log("> Currency Point: ".$stipe_currency_point." ( ( ".$stripe_amount_org." + ".$stripe_fee_org.") / ".$data_array['Invoice']['Total']." )");
                    
                    
                    // Set TMP ARRAY
                    $dd = array('LineItems');
                    
                    // DEBUG
                    $this->log( "> Line Items" );

                    // Grab Items in the Order
                    foreach($data_array['Invoice']['LineItems'] as $item) {

                        // Change the totals to Stripe Primary Currency
                        $this->log( "   Item Start" );
                        $this->log( "   ".json_encode($item) );
                        $this->log( "   Item End" );

                        // {"Description":"MonthlySite","AccountCode":"750","UnitAmount":"86.96","Quantity":"1","TaxType":"TAX003","TaxAmount":"13.04"}

                        if ($stipe_currency_taxfree) {
                            // No Tax, lets add it into the total amount (incase the shop has tax enabled)
                            $tmp = $item['UnitAmount'] + $item['TaxAmount'];
                        } else {
                            $tmp = $item['UnitAmount'];
                        }


                        // Add orginal amount currency to description (eg; Product1 (20 EUR) )
                        $item['Description'] .= ' '.apply_filters( 'wc_xero_stripe_currency_line_text' , 
                                                                  sprintf(esc_html__('(%1$s %2$s)','woocommerce-xero-stripe-currency'), $tmp , $order_currency)
                                                                 );

                        // Lets Apply the currency conversion (based on total and stripe conversions)
                        if ($stipe_currency_taxfree) {
                            $tmp = ( $item['UnitAmount'] + $item['TaxAmount'] ) * $stipe_currency_point;
                        } else {
                            $tmp = $item['UnitAmount'] * $stipe_currency_point;

                            // Add the tax into local currency (asumes the tax rate is the same, will fix this later)
                            $tmp_tax = $item['TaxAmount']  * $stipe_currency_point;

                        }
                        
                        // Sets the unit amount as a String (XML issues otherwise...)
                        $item['UnitAmount'] = "$tmp";

                        // Also make the TAX Inclusive as this is Tax Exempt due to overseas customer
                        if (!$stipe_currency_taxfree) {

                            // Remove Tax
                            $tmp_tax = 0.00;  
                        } 
                        
                        $item['TaxAmount'] = "$tmp_tax";
                        

                        if ($stipe_currency_taxfree) {
                            $item['TaxType'] = "NONE";
                        } else {

                            // Sets Tax Type to New Zealand GST on Income (as per XERO docs), TODO: make this dynamic
                            $item['TaxType'] = "OUTPUT2";
                        }

                        // DEBUG STUFF
                        $this->log( "   Item MOD Start" );
                        $this->log( "   ".json_encode($item) );
                        $this->log( "   Item  MOD End" );
                        
                        // Add it to the TMP array
                        $dd['LineItems'][] = $item;
                    }
                    
                    // DEBUG
                    $this->log( "> End of Line Items" );


                    // Merge it back into the main Data Stream
                    $data_array['Invoice']['LineItems'] = $dd['LineItems'];

                    // Change the Total to the Stripe Currency and do tax calculations
                    if ($stipe_currency_taxfree) {
                        $data_array['Invoice']['Total'] = ( $data_array['Invoice']['Total'] + $data_array['Invoice']['TotalTax'] ) * $stipe_currency_point;
                        $data_array['Invoice']['TotalTax'] = 0;
                    } else {
                        $data_array['Invoice']['Total'] = $data_array['Invoice']['Total'] * $stipe_currency_point;
                        $data_array['Invoice']['TotalTax'] = data_array['Invoice']['TotalTax'] * $stipe_currency_point;
                    }


                    // Change it back to a string (xml issues otherwise...)
                    $data_array['Invoice']['Total'] = (string)$data_array['Invoice']['Total'];
                    $data_array['Invoice']['TotalTax'] = (string)$data_array['Invoice']['TotalTax'];

                    // Change the Xero Invoice to the Xero Supported Currency
                    $data_array['Invoice']['CurrencyCode'] = $xero_stripe_currency;
            
                }

            }
        }

        */

        // Create XML
        $xml_output = DFC_XML::arrayToXML($data_array);
        
        // Safeguard for empty tags in Tax Amount (array issues) - TODO avoid getting them in the first instance
        $xml_output = str_replace("<TaxAmount></TaxAmount>", "<TaxAmount>0</TaxAmount>", $xml_output);
        
        // Safeguard for <0> and </0> tags (array issues) - TODO avoid getting them in the first instance
        $xml_output = str_replace("<0>", "", $xml_output);
        $xml_output = str_replace("</0>", "", $xml_output);
        
        // Dirty Safeguard for double/triple/quadruple LineItem tags (array issues) - TODO avoid getting them in the first instance
        $xml_output = str_replace("<LineItem><LineItem><LineItem><LineItem>", "<LineItem>", $xml_output);
        $xml_output = str_replace("</LineItem></LineItem></LineItem></LineItem>", "</LineItem>", $xml_output);
        $xml_output = str_replace("<LineItem><LineItem><LineItem>", "<LineItem>", $xml_output);
        $xml_output = str_replace("</LineItem></LineItem></LineItem>", "</LineItem>", $xml_output);
        $xml_output = str_replace("<LineItem><LineItem>", "<LineItem>", $xml_output);
        $xml_output = str_replace("</LineItem></LineItem>", "</LineItem>", $xml_output);
            
        // DEBUG
        $this->log('> XML Returned');
        $this->log('  '.$xml_output);
        $this->log('PLUGIN END - '.$this->plugin_name.' ('.$this->version.')');
        
        //throw new Exception('DEBUG');
        
        // Add Order Note
        $this->add_order_note($order_id, apply_filters( 'wc_xero_stripe_currency_order_note' , 
                                        sprintf(esc_html__('Converted order to %s for Xero Invoice','woocommerce-xero-stripe-currency'), $xero_stripe_currency
                                               )));

        // Give it back to Woocommerce Xero Extension to Send
        return $xml_output;
        
    }
    
    /**
	 * Debug functions. 
	 * 
	 * Sends it to main class debug logger
	 *
	 * @since    1.0.1
	 */
    private function log($message='') {
        Woocommerce_Xero_Stripe_Currency::log($message);
    }
    
    /**
     * Data Validation of ARRAY
     * 
     * The values in this arrays contains the names of the indexes (keys) that should 
     * exist in the data array. Returns true if all matching
     *
     * @since    1.0.1
     * @returns boolean
     * @credit https://stackoverflow.com/posts/18250308/revisions
     * 
     */
    private function array_validate( $needed_keys , $data) {

        // Check input is an array and not empty
        if (!is_array($needed_keys) || !is_array($data) || empty($needed_keys) || empty($data)) {
            return false;
        }

        // Check if the needed keys are present in the array
        if(count(array_intersect_key(array_flip($needed_keys), $data_array)) === count($needed_keys)) {
            // Yep all keys present!
            return true;
        }

        return false;

    }
    
    /**
	 * Add private order note 
	 * 
	 * Creates a new note for the order id
	 *
	 * @since    1.0.1
	 */
    private function add_order_note( $order_id , $note ) {
        // Add order note 
        $order      = wc_get_order( $order_id );
        $comment_id = $order->add_order_note( $note );
    }
    
    /**
	 * Checks whether the array has non-integer keys 
	 * 
	 * (not whether the array is sequentially-indexed or zero-indexed)
	 *
	 * @since    1.0.1
	 * @credit   https://stackoverflow.com/a/4254008
	 */
    function has_string_keys(array $array) {
      return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
    
    /**
	 * Add Settings link to plugin 
	 * 
	 * Creates a settings link on the plugins list
	 *
	 * @since    1.0.1
	 */
    public function plugin_links( $links ) {
        
       array_unshift($links, '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=woocommerce_xero') ) .'">'.__( 'Settings' ).'</a>');
        
       return $links;
    }

}
