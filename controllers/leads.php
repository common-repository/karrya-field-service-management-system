<?php

class FSMSLeads {
	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'fsms_lead_ajax_enqueuer' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fsms_lead_ajax_enqueuer' ) );

		add_action( 'wp_ajax_charge_insert', array( "FSMSCharge", 'charge_insert_to_tb' ) );

		add_action( 'wp_ajax_cost_insert', array( "FSMSCost", 'cost_insert_to_tb' ) );

		add_action( 'wp_ajax_payment_insert', array( "FSMSPayment", 'payment_insert_to_tb' ) );

		add_action( 'wp_ajax_payment_delete', array( "FSMSPayment", 'payment_delete_from_tb' ) );

		add_action( 'wp_ajax_view_charge_block', array( "FSMSCharge", 'view_charge_block' ) );
		add_action( 'wp_ajax_view_cost_block', array( "FSMSCost", 'view_cost_block' ) );
		add_action( 'wp_ajax_view_payment_block', array( "FSMSPayment", 'view_payment_block' ) );

		add_action( 'wp_ajax_list_subdepartment', array( "FSMSDepartment", 'list_subdepartment' ) );


		add_action( 'wp_ajax_send_invoice_to_customer', array( "FSMSInvoice", 'send_invoice_to_customer_email' ) );

		add_action( 'wp_ajax_send_quote_to_customer', array( "FSMSQuote", 'send_quote_to_customer_email' ) );

		add_action( 'wp_ajax_search_sku', array( "FSMSStock", 'search_sku' ) );
		add_action( 'wp_ajax_search_description', array( "FSMSStock", 'search_description' ) );

		add_action( 'wp_ajax_get_sku_details', array( "FSMSStock", 'get_sku_details' ) );


	}

	public function fsms_lead_ajax_enqueuer() {
		wp_register_style( 'fsms-css', FSMS_URL_PATH . 'assets/css/style.css?v=1' );
		wp_enqueue_style( 'fsms-css' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui' );


		// Namaste's own Javascript
		wp_register_script(
			'lead-common',
			FSMS_URL_PATH . 'assets/js/common.js',
			false,
			'0.1.0',
			false
		);
		wp_enqueue_script( "lead-common" );
		$translation_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'lead-common', 'fsms_i18n', $translation_array );

		wp_register_script(
			'fsms_lead_common',
			FSMS_URL_PATH . '/assets/js/ajax.js',
			false,
			'0.1.0',
			false
		);

		//Here we create a javascript object variable called "youruniquejs_vars". We can access any variable in the array using youruniquejs_vars.name_of_sub_variable		
		 
		$id     = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );
		wp_localize_script( 'fsms_lead_common', 'fsms_js_vars',
			array(
				//To use this variable in javascript use "youruniquejs_vars.ajaxurl"
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				//To use this variable in javascript use "youruniquejs_vars.the_issue_key"
				'id'      => intval( $id ),
				'nonceVal' => wp_create_nonce('ajax-nonce')

			)
		);


		wp_enqueue_script( "fsms_lead_common" );

	}


	public static function manage() {
		$_lead = new FSMSLead();
		$user  = wp_get_current_user();

		if ( in_array( 'lead_owner', (array) $user->roles ) ) {
			@$id = intval( $_GET['id'] );
			if ( $id > 0 ) {
				$ownerShip = $_lead->checkOwnerShip( $id, 2 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}
			}

		}


		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_add_edit' ) ) {
					$_customer  = new FSMSCustomer();
					$customerId = $_customer->addFromLead( $_POST );
					$_site      = new FSMSSite();
					$siteId     = $_site->addFromLead( $_POST, $customerId );

					$otherDetails = array( "site_id" => intval( $siteId ), "cust_id" => intval( $customerId ) );


					$id      = $_lead->add( $_POST, $otherDetails );
					$success = "added";
					//fmsm_redirect( "admin.php?page=fsms_leads&action=lead_view&id=" . esc_attr($id) );
				}
				$_department  = new FSMSDepartment();
				$departments  = $_department->find();
				$_stage       = new FSMSStage();
				$stages       = $_stage->stageList();
				$args         = array(
					'role'    => 'lead_worker',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_workers = get_users( $args );
				$args         = array(
					'role'    => 'lead_owner',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_owners  = get_users( $args );

				include( FSMS_PATH . "/views/addEditLead.php" );

				break;
			case 'edit':
				$id = intval( $_GET['id'] );
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_add_edit' ) ) {
					$_lead->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_leads&action=lead_view&id=" . esc_attr( $id ) );
				}
				$lead = $_lead->get( $id );
				echo "<script>
				 		departmentsList(" . esc_attr( $lead->lead_dep_id ) . "," . esc_attr( $lead->lead_sub_dep_id ) . ")
					  </script>";
				$_department  = new FSMSDepartment();
				$departments  = $_department->find();
				$_stage       = new FSMSStage();
				$stages       = $_stage->stageList();
				$args         = array(
					'role'    => 'lead_worker',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_workers = get_users( $args );
				$args         = array(
					'role'    => 'lead_owner',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_owners  = get_users( $args );

				include( FSMS_PATH . "/views/addEditLead.php" );
				break;
			case 'charge_edit':
				$id      = intval( $_GET['id'] );
				$lead_id = intval( $_GET['lead_id'] );
				/*$ownerShip = $_lead->checkOwnerShip( $lead_id, 2 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}*/
				$_charge   = new FSMSCharge();
				$chargeArr = $_charge->getChargesById( $id );
				$charge    = $chargeArr[0];
				/*echo "<pre>";
				print_r($charge);
				echo "</pre>";*/
				$_chargeTypes = new FSMSChargeType();
				$chargeTypes  = $_chargeTypes->findActive();

				$args         = array(
					'role'    => 'lead_supplier',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_suppliers  = get_users( $args );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_charge_payment_add_edit' ) ) {
					$_charge->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_leads&action=lead_view&id=" . esc_attr( $lead_id ) );
				}
				include( FSMS_PATH . "/views/addEditCharge.php" );
				break;
			case 'send_invoice_view':
				$id = intval( $_GET['id'] );
				include( FSMS_PATH . "/views/invoiceHeading.php" );
				include( wp_upload_dir()['basedir'] . "/fsms_invoices/viewInvoice_" . esc_attr( $id ) . ".html" );
				break;
			case 'add_message':
				$_message = new FSMSMessage();
				$id       = intval( $_GET['id'] );


				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_message_add' ) ) {
					$_POST['message_added_by'] = get_current_user_id();
					$_POST['message_lead_id']  = esc_attr( $id );
					$_POST['message_for_id']   = esc_attr( $_POST['message_for_id'] );
					if ( $_POST['user_type'] == "2" ) {
						$_POST['message'] = wp_kses_post( wpautop( $_POST['message_owner'] ) );
					} else {
						$_POST['message'] = wp_kses_post( wpautop( $_POST['message_worker'] ) );
					}


					$_message->add( $_POST );
					fmsm_redirect( "admin.php?page=fsms_leads&action=lead_view&id=" . esc_attr( $id ) );
				}
				break;
			case 'send_quote_view':
				$id = intval( $_GET['id'] );
				include( wp_upload_dir()['basedir'] . "/fsms_quotes/viewQuote_" . esc_attr( $id ) . ".html" );
				break;
			case 'is_approve_view':
				$_log              = new FSMSLog();
				$id                = intval( $_GET['id'] );
				$quoteOrInvoice    = intval( $_GET['quoteOrInvoice'] );
				$quoteOrInvoiceTxt = "Invoice";
				if ( $quoteOrInvoice == 1 ) {
					$_invoice   = new FSMSInvoice();
					$invoiceArr = $_invoice->findByid( $id );
					$invoice    = $invoiceArr[0];

				}
				if ( $quoteOrInvoice == 2 ) {
					$_invoice          = new FSMSQuote();
					$invoiceArr        = $_invoice->findByid( $id );
					$invoice           = $invoiceArr[0];
					$quoteOrInvoiceTxt = "Quote";
				}

				$lead_id = intval( $_GET['lead_id'] );
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_invoices_status' ) ) {
					$_lead->approve( $_POST, $id );
					$logvar['lead_id'] = $lead_id;
					$appOrReject       = "Reject";
					if ( $_POST['is_approve'] == 1 ) {
						$appOrReject = "Approved";
					}
					$logvar['action_text'] = esc_attr( $quoteOrInvoiceTxt . "#" . $id . " " . $appOrReject );
					$logvar['action_done'] = get_current_user_id();
					$logvar['action_type'] = esc_attr( "Invoice / Quote approve" );
					$_log->add( $logvar );
					fmsm_redirect( "admin.php?page=fsms_leads&action=lead_view&id=" . esc_attr( $lead_id ) . "#quote_block" );

				}
				include( FSMS_PATH . "/views/addEditInvoiceStatus.php" );
				break;
			case 'lead_view':
				echo "<script>
				loadChargeBlock();
				loadCostBlock()
				loadPaymentBlock()
				</script>";
				$id          = intval( $_GET['id'] );
				$leadViewArr = $_lead->view( $id );
				$_invoice    = new FSMSInvoice();
				$invoices    = $_invoice->find( $id );
				$_quote      = new FSMSQuote();
				$quotes      = $_quote->find( $id );

				$leadView = $leadViewArr[0];
				$_site    = new FSMSSite();
				$sites    = $_site->findLeadSite( $leadView->cust_id );

				$otherLeads              = $_lead->findOtherLeads( $leadView->cust_id );
				$_message                = new FSMSMessage();
				$allmessagesParentOwner  = $_message->getAllMessages( 2, $id, 0 );
				$allmessagesParentWorker = $_message->getAllMessages( 3, $id, 0 );

				$_log = new FSMSLog();
				$logs = $_log->find( $id );
				/*echo "<pre>";
				print_r($allmessagesParent);
				echo "</pre>";	*/
				include( FSMS_PATH . "/views/leadHeading.php" );
				include( FSMS_PATH . "/views/lead-view-owner-worker-message.php" );
				include( FSMS_PATH . "/views/lead-view-summary.php" );
				include( FSMS_PATH . "/views/lead-view.php" );

				break;
			case 'send_invoice':
				$id = intval( $_GET['id'] );

				$leadViewArr           = $_lead->view( $id );
				$leadView              = $leadViewArr[0];
				$leadCharges           = FSMSCharge::getCharges( $id );
				$leadChargesSummaryArr = FSMSCharge::getChargesSummary( $id );
				$leadChargesSummary    = $leadChargesSummaryArr[0];

				$placeholderArr       = FSMSEmail::getPlaceholderValues( $id );
				$leadObjArr           = $placeholderArr['lead_veiw'];
				$placeholder          = $placeholderArr['placeholder'];
				$invoice_top_blcok    = FSMSEmail::getEmailPlaceholder( $placeholder, $leadView->invoice_top_blcok );
				$invoice_bottom_blcok = FSMSEmail::getEmailPlaceholder( $placeholder, $leadView->invoice_bottom_blcok );
				$email_footer         = wp_kses_post( get_option( 'email_footer' ) );
				$email_header         = wp_kses_post( get_option( 'email_header' ) );
				$email_footer         = FSMSEmail::getEmailPlaceholder( $placeholder, $email_footer );

				$email_header = FSMSEmail::getEmailPlaceholder( $placeholder, $email_header );

				include( FSMS_PATH . "/views/create-invoice-view.php" );
				break;
			case 'send_quote':
				$id = intval( $_GET['id'] );

				$leadViewArr           = $_lead->view( $id );
				$leadView              = $leadViewArr[0];
				$leadCharges           = FSMSCharge::getCharges( $id );
				$leadChargesSummaryArr = FSMSCharge::getChargesSummary( $id );
				$leadChargesSummary    = $leadChargesSummaryArr[0];

				$placeholderArr       = FSMSEmail::getPlaceholderValues( $id );
				$leadObjArr           = $placeholderArr['lead_veiw'];
				$placeholder          = $placeholderArr['placeholder'];
				$invoice_top_blcok    = FSMSEmail::getEmailPlaceholder( $placeholder, $leadView->quote_top_blcok );
				$invoice_bottom_blcok = FSMSEmail::getEmailPlaceholder( $placeholder, $leadView->quote_bottom_blcok );
				$email_footer         = wp_kses_post( get_option( 'email_footer' ) );
				$email_header         = wp_kses_post( get_option( 'email_header' ) );
				$email_footer         = FSMSEmail::getEmailPlaceholder( $placeholder, $email_footer );

				$email_header = FSMSEmail::getEmailPlaceholder( $placeholder, $email_header );


				include( FSMS_PATH . "/views/create-quote-view.php" );
				break;
			case 'list':
			default:
				$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
				$pageCount = 5;
				if ( esc_attr( get_option( 'fsms_no_of_rows' ) ) > 0 ) {

					$pageCount = esc_attr( get_option( 'fsms_no_of_rows' ) );
				} else {
					$pageCount = 5;
				}
				if ( $pagei == "" || $pagei == 0 ) {
					$P          = 1;
					$offSetPage = 0;
				} else {
					$P          = $pagei;
					$offSetPage = $pagei - 1;
				}
				$rstart = $offSetPage * $pageCount;
				$rend   = $pageCount;

				$dir = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
				if ( $dir != 'ASC' and $dir != 'DESC' ) {
					$dir = 'ASC';
				}
				$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
				$ob    = "id";
				$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
				if ( ! empty( $obGet ) ) {
					if ( $obGet == "name" ) {
						$ob = "lead_cus_fname";
					}
					if ( $obGet == "id" ) {
						$ob = "id";
					}
					if ( $obGet == "dep" ) {
						$ob = "lead_dep_id";
					}
					if ( $obGet == "subDep" ) {
						$ob = "lead_sub_dep_id";
					}
					if ( $obGet == "stage" ) {
						$ob = "lead_sub_dep_id";
					}
					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}

				$filters         = array(
					"rstart" => $rstart,
					"rend"   => $rend,
					"ob"     => $ob,
					"dir"    => $dir,
					"getOb"  => $obGet
				);
				$leads           = $_lead->find( $filters );
				$totalLeadsCount = $_lead->getTotalLeadsCount();

				include( FSMS_PATH . "/views/leads.php" );
				break;
		}
	}

	// do the booking
	static function book() {
		global $wpdb;

		$_lead = new FSMSLead();
		$_lead->add( $_POST );
	}


}

new FSMSLeads();