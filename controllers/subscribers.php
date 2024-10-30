<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSubscribers {

	public static function manage() {
		$_subscriber = new FSMSSubscriber();
		$action      = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {


			default:
			case 'list':
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
				$leads           = $_subscriber->findLeads( $filters );
				$totalLeadsCount = $_subscriber->getTotalLeadsCount();
				include( FSMS_PATH . "/views/subscriber/subscriber.php" );
				break;
			case 'lead_view':
				$_lead     = new FSMSLead();
				$id        = intval( $_GET['id'] );
				$ownerShip = $_lead->checkOwnerShip( $id, 4 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}
				$_invoice = new FSMSInvoice();
				$invoices = $_invoice->find( $id );
				$_quote   = new FSMSQuote();
				$quotes   = $_quote->find( $id );

				$leadViewArr = $_lead->view( $id );
				$leadView    = $leadViewArr[0];


				include( FSMS_PATH . "/views/subscriber/leadHeading.php" );

				include( FSMS_PATH . "/views/subscriber/lead-view-summary.php" );


				break;
			case 'send_invoice_view':
				$id        = intval( $_GET['id'] );
				$lead_id   = intval( $_GET['lead_id'] );
				$_lead     = new FSMSLead();
				$ownerShip = $_lead->checkOwnerShip( $lead_id, 4 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}

				include( wp_upload_dir()['basedir'] . "/fsms_invoices/viewInvoice_" . $id . ".html" );
				break;
			case 'send_quote_view':
				$id        = intval( $_GET['id'] );
				$lead_id   = intval( $_GET['lead_id'] );
				$_lead     = new FSMSLead();
				$ownerShip = $_lead->checkOwnerShip( $lead_id, 4 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}
				include( wp_upload_dir()['basedir'] . "/fsms_quotes/viewQuote_" . $id . ".html" );
				break;
			case 'is_approve_view':
				$_log      = new FSMSLog();
				$id        = intval( $_GET['id'] );
				$lead_id   = intval( $_GET['lead_id'] );
				$_lead     = new FSMSLead();
				$ownerShip = $_lead->checkOwnerShip( $lead_id, 4 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}
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


				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_invoices_status' ) ) {

					$_lead->approve( $_POST, $id );
					$logvar['lead_id'] = $lead_id;
					$appOrReject       = "Reject";
					if ( $_POST['is_approve'] == 1 ) {
						$appOrReject = "Approved";
					}
					$logvar['action_text'] = esc_attr( $quoteOrInvoiceTxt . "#" . $id . " " . $appOrReject );
					$logvar['action_done'] = get_current_user_id();
					$logvar['action_type'] = esc_attr( "Cutomer Invoice / Quote approve" );
					$_log->add( $logvar );
					fmsm_redirect( "admin.php?page=fsms_work_subscriber&action=lead_view&id=" . esc_attr( $lead_id ) . "#quote_block" );

				}
				include( FSMS_PATH . "/views/addEditInvoiceStatus.php" );
				break;


		}
	}
}