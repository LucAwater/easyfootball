<?php

class WC_Gateway_Payex_Invoice extends WC_Gateway_Payex {
	
	/**
     * Class for PayEx Invoice payment.
     *
     */
     
     public function __construct() {	
		parent::__construct();
		$this->id			= 'payex_invoice';
		$this->method_title = __('PayEx Invoice', 'payex');
		$this->icon 		= apply_filters( 'woocommerce_payex_invoice_icon', plugins_url(basename(dirname(__FILE__))."/images/payex.png") );
		
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
		$this->language				= $this->settings['language'];
		$this->testmode				= $this->settings['testmode'];
		$this->debug				= $this->settings['debug'];
		
		// Test or Live?
 		if($this->testmode == 'yes'){
	 		$this->PxOrderWSDL = "https://test-external.payex.com/pxorder/pxorder.asmx?wsdl";
	 		$this->PxConfinedWSDL = "https://test-confined.payex.com/PxConfined/pxorder.asmx?wsdl";
	 	} else {
		 	$this->PxOrderWSDL = "https://external.payex.com/pxorder/pxorder.asmx?wsdl";
	        $this->PxConfinedWSDL = "https://confined.payex.com/PxConfined/pxorder.asmx?wsdl";
	    }
				
		// Actions
		//add_action( 'woocommerce_api_wc_gateway_payex_pm', array(&$this, 'check_payex_response') );
		
		add_action( 'valid-payex-request', array(&$this, 'successful_request') );
		add_action( 'woocommerce_receipt_payex_invoice', array(&$this, 'receipt_page') );
		
		/* 1.6.6 */
		add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
 
		/* 2.0.0 */
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		
	}
	
		
		/**
		* Check if this gateway is enabled and available in the user's country
		*/
		
		function is_available() {
			global $woocommerce;
			
			if ($this->enabled=="yes") :
							
				// Currency check
				if (!in_array(get_option('woocommerce_currency'), array('EUR', 'SEK', 'DKK', 'NOK'))) return false;
				
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
								'label' => __( 'Enable PayEx Invoice', 'payex' ), 
								'default' => 'no'
							), 
				'title' => array(
								'title' => __( 'Title', 'payex' ), 
								'type' => 'text', 
								'description' => __( 'This controls the title which the user sees during checkout.', 'payex' ), 
								'default' => __( 'PayEx Invoice', 'payex' )
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
				'language' => array(
								'title' => __( 'Language', 'payex' ), 
								'type' => 'select',
								'options' => array('SV'=>'Swedish', 'EN'=>'English', 'FI'=>'Finnish'),
								'description' => __( 'Locale of pages displayed by payex during payment.', 'payex' ), 
								'default' => 'SV'
							),
				'testmode' => array(
								'title' => __( 'Test Mode', 'payex' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable PayEx Invoice Test Mode.', 'payex' ), 
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
			
			// Include PayEx API library
			//require_once(payex_LIB . 'confined.php');
			//require_once(payex_LIB . 'pxorder.php');
			
			
			/*
 			 * Step 1: Set up details
 			 */
   
	        //$_server won't work if run from console.
			$this->clientIPAddress = $_SERVER['REMOTE_ADDR'];		
			$this->clientIdentifier = "USERAGENT=".$_SERVER['HTTP_USER_AGENT'];
 			
 			// We manually calculate the tax percentage here
 			$order_total_ex_tax = $order->order_total - $order->order_tax;
 			$this->tax_percentage = number_format( (($order->order_total/$order_total_ex_tax)-1)*10000, 0, '', '');
 			
 			$params = array	(
				'accountNumber' => $this->account_no,
				'purchaseOperation' => $this->purchase_operation, // AUTHORIZATION or SALE
				'price' => (int)number_format($order->order_total, 2, '', ''),
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
				'view' => 'Invoice', // Payment method PayEx
				'agreementRef' => '',
				'cancelUrl' => $order->get_cancel_order_url(),
				'clientLanguage' => 'sv-SE'
			);
				
			
 			/*
			 * Step 2 initiate payment
			 */ 
			
			// $pxorder = new pxorder();
			// $functions = new functions();
			
			
			$result = $this->initialize8( $params );
			// echo $result;
			
			
			$status = $this->checkStatus($result);
			
			/*
 			* Step 3: verify that it suceeded
 			*/
 			
			// if code & description & errorCode is OK, redirect the user
			if($status['code'] == "OK" && $status['errorCode'] == "OK" && $status['description'] == "OK") {
				
				if ($this->debug=='yes') $this->log->add( 'payex', 'Sending order details to PayEx...' );
				
				// var_dump($status);
				// die();
				// Test med orderrader
				
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
		 * createHash
		 **/
		function createHash($params) {
			$params = $params.$this->encrypted_key;
			return md5($params);
		}
		


		/**
		 * initialize8
		 **/
		function initialize8($params) {
			$PayEx = new SoapClient($this->PxOrderWSDL,array("trace" => 1, "exceptions" => 0));
			// $function = new functions();

			// create the hash
			$hash = $this->createHash( trim( implode( '', $params ) ) );
			// append the hash to the parameters
			$params['hash'] = $hash;

			try{
				// defining which initialize version to run, this one is 8.
				$respons = $PayEx->Initialize8( $params );
				/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
			} catch ( SoapFault $error ){
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
						 	$order->add_order_note(sprintf(__('PayEx Invoice payment completed. PayEx Transaction Number: %s', 'payex'), $posted['transactionNumber'] ) );
						 	$order->payment_complete();
			 	
						 }
					break;
					
					case '5' :
						// Order failed
						$order->update_status('failed', sprintf(__('PayEx Invoice Payment unsuccessful. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
					break;
					
					case '4' :
						// Order cancelled (Only cancelled by merchant in PayEx Account)
						$order->update_status('cancelled', sprintf(__('PayEx Invoice Payment cancelled. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
					break;
					
					case '2' :
						// Order Credited/refunded (Only refunded by merchant in PayEx Account. Only supports full refound/credit at the moment)
						$order->update_status('refunded', sprintf(__('PayEx Invoice Payment refunded. PayEx Transaction Number: %s.', 'payex'), $posted['transactionNumber'] ) );
					break;
					
					default:
	            		// No action
	            		// On-hold until we sort this out with PayEx
	            		$order->update_status('on-hold', sprintf(__('PayEx Invoice Payment On Hold. PayEx Transaction Number: %s PayEx Transaction Status: %s.', 'payex'), $posted['transactionNumber'], $posted['transactionStatus'] ) );
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

			return $status = array(
				'code'=>$code,
				'errorCode'=>$errorCode,
				'description'=>$description,
				'redirectUrl'=>$returnXml->redirectUrl,
				'orderRef'=>$orderRef,
				'authenticationRequired'=>$authenticationRequired
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
        	$transactionNumber = strtoupper($returnXml->transactionNumber);
        	$orderId = strtoupper($returnXml->orderId);
        	return $status = array(
        	        'code'=>$code,
        	        'errorCode'=>$errorCode,
        	        'description'=>$description,
        	        'transactionStatus'=>$transactionStatus,
        	        'transactionNumber'=>$transactionNumber,
        	        'orderId'=>$orderId);
        }
		
	    
} // End class WC_Gateway_Payex_Invoice