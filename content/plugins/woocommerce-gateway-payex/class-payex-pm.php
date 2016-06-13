<?php

class WC_Gateway_Payex_PM extends WC_Gateway_Payex {
	
	/**
     * Class for PayEx P payment.
     *
     */
     
     public function __construct() {	
		parent::__construct();
		$this->id			= 'payex_pm';
		$this->method_title = __('PayEx', 'payex');
		$this->icon 		= apply_filters( 'woocommerce_payex_icon', plugins_url(basename(dirname(__FILE__))."/images/payex.png") );
		
		// Load the form fields.
		$this->init_form_fields();
		
		// Load the settings.
		$this->init_settings();
		
		// Define user set variables
		$this->enabled				= $this->settings['enabled'];
		$this->title 				= $this->settings['title'];
		$this->account_no 			= $this->settings['account_no'];
 		$this->encrypted_key 		= $this->settings['encrypted_key'];
		$this->purchase_operation	= $this->settings['purchase_operation'];
		$this->send_order_lines		= $this->settings['send_order_lines'];
		// $this->send_order_lines		= 'yes';
		// $this->language				= $this->settings['language'];
		$this->testmode				= $this->settings['testmode'];
		$this->debug				= $this->settings['debug'];
		
		$this->shop_country			= get_option('woocommerce_default_country');
		// Check if woocommerce_default_country includes state as well. If it does, remove state
        if (strstr($this->shop_country, ':')) :
        	$this->shop_country = current(explode(':', $this->shop_country));
        else :
        	$this->shop_country = $this->shop_country;
        endif;
		// Country and language
		
		switch ( $this->shop_country )
		{
		case 'DK':
			$payex_language = 'da-DK';
			break;
		case 'DE' :
			$payex_language = 'de-DE';
			break;
		case 'ES' :
			$payex_language = 'es-ES';
			break;
		case 'NO' :
		case 'NB' :
			$payex_language = 'nb-NO';
			break;
		case 'FI' :
			$payex_language = 'fi-FI';
			break;
		case 'SE' :
		case 'SV' :
			$payex_language = 'sv-SE';
			break;
		case 'GB' :
		case 'US' :
			$payex_language = 'en-US';
			break;
		default:
			$payex_language = 'en-US';
			
		}
		
		$this->language = $payex_language;
		
		// Test or Live?
 		if($this->testmode == 'yes'){
	 		$this->PxOrderWSDL = "https://test-external.payex.com/pxorder/pxorder.asmx?wsdl";
	 		$this->PxConfinedWSDL = "https://test-confined.payex.com/PxConfined/pxorder.asmx?wsdl";
	 		$this->PxAgreementWSDL = "https://test-external.payex.com/pxagreement/pxagreement.asmx?WSDL";
	 		$this->PxRecurringWSDL = "https://test-external.payex.com/pxagreement/pxrecurring.asmx?WSDL";
	 	} else {
		 	$this->PxOrderWSDL = "https://external.payex.com/pxorder/pxorder.asmx?wsdl";
	        $this->PxConfinedWSDL = "https://confined.payex.com/PxConfined/pxorder.asmx?wsdl";
	        $this->PxAgreementWSDL = "https://external.payex.com/pxagreement/pxagreement.asmx?WSDL";
	        $this->PxRecurringWSDL = "https://external.payex.com/pxagreement/pxrecurring.asmx?WSDL";
	    }
	    
	    
	    // Subscription support
	    $this->supports = array( 
               'products', 
               'subscriptions',
               'subscription_cancellation', 
               'subscription_suspension', 
               'subscription_reactivation',
               'subscription_amount_changes',
               'subscription_date_changes',
               'subscription_payment_method_change'
          );
				
		// Actions
		//add_action( 'woocommerce_api_wc_gateway_payex_pm', array(&$this, 'check_payex_response') );
		
		add_action( 'valid-payex-request', array(&$this, 'successful_request') );
		add_action( 'woocommerce_receipt_payex_pm', array(&$this, 'receipt_page') );
		
		/* 1.6.6 */
		add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
 
		/* 2.0.0 */
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		
		// Subscriptions
		add_action( 'scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 3 );
		
		// When a subscriber or store manager cancel's a subscription in the store, suspend it with PayEx
		add_action( 'cancelled_subscription_' . $this->id, array( $this, 'delete_agreement' ), 10, 2 );
		
		//woocommerce_subscriptions_changed_failing_payment_method_{your-gateway}
		add_action( 'woocommerce_subscriptions_changed_failing_payment_method_' . $this->id, array( $this, 'update_failing_payment_method' ), 10, 2 );
		
		//add_action( 'woocommerce_receipt_payex_pm', array(&$this, 'scheduled_subscription_payment') );
	}
		
		
		/**
		 * Update the customer token IDs for a subscription after a customer used the gateway to successfully complete the payment
		 * for an automatic renewal payment which had previously failed.
		 *
		 * @param WC_Order $original_order The original order in which the subscription was purchased.
		 * @param WC_Order $renewal_order The order which recorded the successful payment (to make up for the failed automatic payment).
		 * @return void
		 */
		function update_failing_payment_method( $original_order, $renewal_order ) {
			
			update_post_meta( $original_order->id, 'payex_agreement_ref', get_post_meta( $renewal_order->id, 'payex_agreement_ref', true ) );
		}

		/**
		* Check if this gateway is enabled and available in the user's country
		*/
		
		function is_available() {
			global $woocommerce;
			
			if ($this->enabled=="yes") :
							
				// Currency check
				//if (!in_array(get_option('woocommerce_currency'), array('EUR', 'SEK', 'DKK', 'NOK'))) return false;
				
				// Base country check
				// if (!in_array(get_option('woocommerce_default_country'), array('FI', 'SE'))) return false;
				
				// Required fields check
				if (!$this->account_no || !$this->encrypted_key) return false;
				
				return true;
						
			endif;	
		
			return false;
		}
		

		/**
	     * Initialise Gateway Settings Form Fields
	     */
	    function init_form_fields() {
	    
	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'payex' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable PayEx', 'payex' ), 
								'default' => 'yes'
							), 
				'title' => array(
								'title' => __( 'Title', 'payex' ), 
								'type' => 'text', 
								'description' => __( 'This controls the title which the user sees during checkout.', 'payex' ), 
								'default' => __( 'PayEx', 'payex' )
							),
				'description' => array(
								'title' => __( 'Description', 'payex' ), 
								'type' => 'textarea', 
								'description' => __( 'This controls the title which the user sees during checkout.', 'payex' ), 
								'default' => __( '', 'payex' ),
							),
				'account_no' => array(
								'title' => __( 'Account Number', 'payex' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your PayEx Account Number; this is needed in order to take payment!', 'payex' ), 
								'default' => __( '', 'payex' )
							),
				'encrypted_key' => array(
								'title' => __( 'Encrypted Key', 'payex' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your PayEx Encrypted Key; this is needed in order to take payment!', 'payex' ), 
								'default' => __( '', 'payex' )
							),
				'purchase_operation' => array(
								'title' => __( 'Purchase Operation', 'payex' ), 
								'type' => 'select',
								'options' => array('AUTHORIZATION'=>'Authorization', 'SALE'=>'Sale'),
								'description' => __( 'If AUTHORIZATION is submitted, this indicates that the order will be a 2-phased transaction if the payment method supports it. SALE is a 1-phased transaction.', 'payex' ), 
								'default' => 'SALE'
							),
				'send_order_lines' => array(
								'title' => __( 'Send Order Lines', 'payex' ), 
								'type' => 'checkbox', 
								'label' => __( 'Send Each Cart Item as Separate Order Line.', 'payex' ), 
								'default' => 'no'
							),
				/*'language' => array(
								'title' => __( 'Language', 'payex' ), 
								'type' => 'select',
								'options' => array('SV'=>'Swedish', 'EN'=>'English', 'FI'=>'Finnish'),
								'description' => __( 'Locale of pages displayed by payex during payment.', 'payex' ), 
								'default' => 'SV'
							),
				*/
				'testmode' => array(
								'title' => __( 'Test Mode', 'payex' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable PayEx Test Mode.', 'payex' ), 
								'default' => 'no'
							),
				'debug' => array(
								'title' => __( 'Debug', 'payex' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable logging (<code>woocommerce/logs/payex.txt</code>)', 'payex' ), 
								'default' => 'no'
							)
				);
	    
	    } // End init_form_fields()
	    
	    	    
	    /**
		 * Process the payment and return the result
		 **/
		function process_payment( $order_id ) {
			global $woocommerce;
			
			$order = new WC_Order( $order_id );
			
			
			/*
 			 * Step 1: Set up details
 			 */
   
	        //$_server won't work if run from console.
			$this->clientIPAddress = $_SERVER['REMOTE_ADDR'];		
			$this->clientIdentifier = "USERAGENT=".$_SERVER['HTTP_USER_AGENT'];
 			
 			// We manually calculate the tax percentage here
 			
 			
 			// Subscription payment
 			if ( class_exists( 'WC_Subscriptions_Order' ) && WC_Subscriptions_Order::order_contains_subscription( $order_id ) ) {
 				
 				$status = $this->process_subscription_agreement($order, $order_id);
 			
 			// Regular payment
 			} else {
	 			
	 			$status = $this->process_regular_payment($order, $order_id);
 			}
 			
 			
 			
 			
			
			/*
 			* Verify that it suceeded
 			*/
 			
			// if code & description & errorCode is OK, redirect the user
			if($status['code'] == "OK" && $status['errorCode'] == "OK" && $status['description'] == "OK") {
				
				if ($this->debug=='yes') $this->log->add( 'payex', 'Sending order details to PayEx...' );
				
				//var_dump($status);
				//die();
				// Test med orderrader

				if( $this->send_order_lines == 'yes' || ( class_exists( 'WC_Subscriptions_Order' ) && WC_Subscriptions_Order::order_contains_subscription( $order_id ) ) ) {
								
					// Cart Contents
					$item_loop = 1;
					if (sizeof($order->get_items())>0) : foreach ($order->get_items() as $item) :
				
						$tmp_sku = '';
				
						if ( function_exists( 'get_product' ) ) {
				
							// Version 2.0
							$_product = $order->get_product_from_item($item);
							
							// Get SKU or product id
							if ( $_product->get_sku() ) {
								$tmp_sku = $_product->get_sku();
							} else {
								$tmp_sku = $_product->id;
							}
					
						} else {
				
							// Version 1.6.6
							$_product = new WC_Product( $item['id'] );
							
							// Get SKU or product id
							if ( $_product->get_sku() ) {
								$tmp_sku = $_product->get_sku();
							} else {
								$tmp_sku = $item['id'];
							}
					
						}
			
						if ($_product->exists() && $item['qty']) :
							
							// We manually calculate the tax percentage here
							if ($order->get_total_tax() >0) :
								// Calculate tax percentage
								$item_tax_percentage = number_format( ( $order->get_line_tax($item) / $order->get_line_total( $item, false ) )*100, 2, '.', '');
							else :
								$item_tax_percentage = 00;
							endif;
							
							//Collect the item info
							$params = array	(
								'accountNumber' => $this->account_no,
								'orderRef' => $status['orderRef'],
								'itemNumber' => $tmp_sku,
								'itemDescription1' => $item['name'],
								'itemDescription2' => '',
								'itemDescription3' => '',
								'itemDescription4' => '',
								'itemDescription5' => '',
								'quantity' => (int)$item['qty'],
								'amount' => number_format(($order->get_item_total( $item, true )*$item['qty']), 2, '.', '')*100,
								'vatPrice' => $order->get_line_tax($item)*100, // Vat
								'vatPercent' => round( $item_tax_percentage, 1)*100, // Vat
							);
							
							// Pass item info to PayEx
							$result = $this->AddSingleOrderLine2($params);

						endif;
					endforeach; endif;
			
			
					// Shipping Cost
					if (WC_Payex_Compatibility::get_total_shipping($order)>0) :
				
						// We manually calculate the shipping tax percentage here
						$calculated_shipping_tax_percentage = ($order->order_shipping_tax/$order->order_shipping)*100; //25.00
						$calculated_shipping_tax_decimal = ($order->order_shipping_tax/$order->order_shipping)+1; //0.25
						$calculated_shipping_price_including_tax = $order->order_shipping*$calculated_shipping_tax_decimal;
						
						//Collect the shipping info
						$params = array	(
							'accountNumber' => $this->account_no,
							'orderRef' => $status['orderRef'],
							'itemNumber' => '0',
							'itemDescription1' => __('Shipping cost', 'payex'),
							'itemDescription2' => '',
							'itemDescription3' => '',
							'itemDescription4' => '',
							'itemDescription5' => '',
							'quantity' => (int)1,
							'amount' => number_format($calculated_shipping_price_including_tax, 2, '.', '')*100,
							'vatPrice' => number_format($order->order_shipping_tax, 2, '.', '')*100, // Vat
							'vatPercent' => round( $calculated_shipping_tax_percentage, 1)*100, // Vat
						);
						
						// Pass shipping info to PayEx
						$result = $this->AddSingleOrderLine2($params);

					endif;


					// Discount
					if ($order->get_order_discount()>0) :
				
						//Collect the dicount info
						$params = array	(
							'accountNumber' => $this->account_no,
							'orderRef' => $status['orderRef'],
							'itemNumber' => '0',
							'itemDescription1' => __('Discount', 'payex'),
							'itemDescription2' => '',
							'itemDescription3' => '',
							'itemDescription4' => '',
							'itemDescription5' => '',
							'quantity' => (int)1,
							'amount' => -number_format($order->order_discount, 2, '.', '')*100,
							'vatPrice' => 0, // Vat
							'vatPercent' => 0, // Vat
						);
							
						// Pass discount info to PayEx
						$result = $this->AddSingleOrderLine2($params);

					endif;

				} // End if $this->send_order_lines
				
				//$status = $this->checkStatus($result);
				//var_dump($status);
				//die();
				
				
				/*
     			 * Step 4: forward user
     			 */
     			 
     			 
     			 return array(
					'result' 	=> 'success',
					'redirect'	=> (string)$status['redirectUrl']
				);
				
				//var_dump($status);
				//header('Location: '.$status['redirectUrl']);
				//echo $result;
				//echo $status['redirectUrl'];
			} else {
				
				// Something was wrong
				$payex_error_message = '';
				foreach($status as $error => $value) { 
					$payex_error_message .= $error . ' ' . $value . ' <br/>';
					//echo "$error, $value"."\n";
					if ($this->debug=='yes') $this->log->add( 'payex', 'Error response from PayEx: ' . $error . ', ' . $value );
					//print_r($status);
					
				}
				
				$order->add_order_note( sprintf(__('PayEx error: %s', 'payex'), $payex_error_message) );
				WC_Payex_Compatibility::wc_add_notice(sprintf(__('PayEx error: %s', 'payex'), $payex_error_message), 'error');
				
				
			}


		}
		

		
		
		/**
		 * Process the regular payment and return the result
		*/
		function process_regular_payment($order, $order_id) {
		
		$params = array	(
				'accountNumber' => $this->account_no,
				'purchaseOperation' => $this->purchase_operation, // AUTHORIZATION or SALE
				'price' => (int)number_format($order->order_total, 2, '', ''),
				'priceArgList' => '', // No CPA, VISA,
				'currency' => get_woocommerce_currency(),
				'vat' => 0, // Vat
				'orderID' => $order_id, // Local order id
				//'orderID' => $order->get_order_number(), // Local order id
				'productNumber' => 'Order number ' . $order->get_order_number(), // Local product number
				'description' => ' ', // Product description
				'clientIPAddress' => $this->clientIPAddress,
				'clientIdentifier' => $this->clientIdentifier,
				'additionalValues' => '',
				'externalID' => '',
				'returnUrl' => $this->get_return_url( $order ),
				'view' => 'CREDITCARD', // Payment method PayEx
				'agreementRef' => '',
				'cancelUrl' => $order->get_cancel_order_url(),
				'clientLanguage' => $this->language
			);
			
			
			
 			/*
			 * Step 2 initiate payment
			 */ 
			
			// $pxorder = new pxorder();
			// $functions = new functions();
			
			
			$result = $this->initialize8( $params );
			// echo $result;
			
			
			$status = $this->checkStatus($result);
			
			return $status;
			
		} // End function process_regular_payment
		
		
		/**
		 * Process the subscription payment and return the result
		*/
		function process_subscription_agreement($order, $order_id) {
			//Get Subscription item	
 								
				$order_items = $order->get_items();
				$product = $order->get_product_from_item( array_shift( $order_items ) );
				
 				$params = array	(
					'accountNumber' => $this->account_no,
					'merchantRef' => $order->get_order_number(),
					'description' => sprintf( __( 'Initial subscription payment for "%s"', 'payex' ), $product->get_title() ), // Product description
					'purchaseOperation' => $this->purchase_operation, // AUTHORIZATION or SALE
					'maxAmount' => (int)number_format(WC_Subscriptions_Order::get_price_per_period( $order ), 2, '', '')*2,
					'notifyUrl' => '', //
					'startDate' => '',
					'stopDate' => ''
				);
				
				$result = $this->CreateAgreement3($params);
				$status = $this->checkStatus($result);
				
				if($status['code'] !== "OK" && $status['errorCode'] !== "OK" && $status['description'] !== "OK") {
				
					if ($this->debug=='yes') {
					
						foreach($status as $error => $value) { 
							$payex_error_message .= $error . ' ' . $value . ' <br/>';
						}
						$this->log->add( 'payex', 'CreateAgreement3 error response: ' .  $payex_error_message );
						WC_Payex_Compatibility::wc_add_notice(sprintf(__('PayEx error: %s', 'payex'), $payex_status_message), 'error');
						return;
				
					}
				}
				
				// Store the Agreement ref in order as post meta
				update_post_meta( $order_id, 'payex_agreement_ref', $status['agreementRef']);
				$order->add_order_note( sprintf(__('PayEx subscription Agreement ref: %s', 'payex'), $status['agreementRef']) );
				
				if( WC_Subscriptions_Order::get_total_initial_payment( $order ) == 0 ) {
					$price = 1;
				} else {
					$price = WC_Subscriptions_Order::get_total_initial_payment( $order );
				}
				
				$params = array	(
				'accountNumber' => $this->account_no,
				'purchaseOperation' => $this->purchase_operation, // AUTHORIZATION or SALE
				'price' => (int)number_format($price, 2, '', ''),
				//'price' => (int)number_format(WC_Subscriptions_Order::get_total_initial_payment( $order ), 2, '', ''),
				'priceArgList' => '', // No CPA, VISA,
				'currency' => get_woocommerce_currency(),
				'vat' => 0, // Vat
				'orderID' => $order_id, // Local order id
				'productNumber' => 'Order number ' . $order->get_order_number(), // Local product number
				'description' => ' ', // Product description
				'clientIPAddress' => $this->clientIPAddress,
				'clientIdentifier' => $this->clientIdentifier,
				'additionalValues' => '',
				'externalID' => '',
				'returnUrl' => $this->get_return_url( $order ),
				'view' => 'CREDITCARD', // Payment method PayEx
				'agreementRef' => $status['agreementRef'],
				'cancelUrl' => $order->get_cancel_order_url(),
				'clientLanguage' => $this->language
			);
			
			
			
 			/*
			 * Step 2 initiate payment
			 */ 
			
			//$pxorder = new pxorder();
			//$functions = new functions();
			
			
			$result = $this->initialize8( $params );
			//echo $result;
			
			
			$status = $this->checkStatus($result);
			return $status;
				
		} // End process_subscription_payment
			
			
	    /**
		 * Payment form on checkout page
		 */
	    function payment_fields() {
	    	global $woocommerce;
	    	
	    	if ($this->description) echo wpautop(wptexturize($this->description));
	    	
	    }
	    
	    
	    
	    /**
		 * receipt_page
		 **/
		function receipt_page( $order ) {
			
			echo '<p>'.__('Thank you for your order.', 'payson').'</p>';
			
		}
		
		
		
		
		/**
		 * scheduled_subscription_payment function.
		 * 
		 * @param $amount_to_charge float The amount to charge.
		 * @param $order WC_Order The WC_Order object of the order which the subscription was purchased in.
		 * @param $product_id int The ID of the subscription product for which this payment relates.
		 * @access public
		 * @return void
		 */
		function scheduled_subscription_payment( $amount_to_charge, $order, $product_id ) {
			
			$result = $this->process_subscription_payment( $order, $amount_to_charge );
			
			if (  $result == false ) {
			
				// Debug
				if ($this->debug=='yes') $this->log->add( 'payex', 'Scheduled subscription payment failed for order ' . $order->id );
				
				WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $order, $product_id );

			} else {
				
				// Debug
				if ($this->debug=='yes') $this->log->add( 'payex', 'Scheduled subscription payment succeeded for order ' . $order->id );
				
				WC_Subscriptions_Manager::process_subscription_payments_on_order( $order );

			}

		} // End function
		
		
		/**
		* process_subscription_payment function.
		*
  		* Since we use a Merchant handled subscription, we need to generate the 
  	 	* recurrence request.
  		*/
  	  		
  		function process_subscription_payment( $order = '', $amount = 0 ) {
			global $woocommerce;
			
			$agreementref = get_post_meta( $order->id, 'payex_agreement_ref', true);
			$currency = get_woocommerce_currency();

			$order_items = $order->get_items();
			$product = $order->get_product_from_item( array_shift( $order_items ) );
			
			if ( $product->get_sku() ) {
				$tmp_sku = $product->get_sku();
			} else {
				$tmp_sku = $product->id;
			}
			
			$params = array	(
				'accountNumber' => $this->account_no,
				'agreementRef' => $agreementref,
				'price' => number_format($amount, 2, '.', '')*100,
				'productNumber' => $tmp_sku,
				'description' => sprintf( __( 'Subscription for "%s"', 'payex' ), $product->get_title() ),
				//'orderId' => $order->get_order_number(),
				'orderId' => $order->id,
				'purchaseOperation' => $this->purchase_operation,
				'currency' => $currency
			);
			
			// Pass item info to PayEx
			$result = $this->AutoPay3($params);
			
			$status = $this->checkStatus($result);
			
			if($status['code'] == "OK" && $status['errorCode'] == "OK" && $status['description'] == "OK") {
				$order->add_order_note( sprintf(__('PayEx subscription payment completed. Transaction ref: %s. Transaction number: %s', 'payex'), $status['transactionRef'], $status['transactionNumber']) );
				return true;
			
			} else {
				$order->add_order_note( sprintf(__('PayEx subscription payment failed. Error code: %s.', 'payex'), $status['errorCode']) );
				return false;
				
			}
		
		} // End function


		/**
		 * Delete agreement
		*/
		function delete_agreement($order, $order_id) {
			
			$agreementref = get_post_meta( $order->id, 'payex_agreement_ref', true);
					
 				$params = array	(
					'accountNumber' => $this->account_no,
					'agreementRef' => $agreementref
				);
				
				$result = $this->DeleteAgreement($params);
				$status = $this->checkStatus($result);
			
			if($status['code'] == "OK" && $status['errorCode'] == "OK") {
					$order->add_order_note( sprintf(__('PayEx subscription canceled.', 'payex'), $status['errorCode']) );
				} else {
					$order->add_order_note( sprintf(__('PayEx subscription cancelation failed. Error code: %s.', 'payex'), $status['errorCode']) );
				}	
				
		} // End function
		
		
		/**
		 * createHash
		 **/
		function createHash($params) {
			$params = $params.$this->encrypted_key;
			return md5($params);
		}
		

		/**
		 * initialize8
		 **/
		function initialize8( $params ) {
			$PayEx = new SoapClient( $this->PxOrderWSDL, array( 'trace' => 1, 'exceptions' => 0 ) );
			// $function = new functions();

			// create the hash
			$hash = $this->createHash( trim( implode( '', $params ) ) );
			// append the hash to the parameters
			$params['hash'] = $hash;

			try{
				// defining which initialize version to run, this one is 8.
				$respons = $PayEx->Initialize8( $params );
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			} catch ( SoapFault $error ) {
				echo "Error: {$error->faultstring}";
			}
			return $respons->{'Initialize8Result'};
			// print_r($respons->{'Initialize8Result'}."\n");
		}

		
		/**
		 * AddSingleOrderLine2
		 **/
		function AddSingleOrderLine2($params) {
			$PayEx = new SoapClient($this->PxOrderWSDL,array("trace" => 1, "exceptions" => 0));
			//$function = new functions();
			
			//create the hash
			$hash = $this->createHash(trim(implode("", $params)));
			//append the hash to the parameters
			$params['hash'] = $hash;
			
			try{
				
				$respons = $PayEx->AddSingleOrderLine2($params);
				
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			}catch (SoapFault $error){
				echo "Error: {$error->faultstring}";
			}
			
			return $respons->{'AddSingleOrderLine2Result'};
			//print_r($respons->{'AddSingleOrderLine2Result'}."\n");
			
		}
		
		/**
		 * CreateAgreement3
		 **/
		function CreateAgreement3($params) {
			
			$PayEx = new SoapClient($this->PxAgreementWSDL,array("trace" => 1, "exceptions" => 0));
			//$function = new functions();
			
			//create the hash
			$hash = $this->createHash(trim(implode("", $params)));
			//append the hash to the parameters
			$params['hash'] = $hash;
			
			try{
				
				$respons = $PayEx->CreateAgreement3($params);
				
				
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			}catch (SoapFault $error){
				echo "Error: {$error->faultstring}";
			}
			
			return $respons->{'CreateAgreement3Result'};
			//print_r($respons->{'CreateAgreement3Result'}."\n");
			
			
		} // End function
		
		
		/**
		 * DeleteAgreement
		 **/
		function DeleteAgreement($params) {
			
			$PayEx = new SoapClient($this->PxAgreementWSDL,array("trace" => 1, "exceptions" => 0));
			//$function = new functions();
			
			//create the hash
			$hash = $this->createHash(trim(implode("", $params)));
			//append the hash to the parameters
			$params['hash'] = $hash;
			
			try{
				
				$respons = $PayEx->DeleteAgreement($params);
				
				
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			}catch (SoapFault $error){
				echo "Error: {$error->faultstring}";
			}
			
			return $respons->{'DeleteAgreementResult'};
			//print_r($respons->{'DeleteAgreementResult'}."\n");
			
			
		} // End function
		
		
		/**
		 * AutoPay3
		 **/
		function AutoPay3($params) {
			
			$PayEx = new SoapClient($this->PxAgreementWSDL,array("trace" => 1, "exceptions" => 0));
			//$function = new functions();
			
			//create the hash
			$hash = $this->createHash(trim(implode("", $params)));
			//append the hash to the parameters
			$params['hash'] = $hash;
			
			try{
				
				$respons = $PayEx->AutoPay3($params);
				
				
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			}catch (SoapFault $error){
				echo "Error: {$error->faultstring}";
			}
			
			return $respons->{'AutoPay3Result'};
			//print_r($respons->{'CreateAgreement3Result'}."\n");
			
			
		}

		
		
		/**
		 * Complete
		 **/
		 
		function Complete($params) {
			$PayEx = new SoapClient($this->PxOrderWSDL,array("trace" => 1, "exceptions" => 0));
			//$function = new functions();
			
			//create the hash
			$hash = $this->createHash(trim(implode("", $params)));
			//append the hash to the parameters
			$params['hash'] = $hash;
			
			try{
				//defining which complete
				$respons = $PayEx->Complete($params);
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			}catch (SoapFault $error){
				echo "Error: {$error->faultstring}";
			}
			return $respons->{'CompleteResult'};

		}
		
		
		/**
	 	* Check PayEx Response validity
	 	**/
		function check_payex_request_is_valid() {
			global $woocommerce;
		
			if ($this->debug=='yes') $this->log->add( 'payex', 'Checking PayEx response is valid...' );
			
			header('HTTP/1.1 200 OK', true, 200);
				
		}
		
		
		/**
		 * Check for PayEx Response
		 **/
		function check_payex_response( $posted ) {
			
			// Check for both IPN callback (payexListener) and buyer-return-to-shop callback (orderRef)
			// We do this because IPN sometimes fires after the buyer returns to the Thank you page.
			// This is not good if the transaction fails.
			//if ( ( isset($_GET['payexListener']) && $_GET['payexListener'] == '1' ) || isset($_GET['orderRef']) ):
			//if (isset($_GET['wc-api']) && $_GET['wc-api'] == 'WC_Gateway_Payex_PM' || isset($_GET['orderRef']) ):
			
				
	        	// Get the POST data
	        	//$postData = file_get_contents("php://input");
	        	//$_REQUEST = stripslashes_deep($_REQUEST);
	        	$orderRef = $posted['orderRef'];
	        	$params = array
	        	(
	        	'accountNumber' => $this->account_no,
	        	'orderRef' => $orderRef
	        	);
				
	        	$completeResponse = $this->Complete($params);		
	        	$result = $this->ckhCompleteResp($completeResponse);
	        	// For debug purposes
	        	if ($this->debug=='yes') {
		        	$tmp_log ='';
		        	foreach ( $result as $key => $value ) {
						$tmp_log .= $key . '=' . $value . "\r\n";
					}
					$this->log->add( 'payex', ' PayEx IPN Request Response: ' . $tmp_log );
					//$this->log->add( 'payex', print_r($result) );
				}
				
				//if ($this->check_payex_request_is_valid()) :
				if ($result['errorCode'] == 'OK') {
					
					header('HTTP/1.1 200 OK');
					
					if ($this->debug=='yes')
						$this->log->add( 'payex', 'PayEx IPN Request is valid!' );
						
					do_action("valid-payex-request", $result);
				
				} else {
					if ($this->debug=='yes') {
        				$this->log->add( 'payex', 'PayEx IPN Request Not Valid! ' );
        				wp_die("payex IPN Request Failure");
        			}
        			
        			header('HTTP/1.1 200 FAILURE');
        			
				}
				
	       			
	       			
	       	//endif; // End if payexListener
				
		} // End function check_payex_response()
		
		
		/**
		 * Successful Payment!
		 **/
		function successful_request( $posted ) {
			 global $woocommerce;

			 // 
			 if ( !empty($posted['errorCode']) && !empty($posted['orderId']) ) {
			 	
			 	$order_id = $posted['orderId'];
			 	
			 	$order = new WC_Order( $order_id );
			 	
			 	// We are here so lets check status and do actions
			 	// 0=Sale, 1=Initialize, 2=Credit, 3=Authorize, 4=Cancel, 5=Failure, 6=Capture
			 	
			 	switch ($posted['transactionStatus']) :
			 		case '0' :
			 		case '3' :
			 		case '6' :
				 		if ($order->status !== 'completed') {
					 	
						 	// Payment valid
						 	$order->add_order_note(sprintf(__('PayEx payment completed. PayEx Transaction Number: %s', 'payex'), $posted['transactionNumber'] ) );
						 	$order->payment_complete();
			 	
						 }
					break;
					
					case '5' :
						// Order failed
						$order->update_status('failed', sprintf(__('PayEx Payment unsuccessful. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
					break;
					
					case '4' :
						// Order cancelled (Only cancelled by merchant in PayEx Account)
						$order->update_status('cancelled', sprintf(__('PayEx Payment cancelled. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
					break;
					
					case '2' :
						// Order Credited/refunded (Only refunded by merchant in PayEx Account. Only supports full refound/credit at the moment)
						$order->update_status('refunded', sprintf(__('PayEx Payment refunded. PayEx Transaction Number: %s.', 'payex'), $posted['transactionNumber'] ) );
					break;
					
					default:
	            		// No action
	            		// On-hold until we sort this out with PayEx
	            		$order->update_status('on-hold', sprintf(__('PayEx Payment On Hold. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
	            	break;
	            
	            endswitch;
	            
	            // Prepare redirect url
				if( WC_Payex_Compatibility::is_wc_version_gte_2_1() ) {
	    			$redirect_url = WC_Payex_Compatibility::get_checkout_order_received_url($order);
				} else {
	    			$redirect_url = add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(get_option('woocommerce_thanks_page_id'))));
				}
				
	            // Return to Thank you page if this is a buyer-return-to-shop callback
	            wp_redirect( $redirect_url ); 
	            exit;
			 
			 }
			 
		} // End successful_request()

		
		
		/* Checking for OK statements in return xml. */
		public function checkStatus($xml) {
			$returnXml = new SimpleXMLElement($xml);
			$code = strtoupper($returnXml->status->code);
			$errorCode = strtoupper($returnXml->status->errorCode);
			$description = strtoupper($returnXml->status->description);
			$orderRef = strtoupper($returnXml->orderRef);
			$authenticationRequired = strtoupper($returnXml->authenticationRequired);
			$agreementRef = strtoupper($returnXml->agreementRef);
			$transactionRef = strtoupper($returnXml->transactionRef);
			$transactionNumber = strtoupper($returnXml->transactionNumber);
			$paymentMethod = strtoupper($returnXml->paymentMethod);

			return $status = array(
				'code'=>$code,
				'errorCode'=>$errorCode,
				'description'=>$description,
				'redirectUrl'=>$returnXml->redirectUrl,
				'orderRef'=>$orderRef,
				'authenticationRequired'=>$authenticationRequired,
				'agreementRef'=>$agreementRef,
				'transactionRef'=>$transactionRef,
				'transactionNumber'=>$transactionNumber,
				'paymentMethod'=>$paymentMethod
			);
 	
		}

		
		 /**
		  * Parse the xml response on payex complete transaction
		  * @param $params : xml data
		  * @return array of response data
		*/
		 
		function ckhCompleteResp($params) {
        	$returnXml = new SimpleXMLElement($params);
        	$code = strtoupper($returnXml->status->code);
        	$errorCode = strtoupper($returnXml->status->errorCode);
        	$description = strtoupper($returnXml->status->description);
        	$transactionStatus = strtoupper($returnXml->transactionStatus);
        	$transactionRef = strtoupper($returnXml->transactionRef);
        	$transactionNumber = strtoupper($returnXml->transactionNumber);
        	$orderId = strtoupper($returnXml->orderId);
        	return $status = array(
        	        'code'=>$code,
        	        'errorCode'=>$errorCode,
        	        'description'=>$description,
        	        'transactionStatus'=>$transactionStatus,
        	        'transactionRef'=>$transactionRef,
        	        'transactionNumber'=>$transactionNumber,
        	        'orderId'=>$orderId);
        }
		
	    
} // End class WC_Gateway_Payex_PM


/**
 *  Class for PayEx callback, this because IPN sometimes fires after the buyer returns to the Thank you page. 
 *  This is not good if the transaction fails.
 *  @class 		WC_Gateway_Payex_PM_Extra
 *  @since		1.0
 *
 **/

class WC_Gateway_Payex_PM_Extra {
	
	public function __construct() {
		global $woocommerce;
		$this->log 			= WC_Payex_Compatibility::new_wc_logger();
		
		// Actions
		add_action('init', array(&$this, 'check_callback'));
		
	}
	
	/**
	* Check for PayEx Response
	**/
	function check_callback() {
		// Check for both IPN callback (wc-api) and buyer-return-to-shop callback (orderRef)
		if ( (isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'WC_Gateway_Payex_PM') || isset($_GET['orderRef']) ) {
			$this->log->add( 'payex', ' Callback from PayEx...' );
			$callback = new WC_Gateway_Payex_PM;
			$callback->check_payex_response(stripslashes_deep($_REQUEST));
			
		} // End if
	} // End function check_callback()

} // End class WC_Gateway_Payex_PM_Extra

$wc_gateway_payex_pm_extra = new WC_Gateway_Payex_PM_Extra;
?>