<?php
/**
 * WooCommerce PayEx Gateway
 * By Niklas Högefjord (niklas@krokedil.se)
 * 
 * Uninstall - removes all PayEx options from DB when user deletes the plugin via WordPress backend.
 * @since 1.0
 **/
 
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
	delete_option( 'woocommerce_payex_pm_settings' );
		
?>