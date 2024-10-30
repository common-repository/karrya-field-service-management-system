<?php

class FSMSDashboard {
	// initialization
	static function init() {
		global $wpdb;
		register_setting( 'fsms-settings', 'fsms_vat' );
		register_setting( 'fsms-settings', 'fsms_currency_symbol' );
		register_setting( 'fsms-settings', 'fsms_company_address' );
		register_setting( 'fsms-settings', 'fsms_no_of_rows' );
		register_setting( 'fsms-settings', 'thanks_email' );
		register_setting( 'fsms-settings', 'from_email_address' );
		register_setting( 'fsms-settings', 'thanks_email_subject' );
		register_setting( 'fsms-settings', 'email_footer' );
		register_setting( 'fsms-settings', 'email_header' );


		register_setting( 'fsms-settings', 'invoice_top_setting' );
		register_setting( 'fsms-settings', 'invoice_bottom_setting' );
		register_setting( 'fsms-settings', 'quote_top_setting' );
		register_setting( 'fsms-settings', 'quote_bottom_setting' );


	}

	// manage general options
	static function dashboardViewOwner() {
		include( FSMS_PATH . "/views/dashboard-view-owner.php" );
	}

	static function dashboardViewWorker() {
		include( FSMS_PATH . "/views/dashboard-view-worker.php" );
	}

	static function dashboardViewSubscriber() {
		include( FSMS_PATH . "/views/subscriber/dashboard-view-subscriber.php" );
	}

	static function dashboardViewAdmin() {
		$_stat = new FSMSStat();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );


		switch ( $action ) {
			case 'search':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_stat_search' ) ) {

					$leadCount      = $_stat->getStatLeads();
					$costAndPayment = $_stat->getStatCostAndPayment();

					$fromDate             = sanitize_text_field( $_POST['fromDate'] );
					$toDate               = sanitize_text_field( $_POST['toDate'] );
					$costAndPaymentSearch = $_stat->getStatCostAndPaymentSearch( $fromDate, $toDate );

					$leadCountSearch = $_stat->getSearchStatLeads( $fromDate, $toDate );
					include( FSMS_PATH . "/views/dashboard-view-admin.php" );
					include( FSMS_PATH . "/views/dashboard-view-admin-search.php" );
				}
				break;
			case 'list':
			default:

				$leadCount      = $_stat->getStatLeads();
				$costAndPayment = $_stat->getStatCostAndPayment();
				include( FSMS_PATH . "/views/dashboard-view-admin.php" );
				break;
		}


	}

	// manage general options
	static function options() {
		include( FSMS_PATH . "/views/setting.php" );
	}

	// main menu
	static function menu() {

		$fsms_caps       = current_user_can( 'admin_lead_setting' ) ? 'admin_lead_setting' : 'admin_lead_setting';
		$fsms_caps_owner = current_user_can( 'lead_summary_owner' ) ? 'lead_summary_owner' : 'lead_summary_owner';

		$fsms_caps_worker = current_user_can( 'lead_summary_worker' ) ? 'lead_summary_worker' : 'lead_summary_worker';

		$fsms_caps_subscriber = current_user_can( 'lead_summary_subscriber' ) ? 'lead_summary_subscriber' : 'lead_summary_subscriber';

		$fsms_caps_supplier = current_user_can( 'lead_summary_supplier' ) ? 'lead_summary_supplier' : 'lead_summary_supplier';

		if ( current_user_can( 'lead_summary_owner' ) ) :
			add_menu_page( 'FSMS', 'Fsms', $fsms_caps_owner, "fsms_options",
				array( 'FSMSLeads', "manage" ),FSMS_URL_PATH. 'assets/icon.png' );

			add_submenu_page( 'fsms_options', 'Leads', 'Leads', $fsms_caps_owner, 'fsms_leads', array(
				'FSMSLeads',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage customers', 'Manage customers', $fsms_caps_owner, 'fsms_customers', array(
				'FSMSCustomers',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage sites', 'Manage sites', $fsms_caps_owner, 'fsms_sites', array(
				'FSMSSites',
				"manage"
			) );
		endif;
		if ( current_user_can( 'lead_summary_worker' ) ) :
			add_menu_page( 'FSMS', 'Fsms', $fsms_caps_worker, "fsms_options",
				array( 'FSMSWorkers', "manage" ),FSMS_URL_PATH.'assets/icon.png' );

			add_submenu_page( 'fsms_options', 'Leads', 'Leads', $fsms_caps_worker, 'fsms_work_leads', array(
				'FSMSWorkers',
				"manage"
			) );
		endif;
		if ( current_user_can( 'lead_summary_subscriber' ) ) :
			add_menu_page( 'FSMS', 'Fsms Leads', $fsms_caps_subscriber, "fsms_work_subscriber",
				array( 'FSMSSubscribers', "manage" ),FSMS_URL_PATH. 'assets/icon.png' );

			//add_submenu_page('fsms_options','Leads', 'Leads', $fsms_caps_subscriber, 'fsms_work_subscriber', array('FSMSSubscribers', "manage"));
		endif;
		if ( current_user_can( 'lead_summary_supplier' ) ) :
			add_menu_page( 'FSMS', 'Fsms Leads', $fsms_caps_supplier, "fsms_work_supplier",
				array( 'FSMSSuppliers', "manage" ),FSMS_URL_PATH. 'assets/icon.png' );

			//add_submenu_page('fsms_options','Leads', 'Leads', $fsms_caps_subscriber, 'fsms_work_subscriber', array('FSMSSubscribers', "manage"));
		endif;
		if ( current_user_can( 'admin_lead_setting' ) ) :

			add_menu_page( 'FSMS', 'Fsms', $fsms_caps, "fsms_options",
				array( __CLASS__, "dashboardViewAdmin" ),FSMS_URL_PATH.'assets/icon.png' );

			add_submenu_page( 'fsms_options', 'Dashboard', 'Dashboard', $fsms_caps, "fsms_options",
				array( __CLASS__, "dashboardViewAdmin" ) );


			add_submenu_page( 'fsms_options', 'Setting', 'Setting', $fsms_caps, "fsms_settings",
				array( __CLASS__, "options" ) );


			add_submenu_page( 'fsms_options', 'Leads', 'Leads', $fsms_caps, 'fsms_leads', array(
				'FSMSLeads',
				"manage"
			) );

			add_submenu_page( 'fsms_options', 'Manage departments', 'Manage departments', $fsms_caps, 'fsms_departments', array(
				'FSMSDepartments',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage charge type', 'Manage charge type', $fsms_caps, 'fsms_charge_types', array(
				'FSMSChargeTypes',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage stock', 'Manage stock', $fsms_caps, 'fsms_stocks', array(
				'FSMSStocks',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage stages', 'Manage stages', $fsms_caps, 'fsms_stages', array(
				'FSMSStages',
				"manage"
			) );

			add_submenu_page( 'fsms_options', 'Manage customers', 'Manage customers', $fsms_caps, 'fsms_customers', array(
				'FSMSCustomers',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage sites', 'Manage sites', $fsms_caps, 'fsms_sites', array(
				'FSMSSites',
				"manage"
			) );

			add_submenu_page( 'fsms_options', 'Manage job owners', 'Manage job owners', $fsms_caps, 'fsms_owners', array(
				'FSMSOwners',
				"manage"
			) );
			add_submenu_page( 'fsms_options', 'Manage job worker', 'Manage job worker', $fsms_caps, 'fsms_workers', array(
				'FSMSWorkers',
				"manages"
			) );
			add_submenu_page( 'fsms_options', 'Manage supplier', 'Manage supplier', $fsms_caps, 'fsms_suppliers', array(
				'FSMSSuppliers',
				"manages"
			) );
		endif;
	}


}