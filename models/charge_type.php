<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSChargeType {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$result                = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $charge_type_tablename . " SET
			charge_type=%s, display_order=%d, status=%d, list_in=%d, entry_category=%s",
			$vars['charge_type'], $vars['display_order'], $vars['status'], $vars['list_in'], $vars['entry_category'] ) );

		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// return specific department details
	function get( $id ) {
		global $wpdb;
		$id                    = intval( $id );
		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$chargeType            = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $charge_type_tablename . " WHERE id=%d", $id ) );

		return $chargeType;
	}


	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['charge_type']    = sanitize_text_field( $vars['charge_type'] );
		$vars['display_order']  = sanitize_text_field( $vars['display_order'] );
		$vars['status']         = sanitize_text_field( $vars['status'] );
		$vars['list_in']        = sanitize_text_field( $vars['list_in'] );
		$vars['entry_category'] = sanitize_text_field( $vars['entry_category'] );

	}


	function delete( $id ) {
		global $wpdb;
		$id                    = intval( $id );
		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$result                = $wpdb->query( $wpdb->prepare( "UPDATE " . $charge_type_tablename . " SET status=%d WHERE id=%d", 0, $id ) );

		if ( ! $result ) {
			return false;
		}

		return true;
	}

	function edit( $vars, $id ) {
		global $wpdb;


		$this->prepare_vars( $vars );
		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$result                = $wpdb->query( $wpdb->prepare( "UPDATE " . $charge_type_tablename . " SET
			charge_type=%s, display_order=%d, status=%d , list_in=%d, entry_category=%s  WHERE id=%d",
			$vars['charge_type'], $vars['display_order'], $vars['status'], $vars['list_in'], $vars['entry_category'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// allow filters
	public static function find( $filters = null ) {
		global $wpdb;

		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$chargeTypes           = $wpdb->get_results( ( "SELECT * FROM " . $charge_type_tablename . " ORDER BY display_order" ) );

		return $chargeTypes;
	}

	// allow filters
	public static function findActive( $filters = null ) {
		global $wpdb;

		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$chargeTypes           = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $charge_type_tablename . " WHERE status=1 AND list_in=%d ORDER BY display_order", 1 ) );


		return $chargeTypes;
	}

	// allow filters
	public static function findEntryCategory( $id = null ) {
		global $wpdb;

		$charge_type_tablename = $wpdb->prefix . "fsms_charge_type";
		$chargeTypes           = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $charge_type_tablename . " WHERE id	=%d ", $id ) );

		//echo $wpdb->last_query;
		return $chargeTypes;
	}
}