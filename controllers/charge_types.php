<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSChargeTypes {
	public static function manage() {
		$_chargeType = new FSMSChargeType();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );


		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_charge_type_add_edit' ) ) {
					$_chargeType->add( $_POST );

					fmsm_redirect( "admin.php?page=fsms_charge_types" );
				}
				include( FSMS_PATH . "/views/addEditChargeType.php" );
				break;
			case 'delete':
				$id = intval( $_GET['id'] );
				$_chargeType->delete( $id );
				$success = "deleted";
				fmsm_redirect( "admin.php?page=fsms_charge_types&action=list" );
				break;
			case 'edit':
				$id = intval( $_GET['id'] );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_charge_type_add_edit' ) ) {
					$_chargeType->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_charge_types&action=list" );
				}
				$chargeType = $_chargeType->get( $id );
				include( FSMS_PATH . "/views/addEditChargeType.php" );
				break;
			case 'list':
			default:
				$chargeTypes = $_chargeType->find();
				include( FSMS_PATH . "/views/chargeTypes.php" );
				break;


		}
	}
}