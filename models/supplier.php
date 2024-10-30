<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSupplier {


	function find( $filters = null ) {
		global $wpdb;


		$args         = array(
			'role'    => 'lead_supplier',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',

			'fields' => array( 'ID', '	user_nicename' )
		);
		$lead_suppliers = get_users( $args );

		return $lead_suppliers;

	}

	function findLeads( $filters = null ) {
		global $wpdb;

		$rstart               = $filters['rstart'];
		$rend                 = $filters['rend'];
		$dir                  = $filters['dir'];
		$ob                   = $filters['ob'];
		$tablename_stage      = $wpdb->prefix . "fsms_stage";
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$leads                = $wpdb->get_results( $wpdb->prepare( "SELECT S.*   FROM " . $stock_tablename . " as S  WHERE S.supplier_id=" . get_current_user_id() . "  ORDER BY S.$ob $dir LIMIT %d, %d", $rstart, $rend ) );

		return $leads;
	}

	function getTotalLeadsCount( $filters = null ) {
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stock_tablename . " WHERE supplier_id=" . get_current_user_id() ) );

		return $totalLeadsCount;

	}

	  

}