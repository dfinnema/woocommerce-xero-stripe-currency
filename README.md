# Woocommerce Xero Stripe Currency
This is a Plugin for Wordpress using **Woocommerce** with the **Woocommerce Xero** Extension and **Stripe**

Converts the order amounts to your Stripe currency for easier reconciliation in Xero (if using the same currency as Stripe)

**Please note this is a work in progress and probably has a lot of bugs in it. The code is quick and dirty so feel free to add / change to it.**
### Features

  - NZ GST support only at this time
  - Keeps orginal currency amounts in description
  - Any Stripe currency is supported
  - Also works with the 'woocommerce-xero-stripe-fees' plugin
  
  Note sending payments to Xero is not currently supported for Stripe Currency. Amounts may change slightly due to stripe sending it 7 days later

### Installation

1. Download as .zip and add plugin using the upload function or add the .php file to ```wp-content/plugins/woocommerce-xero-stripe-currency``` 
2. Activate the plugin in Wordpress
3. Add the Xero 'Stripe Currency' account code in ```Woocommerce > Xero``` options page (at the bottom)
4. Test to see if it works (see debug below)

### Requirements

This Plugin requires the following plugins to be active in Wordpress

| Plugin | Link |
| ------ | ------ |
| Woocommerce 3.0 or above | https://woocommerce.com |
| Woocommerce Xero (tested with 1.7.9) | https://woocommerce.com/products/xero/ |

### Bugs

Please create a new issue if you are able to reproduce your bug

### Debug

Enable debug under ```Woocommerce > Xero```

Logs are created in the ```wp-content/uploads/wc-logs``` the filename should start with **xero** followed by a bunch of numbers with the extension of **.log**

### Development

Want to contribute? Great!

Feel free to post an issue or create a pull request. 
