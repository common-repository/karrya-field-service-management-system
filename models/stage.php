<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSStage {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$result          = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $stage_tablename . " SET
			stage_name=%s,stage_order=%d,stage_status=%d",
			$vars['stage_name'], $vars['stage_order'], $vars['stage_status'] ) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['stage_name']   = sanitize_text_field( $vars['stage_name'] );
		$vars['stage_order']  = sanitize_text_field( $vars['stage_order'] );
		$vars['stage_status'] = sanitize_text_field( $vars['stage_status'] );

	}
	// list all stage, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$rstart          = $filters['rstart'];
		$rend            = $filters['rend'];
		$dir             = $filters['dir'];
		$ob              = $filters['ob'];

		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE stage_status=1 AND stage_name LIKE %s";
			$searchvar   = $filters['searchKey'];
			$stages      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $stage_tablename . " " . $searchParam . " ORDER BY $ob $dir LIMIT %d, %d", "%" . $searchvar . "%", $rstart, $rend ) );
		} else {
			$stages = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $stage_tablename . "   ORDER BY $ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}

		//echo $wpdb->last_query;
		return $stages;

	}

	function getTotalCount( $filters = null ) {
		global $wpdb;
		$param     = "";
		$searchVar = "";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE stage_status=1 AND stage_name LIKE '%" . $filters['searchKey'] . "'";
		}
		$stage_tablename = $wpdb->prefix . "fsms_stage";
		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stage_tablename . "" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stage_tablename . "" ) );
		}


		return $totalCount;

	}

	function edit( $vars, $id ) {
		global $wpdb;


		$this->prepare_vars( $vars );
		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$result          = $wpdb->query( $wpdb->prepare( "UPDATE " . $stage_tablename . " SET
			stage_name=%s, stage_status=%d, stage_order=%d WHERE id=%d",
			$vars['stage_name'], $vars['stage_status'], $vars['stage_order'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// return specific stage details
	function get( $id ) {
		global $wpdb;
		$id              = intval( $id );
		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$stage           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $stage_tablename . " WHERE id=%d", $id ) );

		return $stage;
	}

	function delete( $id ) {
		global $wpdb;
		$id              = intval( $id );
		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$result          = $wpdb->query( $wpdb->prepare( "UPDATE " . $stage_tablename . " SET stage_status=%d WHERE id=%d", 0, $id ) );

		//echo $wpdb->last_query;
		if ( ! $result ) {
			return false;
		}

		return true;
	}

	function stageList( $filters = null ) {
		global $wpdb;


		$stage_tablename = $wpdb->prefix . "fsms_stage";
		$stages          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $stage_tablename . "  ORDER BY %s ", "id" ) );


		return $stages;

	}

}