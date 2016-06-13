<?php
/*
Plugin Name: WooCommerce PayEx Gateway
Plugin URI: http://www.woothemes.com/products/payex-payment-gateway/
Description: Extends WooCommerce. Provides a <a href="http://www.payex.com" target="_blank">PayEx</a> gateway for WooCommerce.
Version: 1.2.1
Author: Krokedil
Author URI: http://krokedil.com
*/

/*  Copyright 2013  Niklas Högefjord  (email : info@krokedil.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '103fc3ed4d53f5fe4a4ce388dfbb72ca', '201969' );

add_action('plugins_loaded', 'init_payex_gateway', 0);

function init_payex_gateway() {

	// If the WooCommerce payment gateway class is not available, do nothing
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
	 * Localisation
	 */
	load_plugin_textdomain('payex', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

	class WC_Gateway_Payex extends WC_Payment_Gateway {

		public function __construct() {
			global $woocommerce;

	        $this->has_fields 	= false;
			$this->shop_country	= get_option('woocommerce_default_country');
			$this->log 			= WC_Payex_Compatibility::new_wc_logger();
	    }




		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {

	    	?>
	    	<h3><?php _e('PayEx', 'payex'); ?></h3>

	    		<?php
				// Generate the HTML For the settings form.
				?>
		    	<p><?php printf(__('PayEx works by sending the user to <a href="http://www.payex.com">PayEx</a> to enter their payment information. With PayEx your customers can pay by credit card, via instant bank transactions and invoice. Documentation <a href="%s" target="_blank">can be found here</a>.', 'payex'), 'http://docs.woothemes.com/document/payex/' ); ?></p>
	    		<table class="form-table">
	    		<?php
	    		// Generate the HTML For the settings form.
	    		$this->generate_settings_html();
	    		?>
				</table><!--/.form-table-->

			<?php
	    	

	    } // End admin_options()




	} // End class

	
	// Include the WooCommerce Compatibility Utility class
	// The purpose of this class is to provide a single point of compatibility functions for dealing with supporting multiple versions of WooCommerce (currently 2.0.x and 2.1)
	require_once 'classes/class-wc-payex-compatibility.php';
	
	
	// Include our Payment Menu class
	require_once 'class-payex-pm.php';
	
	// Include our Payment Invoice class
	require_once 'class-payex-invoice.php';


} // End init_payex_gateway

/**
 * Add the gateway to WooCommerce
 **/
function add_payex_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Payex_PM';
	$methods[] = 'WC_Gateway_Payex_Invoice';
	return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_payex_gateway' );
