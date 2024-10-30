<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSLog {
	public static function add( $vars ) {
		global $wpdb;

		FSMSLog::prepare_vars( $vars );
		$log_tablename = $wpdb->prefix . "fsms_lead_log";
		$result        = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $log_tablename . " SET
			lead_id=%d,action_text=%s,action_done=%d,action_type=%s,action_date_time=%s",
			$vars['lead_id'], $vars['action_text'], $vars['action_done'], $vars['action_type'], date( "Y-m-d H:i:s" ) ) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}


	// prepare and sanitize vars
	public static function prepare_vars( &$vars ) {
		$vars['lead_id']     = sanitize_text_field( $vars['lead_id'] );
		$vars['action_text'] = sanitize_text_field( $vars['action_text'] );
		$vars['action_done'] = sanitize_text_field( $vars['action_done'] );
		$vars['action_type'] = sanitize_text_field( $vars['action_type'] );

	}


	// list all log, paginated. 
	// allow filters
	function find( $id = null ) {
		global $wpdb;

		$log_tablename   = $wpdb->prefix . "fsms_lead_log";
		$tablename_users = $wpdb->prefix . "users";

		$log = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,U.user_nicename FROM " . $log_tablename . " as L LEFT JOIN " . $tablename_users . " as U ON U.ID=L.action_done WHERE lead_id=%d ORDER BY L.id DESC", $id ) );

		//echo $wpdb->last_query;
		return $log;

	}


}