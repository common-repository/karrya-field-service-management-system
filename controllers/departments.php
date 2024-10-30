<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSDepartments {
	public static function manage() {
		$_department = new FSMSDepartment();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );


		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_department_add_edit' ) ) {
					$_department->add( $_POST );

					fmsm_redirect( "admin.php?page=fsms_departments" );
				}
				include( FSMS_PATH . "/views/addEditDepartment.php" );
				break;
			case 'delete':
				$id = intval( $_GET['id'] );
				$_department->delete( $id );
				$success = "deleted";
				fmsm_redirect( "admin.php?page=fsms_departments&action=list" );
				break;
			case 'edit':
				$id = intval( $_GET['id'] );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_department_add_edit' ) ) {
					$_department->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_departments&action=list" );
				}
				$department = $_department->get( $id );
				include( FSMS_PATH . "/views/addEditDepartment.php" );
				break;
			case 'list':
			default:
				$departments = $_department->find();
				include( FSMS_PATH . "/views/departments.php" );
				break;
			case 'add_sub_dep':
				$_subDepartment = new FSMSSubDepartment();
				$id             = intval( $_GET['id'] );
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_sub_deparment_add_edit' ) ) {

					$_subDepartment->sub_add( $_POST );

					fmsm_redirect( "admin.php?page=fsms_departments&action=list" );
				}
				include( FSMS_PATH . "/views/sub_department.php" );
				break;

			case 'department_view':
				$id                = intval( $_GET['id'] );
				$departmentViewArr = $_department->view( $id );
				$departmentView    = $departmentViewArr[0];
				$subDepartments    = $_department->subFind( $id );
				include( FSMS_PATH . "/views/subDepartments.php" );
				break;

		}
	}
}