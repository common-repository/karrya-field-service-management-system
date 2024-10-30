<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSOwner {


	function find( $filters = null ) {
		global $wpdb;


		$args        = array(
			'role'    => 'lead_owner',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',

			'fields' => array( 'ID', '	user_nicename' )
		);
		$lead_owners = get_users( $args );


		return $lead_owners;

	}

}