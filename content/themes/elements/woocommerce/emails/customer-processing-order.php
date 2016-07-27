<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php _e( "Din beställning är betald och klar men för att vi ska kunna leverera dina biljetter är det viktigt att du läser informationen nedan om hur just dina biljetter levereras. Återkom med informationen så snart som möjligt.", 'woocommerce' ); ?></p>

<p><?php _e( "Biljetter till matcher utanför Sverige levereras i regel på plats i den stad som matchen spelas. Nedan följer information om hur biljetterna levereas i det land som du ska till." ); ?></p>

<p><strong><?php _e( "England" ); ?></strong><br><?php _e( "Biljetter till matcher i England levereras i regel till receptionen i det hotell som du bor på i England. Det är därför viktigt att du meddelar oss så fort du har bokat ditt boende:" ); ?></p>
<ul>
  <li>Namn på hotellet</li>
  <li>Adress till hotellet</li>
  <li>Namnet på den person hotellbokningen står på</li>
  <li>Mobilnummer till dig</li>
</ul>

<p>
  <strong><?php _e( "Spanien" ); ?></strong><br>
  <?php _e( "Biljetter till FC Barcelona eller Espanyol hämtas ut på följande adress i Barcelona:" ); ?>
</p>
<p>
  <?php _e( "La Rambla 54" ); ?><br>
  <?php _e( "08002 Barcelona" ); ?><br>
</p>
<p><?php _e( "Väl på plats visar du som kund din legitimation eller bokningsnummer och får biljetterna." ); ?></p>

<p><?php _e( "Biljetter till övriga matcher i Spanien levereras till ditt boende vi behöver därför:" ); ?></p>
<ul>
  <li>Namn på hotellet</li>
  <li>Adress till hotellet</li>
  <li>Namnet på den person hotellbokningen står på</li>
  <li>Mobilnummer till dig</li>
</ul>

<p><strong><?php _e( "Italien" ); ?></strong><br><?php _e( "Biljetter till matcher i Italien levereras till receptionen i det hotell som du bor på i Italien. Det är därför viktigt att du meddelar oss så fort du har bokat ditt boende:" ); ?></p>
<ul>
  <li>Namn på hotellet</li>
  <li>Adress till hotellet</li>
  <li>Namnet på den person hotellbokningen står på</li>
  <li>Mobilnummer till dig</li>
  <li>pasnummer</li>
</ul>

<?php
/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
