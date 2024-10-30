<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSites {
	public static function manage() {
		$_site = new FSMSSite();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) and check_admin_referer( 'fsms_stage' ) ) {
					$_stage->add( $_POST );
					$success = "added";
					//fmsm_redirect("admin.php?page=fsms_sites&action=list");
				}
				include( FSMS_PATH . "/views/addEditSite.php" );
				break;
			case 'view':
				$id         = intval( $_GET['id'] );
				$site       = $_site->get( $id );
				$otherLeads = FSMSLead::findOtherLeads( $site->cust_id );
				include( FSMS_PATH . "/views/site_view.php" );
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

					if ( $obGet == "id" ) {
						$ob = "id";
					}
					if ( $obGet == "stagename" ) {
						$ob = "stage_name";
					}
					if ( $obGet == "stageorder" ) {
						$ob = "stage_order";
					} else {
						$ob = "id";
					}


					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}


				$filters    = array(
					"rstart"    => $rstart,
					"rend"      => $rend,
					"ob"        => $ob,
					"dir"       => $dir,
					"getOb"     => $obGet,
					"searchKey" => "",
					"nonce"     => "",
					"action"    => ""
				);
				$sites      = $_site->find( $filters );
				$totalCount = $_site->getTotalCount();
				include( FSMS_PATH . "/views/sites.php" );
				break;
			case 'add_lead':
				$id   = intval( $_GET['id'] );
				$site = $_site->get( $id );


				$lead                 = new stdClass();
				$lead->lead_cus_fname = $site->site_name;
				$lead->lead_cus_city  = $site->site_city;

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_add_edit' ) ) {

					$otherDetails = array( "site_id" => intval( $id ), "cust_id" => intval( $site->cust_id ) );


					FSMSLead::add( $_POST, $otherDetails );
					fmsm_redirect( "admin.php?page=fsms_leads" );
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
		}
	}
}