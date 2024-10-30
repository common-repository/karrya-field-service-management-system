<?php
/*
Plugin Name: Karrya Field service management system
Plugin URI: 
Description: Karrya is a simple Field service management system.
Version: 1.4.3
Author: WAP Nishantha <wapnishantha@gmail.com>
Author URI: https://karryawp.enuyanu.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Repo: https://bitbucket.org/wapnishantha/karrya/src/master/
*/

define( 'FSMS_PATH', dirname( __FILE__ ) );
define( 'FSMS_URL_PATH', plugin_dir_url( __FILE__ ) );

require( FSMS_PATH . '/activate.php' );
require( FSMS_PATH . '/deactivate.php' );

// require controllers and models
require( FSMS_PATH . "/models/dashboard.php" );
require( FSMS_PATH . "/models/department.php" );
require( FSMS_PATH . "/controllers/departments.php" );
require( FSMS_PATH . "/models/subDepartment.php" );

require( FSMS_PATH . "/models/charge_type.php" );
require( FSMS_PATH . "/controllers/charge_types.php" );

require( FSMS_PATH . "/models/lead.php" );
require( FSMS_PATH . "/models/charge.php" );
require( FSMS_PATH . "/models/cost.php" );
require( FSMS_PATH . "/models/payment.php" );
require( FSMS_PATH . "/models/email.php" );
require( FSMS_PATH . "/controllers/leads.php" );

require( FSMS_PATH . "/models/subscriber.php" );
require( FSMS_PATH . "/controllers/subscribers.php" );

require( FSMS_PATH . "/models/stock.php" );
require( FSMS_PATH . "/controllers/stocks.php" );


require( FSMS_PATH . "/models/invoice.php" );
require( FSMS_PATH . "/models/quote.php" );
require( FSMS_PATH . "/models/stat.php" );
require( FSMS_PATH . "/models/message.php" );
require( FSMS_PATH . "/models/log.php" );


require( FSMS_PATH . "/controllers/shortcodes.php" );
require( FSMS_PATH . "/controllers/ajax.php" );
require( FSMS_PATH . "/controllers/users.php" );

require( FSMS_PATH . "/models/stage.php" );
require( FSMS_PATH . "/controllers/stages.php" );

require( FSMS_PATH . "/models/site.php" );
require( FSMS_PATH . "/controllers/sites.php" );

require( FSMS_PATH . "/models/owner.php" );
require( FSMS_PATH . "/controllers/owners.php" );

require( FSMS_PATH . "/models/supplier.php" );
require( FSMS_PATH . "/controllers/suppliers.php" );

require( FSMS_PATH . "/models/worker.php" );
require( FSMS_PATH . "/controllers/workers.php" );

require( FSMS_PATH . "/models/customer.php" );
require( FSMS_PATH . "/controllers/customers.php" );

add_action( 'init', array( "FSMSDashboard", "init" ) );

add_action( 'admin_menu', array( "FSMSDashboard", "menu" ) );


/*Front end short code*/
add_shortcode( 'fsms-lead-dep-booking', array( "FSMSShortcodes", "departmentLeadBookingForm" ) );


// safe redirect
function fmsm_redirect( $url ) {
	echo "<meta http-equiv='refresh' content='0;url=$url' />";
	exit;
}


function change_product_price_display( $price ) {

	if ( is_float( $price ) && $price > 0 ) {
		$price = number_format( $price, 2, '.', '.' );
	}

	return esc_attr( get_option( 'fsms_currency_symbol' ) ) . $price;

}

add_filter( 'plugin_action_links', 'fsms_plugin_action_links', 10, 2 );

function fsms_plugin_action_links( $links, $file ) {
	$plugin_file = basename( __FILE__ );
	if ( basename( $file ) == $plugin_file ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=fsms_settings' ) . '">Settings</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

/**
 * Activation and Deactivation hooks
 */
register_activation_hook( __FILE__, 'fsms_activate' );
register_activation_hook( __FILE__, 'fsms_insert_custom_table_data' );
register_deactivation_hook( __FILE__, 'fsms_deactivate' );
 

