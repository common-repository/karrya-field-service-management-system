<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSStocks {
	public static function manage() {
		$_stock = new FSMSStock();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {
			case 'add':

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_stock_add_edit' ) ) {
					$_stock->add( $_POST );
					$success = "added";
					fmsm_redirect("admin.php?page=fsms_stocks&action=list");
				}
				$args         = array(
					'role'    => 'lead_supplier',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_suppliers  = get_users( $args );
				$_chargeTypes = new FSMSChargeType();
				$chargeTypes  = $_chargeTypes->findActive();
				include( FSMS_PATH . "/views/addEditStock.php" );
				break;
			case 'view':
				$id    = intval( $_GET['id'] );
				$stock = $_stock->get( $id );

				include( FSMS_PATH . "/views/stock_view.php" );
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
					if ( $obGet == "sku" ) {
						$ob = "sku";
					}
					if ( $obGet == "description" ) {
						$ob = "description";
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
					"action"    => "list"
				);
				$stocks     = $_stock->find( $filters );
				$totalCount = $_stock->getTotalCount();
				include( FSMS_PATH . "/views/stocks.php" );
				break;
			case 'edit':
				$id = intval( $_GET['id'] );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_stock_add_edit' ) ) {
					$_stock->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_stocks&action=list" );
				}
				$stock        = $_stock->get( $id );
				$_chargeTypes = new FSMSChargeType();
				$chargeTypes  = $_chargeTypes->findActive();
				$args         = array(
					'role'    => 'lead_supplier',
					'orderby' => 'user_nicename',
					'order'   => 'ASC',
					'fields'  => array( 'ID', '	user_nicename' )
				);
				$lead_suppliers  = get_users( $args );
				include( FSMS_PATH . "/views/addEditStock.php" );
				break;
			case 'search':
				if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'fsms_stock_search' ) ) {

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
					$dir    = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
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
						if ( $obGet == "sku" ) {
							$ob = "sku";
						}
						if ( $obGet == "description" ) {
							$ob = "description";
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
						"searchKey" => $searchKey,
						"nonce"     => $_GET['nonce'],
						"action"    => $action
					);
					$stocks     = $_stock->find( $filters );
					$totalCount = $_stock->getTotalCount( $filters );
					include( FSMS_PATH . "/views/stocks.php" );
				}

				break;

		}
	}
}