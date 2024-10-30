<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSCustomers {
	public static function manage() {
		$_customer = new FSMSCustomer();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_customer_add_edit' ) ) {
					$_customer->add( $_POST );
					$success = "added";
					//fmsm_redirect("admin.php?page=fsms_customers&action=list");
				}
				include( FSMS_PATH . "/views/addEditCustomer.php" );
				break;
			case 'view':
				$id         = intval( $_GET['id'] );
				$sites      = FSMSSite::findLeadSite( $id );
				$otherLeads = FSMSLead::findOtherLeads( $id );
				include( FSMS_PATH . "/views/customer_view.php" );
				break;
			case 'add_lead':
				$id       = intval( $_GET['id'] );
				$customer = $_customer->get( $id );


				$lead                 = new stdClass();
				$lead->lead_cus_fname = $customer->cust_fname;
				$lead->lead_cus_sname = $customer->cust_lname;
				$lead->lead_cus_phone = $customer->cust_phone;
				$lead->lead_email     = $customer->cust_email;
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_lead_add_edit' ) ) {

					$_site  = new FSMSSite();
					$siteId = $_site->addFromLead( $_POST, $id );

					$otherDetails = array( "site_id" => intval( $siteId ), "cust_id" => intval( $id ) );


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
			case 'delete':
				$id = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );

				$_customer->delete( $id );
				$success = "deleted";
				fmsm_redirect( "admin.php?page=fsms_customers&action=list" );
				break;
			case 'edit':
				$id = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_customer_add_edit' ) ) {
					$_customer->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_customers&action=list" );
				}
				$customer = $_customer->get( $id );
				include( FSMS_PATH . "/views/addEditCustomer.php" );
				break;
			case 'search':
				if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'fsms_customer_search' ) ) {

					$searchKey = sanitize_text_field( ( isset( $_GET['searchKey'] ) ) ? ( $_GET['searchKey'] ) : ( "" ) );


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
					$odir = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
					$ob   = "id";

					$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );

					if ( ! empty( $obGet ) ) {

						if ( $obGet == "id" ) {
							$ob = "id";
						}
						if ( $obGet == "fname" ) {
							$ob = "cust_fname";
						}
						if ( $obGet == "email" ) {
							$ob = "cust_email";
						}
						if ( $obGet == "phone" ) {
							$ob = "cust_phone";
						}


						$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
					}


					$filters    = array(
						"rstart"    => $rstart,
						"rend"      => $rend,
						"ob"        => $ob,
						"dir"       => $dir,
						"getOb"     => $obGet,
						"searchKey" => $searchKey,
						"nonce"     => $_GET['nonce'],
						"action"    => sanitize_text_field( $_GET['action'] )
					);
					$customers  = $_customer->find( $filters );
					$totalCount = $_customer->getTotalCount( $filters );
					include( FSMS_PATH . "/views/customers.php" );
				}

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
					if ( $obGet == "fname" ) {
						$ob = "cust_fname";
					}
					if ( $obGet == "email" ) {
						$ob = "cust_email";
					}
					if ( $obGet == "phone" ) {
						$ob = "cust_phone";
					}


					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}


				$filters   = array(
					"rstart"    => $rstart,
					"rend"      => $rend,
					"ob"        => $ob,
					"dir"       => $dir,
					"getOb"     => $obGet,
					"searchKey" => "",
					"nonce"     => "",
					"action"    => ""
				);
				$customers = $_customer->find( $filters );

				$totalCount = $_customer->getTotalCount();
				include( FSMS_PATH . "/views/customers.php" );
				break;
		}
	}
}