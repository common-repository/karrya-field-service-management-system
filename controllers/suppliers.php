<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSuppliers {
	public static function manages() {
		$_supplier = new FSMSSupplier();
		$action  = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {

			case 'list':
			default:
				$suppliers = $_supplier->find();
				include( FSMS_PATH . "/views/supplier/suppliers.php" );
				break;
		}
	}

	public static function manage() {
		$_supplier = new FSMSSupplier();

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
				$stocks           = $_supplier->findLeads( $filters );
				$totalCount = $_supplier->getTotalLeadsCount();
				include( FSMS_PATH . "/views/supplier/leads_suppliers.php" );
				break;	 
			

		}
	}
}