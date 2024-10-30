<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSDepartment {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$result               = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $department_tablename . " SET
			department_name=%s, department_order=%d, department_status=%d",
			$vars['department_name'], $vars['department_order'], $vars['department_status'] ) );

		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	function sub_add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$result               = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $department_tablename . " SET
			department_name=%s, department_parent_id=%d  ",
			$vars['department'], $vars['id'] ) );


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
	// list all department, paginated. 
	// allow filters
	public static function find( $filters = null ) {
		global $wpdb;

		$department_tablename = $wpdb->prefix . "fsms_department";
		$departments          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $department_tablename . " WHERE department_parent_id=%d ORDER BY department_order", 0 ) );

		return $departments;
	}

	function subFind( $id = null ) {
		global $wpdb;


		$department_tablename = $wpdb->prefix . "fsms_department";
		$subDepartments       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $department_tablename . " WHERE department_parent_id=%d ORDER BY id", $id ) );

		return $subDepartments;
	}

	function view( $id = 0 ) {
		global $wpdb;
		$department_tablename = $wpdb->prefix . "fsms_department";
		$departmentView       = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $department_tablename . " WHERE id=%d", $id ) );

		return $departmentView;
	}

	public static function list_subdepartment() {


		$dep_id     = sanitize_text_field( ( isset( $_POST['dep_id'] ) ) ? ( $_POST['dep_id'] ) : ( 0 ) );
		$sub_dep_id = sanitize_text_field( ( isset( $_POST['sub_dep_id'] ) ) ? ( $_POST['sub_dep_id'] ) : ( 0 ) );

		$_department             = new FSMSDepartment();
		$subDeparmentLists       = $_department->subFind( $dep_id );
		$subdepartmentOptionList = "<option>Please select</option>";
		foreach ( $subDeparmentLists as $subDeparmentList ):
			$selected = "";
			if ( $sub_dep_id == $subDeparmentList->id ) {
				$selected = "selected";
			}
			$subdepartmentOptionList .= "<option " . esc_attr( $selected ) . " value='" . esc_attr( $subDeparmentList->id ) . "'>" . esc_attr( $subDeparmentList->department_name ) . "</option>";
		endforeach;
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $subdepartmentOptionList );
		die();
	}

	// return specific department details
	function get( $id ) {
		global $wpdb;
		$id                   = intval( $id );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$department           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $department_tablename . " WHERE id=%d", $id ) );

		return $department;
	}

	function delete( $id ) {
		global $wpdb;
		$id                   = intval( $id );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$result               = $wpdb->query( $wpdb->prepare( "UPDATE " . $department_tablename . " SET department_status=%d WHERE id=%d", 0, $id ) );

		if ( ! $result ) {
			return false;
		}

		return true;
	}

	function edit( $vars, $id ) {
		global $wpdb;


		$this->prepare_vars( $vars );
		$department_tablename = $wpdb->prefix . "fsms_department";
		$result               = $wpdb->query( $wpdb->prepare( "UPDATE " . $department_tablename . " SET
			department_name=%s, department_status=%d, department_order=%d WHERE id=%d",
			$vars['department_name'], $vars['department_status'], $vars['department_order'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}
}