<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSSubDepartment {

	function sub_add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$result               = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $department_tablename . " SET
			department_name=%s,department_order=%d,department_status=%d, department_parent_id=%d  ",
			$vars['department_name'], $vars['department_order'], $vars['department_status'], $vars['id'] ) );

		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['department_name']   = sanitize_text_field( $vars['department_name'] );
		$vars['department_order']  = sanitize_text_field( $vars['department_order'] );
		$vars['department_status'] = sanitize_text_field( $vars['department_status'] );

	}

	function subFind( $id = null ) {
		global $wpdb;


		$department_tablename = $wpdb->prefix . "fsms_department";
		$subDepartments       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $department_tablename . " WHERE department_parent_id=%d ORDER BY id", $id ) );

		return $subDepartments;
	}


}