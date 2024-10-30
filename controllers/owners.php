<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSOwners {
	public static function manage() {
		$_owner = new FSMSOwner();
		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );

		switch ( $action ) {

			case 'list':
			default:
				$owners = $_owner->find();
				include( FSMS_PATH . "/views/owner.php" );
				break;
		}
	}
}