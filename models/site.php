<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSite {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$stage_tablename = $wpdb->prefix . "fsms_site";
		$result          = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $stage_tablename . " SET
			site_name=%s,site_city=%s,cust_id=%d",
			$vars['stage_name'], $vars['stage_order'], $vars['stage_status'] ) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}

	function addFromLead( $vars, $customerId ) {
		global $wpdb;

		$this->prepare_from_lead_vars( $vars );
		$stage_tablename = $wpdb->prefix . "fsms_site";
		$result          = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $stage_tablename . " SET
			site_name=%s,site_city=%s,cust_id=%d,site_status=%d",
			$vars['site_name'], $vars['site_city'], $customerId, 1 ) );

		$lastid = $wpdb->insert_id;
		if ( $result === false ) {
			return false;
		}

		return $lastid;
	}

	// prepare and sanitize vars
	function prepare_from_lead_vars( &$vars ) {
		$vars['site_name'] = sanitize_text_field( $vars['lead_cus_fname'] );
		$vars['site_city'] = sanitize_text_field( $vars['lead_cus_city'] );

	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['site_city']   = sanitize_text_field( $vars['site_city'] );
		$vars['site_status'] = sanitize_text_field( $vars['site_status'] );
		$vars['site_name']   = sanitize_text_field( $vars['site_name'] );

	}

	public static function findLeadSite( $id = null ) {
		global $wpdb;
		$site_tablename = $wpdb->prefix . "fsms_site";
		$sites          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $site_tablename . " WHERE cust_id =%d", $id ) );

		//echo $wpdb->last_query;
		return $sites;
	}
	// list all site, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$site_tablename = $wpdb->prefix . "fsms_site";
		$rstart         = $filters['rstart'];
		$rend           = $filters['rend'];
		$dir            = $filters['dir'];
		$ob             = $filters['ob'];

		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE site_status=1 AND site_name LIKE %s";
			$searchvar   = $filters['searchKey'];
			$sites       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $site_tablename . " " . $searchParam . " ORDER BY $ob $dir LIMIT %d, %d", "%" . $searchvar . "%", $rstart, $rend ) );
		} else {
			$sites = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $site_tablename . " WHERE site_status=1 ORDER BY $ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}

		return $sites;

	}

	function getTotalCount( $filters = null ) {
		global $wpdb;
		$param     = "";
		$searchVar = "";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE site_status	=1 AND site_name	 LIKE '%" . $filters['searchKey'] . "'";
		}
		$site_tablename = $wpdb->prefix . "fsms_site";
		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $site_tablename . "" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $site_tablename . " WHERE site_status=1" ) );
		}


		return $totalCount;

	}

	function edit( $vars, $id ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$result         = $wpdb->query( $wpdb->prepare( "UPDATE " . $site_tablename . " SET
			site_name=%s, site_city=%s, site_status=%d WHERE id=%d",
			$vars['site_name'], $vars['site_city'], $vars['site_status'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// return specific site details
	function get( $id ) {
		global $wpdb;
		$id             = intval( $id );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$site           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $site_tablename . " WHERE id=%d", $id ) );

		return $site;
	}

	function delete( $id ) {
		global $wpdb;
		$id             = intval( $id );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$result         = $wpdb->query( $wpdb->prepare( "UPDATE " . $site_tablename . " SET site_status=%d WHERE id=%d", 0, $id ) );


		if ( ! $result ) {
			return false;
		}

		return true;
	}

}