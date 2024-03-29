# No Longer Supported
This project is no longer maintained. We are no longer responding to Issues or Pull Requests unless they relate to security concerns. We encourage interested developers to fork this project and make it their own, or use the [Stripe + Xero integration](https://www.xero.com/us/partnerships/stripe/) provided by Xero

# Woocommerce Xero Stripe Currency
This is a Plugin for Wordpress using **Woocommerce** with the **Woocommerce Xero** Extension and **Stripe**

Converts the order amounts to your Stripe currency for easier reconciliation in Xero (if using the same currency as Stripe)

**Please note this is a work in progress and probably has a lot of bugs in it. The code is quick and dirty so feel free to add / change to it.**
### Features

  - Keeps original currency amounts in description
  - Any Stripe currency is supported
  
 ** Requires ```woocommerce-xero-stripe-fees``` plugin **
 
### Installation

1. Download as .zip and add plugin using the upload function or add the .php file to ```wp-content/plugins/woocommerce-xero-stripe-currency``` 
2. Activate the plugin in Wordpress
3. Add the Xero 'Stripe Currency' account code in ```Woocommerce > Xero``` options page (at the bottom)
4. Test to see if it works (see debug below)

### Requirements

This Plugin requires the following plugins to be active in Wordpress

| Plugin | Link |
| ------ | ------ |
| Woocommerce 3.6 or above | https://woocommerce.com |
| Woocommerce Xero (tested with 1.7.16) | https://woocommerce.com/products/xero/ |
| Woocommerce Xero Stripe Fees (tested with 2.1) | https://github.com/dfinnema/woocommerce-xero-stripe-fees |

### Bugs

Please create a new issue if you are able to reproduce your bug

### Debug

Enable debug under ```Woocommerce > Xero```

Logs are created in the ```wp-content/uploads/wc-logs``` the filename should start with **xero** followed by a bunch of numbers with the extension of **.log**

### Are you finding this usefull?

Using this plugin or some of the code? Feeling generous and want to say thanks? You can <a href='https://ko-fi.com/A6552UEK' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://az743702.vo.msecnd.net/cdn/kofi2.png?v=0' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>
