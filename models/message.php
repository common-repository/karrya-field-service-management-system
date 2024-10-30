<?php

class FSMSMessage {
	public function __construct() {
	}

	public static function getAllMessages( $userType, $id, $pid ) {
		global $wpdb;
		$id                = intval( $id );
		$message_tablename = $wpdb->prefix . "fsms_message";
		$user_tablename    = $wpdb->prefix . "users";
		$messages          = $wpdb->get_results( $wpdb->prepare( "SELECT M.*, U.user_nicename FROM " . $message_tablename . " as M LEFT JOIN " . $user_tablename . " as U ON M.message_added_by = U.ID WHERE M.user_type=%d AND M.message_parent_id=%d AND M.message_lead_id=%d", $userType, $pid, $id ) );
		//echo "<br>";
		//echo $wpdb->last_query;
		return $messages;
	}

	public function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$message_tablename = $wpdb->prefix . "fsms_message";
		$result            = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $message_tablename . " SET
			message_parent_id=%d,message_added_by=%d,message_lead_id=%d,message_for_id=%d,message=%s,message_added_time=%s,user_type=%d",
			$vars['message_parent_id'], $vars['message_added_by'], $vars['message_lead_id'], $vars['message_for_id'], $vars['message'], date( "Y-m-d H:i:s" ), $vars['user_type'] ) );

		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['message_parent_id'] = sanitize_text_field( $vars['message_parent_id'] );
		$vars['message_added_by']  = sanitize_text_field( $vars['message_added_by'] );
		$vars['message_lead_id']   = sanitize_text_field( $vars['message_lead_id'] );
		$vars['message_for_id']    = sanitize_text_field( $vars['message_for_id'] );
		$vars['message']           = wp_kses_post( $vars['message'] );
		$vars['user_type']         = sanitize_text_field( $vars['user_type'] );

	}
}