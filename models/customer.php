<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSCustomer {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$result             = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $customer_tablename . " SET
			cust_fname	=%s, cust_lname=%s, cust_phone=%s, cust_status=%s , cust_email=%s",
			$vars['cust_fname'], $vars['cust_lname'], $vars['cust_phone'], $vars['cust_status'], $vars['cust_email'] ) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}

	function addFromLead( $vars ) {
		global $wpdb;

		$this->prepare_from_lead_vars( $vars );
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$result             = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $customer_tablename . " SET
			cust_fname	=%s, cust_lname=%s, cust_phone=%s, cust_status=%s , cust_email=%s, subscriber_id=%d",
			$vars['cust_fname'], $vars['cust_lname'], $vars['cust_phone'], 1, $vars['cust_email'], get_current_user_id() ) );

		$lastid = $wpdb->insert_id;

		if ( $result === false ) {
			return false;
		}

		return $lastid;
	}

	// prepare and sanitize vars
	function prepare_from_lead_vars( &$vars ) {
		$vars['cust_fname'] = sanitize_text_field( $vars['lead_cus_fname'] );
		$vars['cust_lname'] = sanitize_text_field( $vars['lead_cus_sname'] );
		$vars['cust_email'] = sanitize_email( $vars['lead_email'] );
		$vars['cust_phone'] = sanitize_text_field( $vars['lead_cus_phone'] );


	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['cust_fname']  = sanitize_text_field( $vars['cust_fname'] );
		$vars['cust_lname']  = sanitize_text_field( $vars['cust_lname'] );
		$vars['cust_email']  = sanitize_email( $vars['cust_email'] );
		$vars['cust_phone']  = sanitize_text_field( $vars['cust_phone'] );
		$vars['cust_status'] = sanitize_text_field( $vars['cust_status'] );

	}
	// list all customer, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$rstart             = $filters['rstart'];
		$rend               = $filters['rend'];
		$dir                = $filters['dir'];
		$ob                 = $filters['ob'];

		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE cust_status=1 AND cust_fname LIKE %s";
			$searchvar   = $filters['searchKey'];
			$customers   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $customer_tablename . " " . $searchParam . " ORDER BY $ob $dir LIMIT %d, %d", "%" . $searchvar . "%", $rstart, $rend ) );
		} else {
			$customers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $customer_tablename . " WHERE cust_status=1 ORDER BY $ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}


		return $customers;

	}

	function getTotalCount( $filters = null ) {
		global $wpdb;
		$param     = "";
		$searchVar = "";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE cust_status=1 AND cust_fname LIKE '%" . $filters['searchKey'] . "'";
		}
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $customer_tablename . "" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $customer_tablename . " WHERE cust_status=1" ) );
		}


		return $totalCount;

	}

	function edit( $vars, $id ) {
		global $wpdb;


		$this->prepare_vars( $vars );
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$result             = $wpdb->query( $wpdb->prepare( "UPDATE " . $customer_tablename . " SET
			cust_fname	=%s, cust_lname=%s, cust_phone=%s, cust_status=%s , cust_email=%s WHERE id=%d",
			$vars['cust_fname'], $vars['cust_lname'], $vars['cust_phone'], $vars['cust_status'], $vars['cust_email'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// return specific stage details
	function get( $id ) {
		global $wpdb;
		$id                 = intval( $id );
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$customer           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $customer_tablename . " WHERE id=%d", $id ) );

		return $customer;
	}

	function delete( $id ) {
		global $wpdb;
		$id                 = intval( $id );
		$customer_tablename = $wpdb->prefix . "fsms_customer";
		$result             = $wpdb->query( $wpdb->prepare( "UPDATE " . $customer_tablename . " SET cust_status=%d WHERE id=%d", 0, $id ) );

		//echo $wpdb->last_query;
		if ( ! $result ) {
			return false;
		}

		return true;
	}

}