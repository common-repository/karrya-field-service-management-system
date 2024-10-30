<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSWorkers {
	public static function manages() {
		$_worker = new FSMSWorker();
		$action  = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {

			case 'list':
			default:
				$workers = $_worker->find();
				include( FSMS_PATH . "/views/workers.php" );
				break;
		}
	}

	public static function manage() {
		$_worker = new FSMSWorker();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

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
				$leads           = $_worker->findLeads( $filters );
				$totalLeadsCount = $_worker->getTotalLeadsCount();
				include( FSMS_PATH . "/views/worker/leads_workers.php" );
				break;
			case 'lead_view':
				$_lead     = new FSMSLead();
				$id        = intval( $_GET['id'] );
				$ownerShip = $_lead->checkOwnerShip( $id, 3 );
				if ( $ownerShip == 0 ) {
					die( "Sorry" );
				}
				$leadViewArr = $_lead->view( $id );
				$leadView    = $leadViewArr[0];


				$_message = new FSMSMessage();

				$allmessagesParentWorker = $_message->getAllMessages( 3, $id, 0 );


				include( FSMS_PATH . "/views/worker/leadHeading.php" );
				include( FSMS_PATH . "/views/worker/lead-view-owner-worker-message.php" );
				include( FSMS_PATH . "/views/worker/lead-view-summary.php" );
				include( FSMS_PATH . "/views/worker/lead-view.php" );

				break;
			case 'add_message':
				$_message = new FSMSMessage();
				$id       = intval( $_GET['id'] );


				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_message_add' ) ) {
					$_POST['message_added_by'] = get_current_user_id();
					$_POST['message_lead_id']  = $id;
					$_POST['message_for_id']   = sanitize_text_field( $_POST['message_for_id'] );
					if ( $_POST['user_type'] == "2" ) {
						$_POST['message'] = wp_kses_post( wpautop( $_POST['message_owner'] ) );
					} else {
						$_POST['message'] = wp_kses_post( wpautop( $_POST['message_worker'] ) );
					}


					$_message->add( $_POST );
					fmsm_redirect( "admin.php?page=fsms_work_leads&action=lead_view&id=" . esc_attr( $id ) );
				}
				break;
			case 'lead_sheet':
				$id = intval( $_GET['id'] );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_sheet' ) ) {
					$_worker->editLeadSheet( $_POST, $id );
					$success               = "edit";
					$_log                  = new FSMSLog();
					$logvar['lead_id']     = $id;
					$logvar['action_text'] = esc_attr( "Lead sheet added" );
					$logvar['action_done'] = get_current_user_id();
					$logvar['action_type'] = esc_attr( "lead sheet" );
					$_log->add( $logvar );
					fmsm_redirect( "admin.php?page=fsms_work_leads&action=lead_view&id=" . esc_attr( $id ) );
				}
				$_lead = new FSMSLead();
				$lead  = $_lead->get( $id );
				include( FSMS_PATH . "/views/worker/addEditLeadSheet.php" );
				break;

		}
	}
}