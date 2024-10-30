<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSWorker {


	function find( $filters = null ) {
		global $wpdb;


		$args         = array(
			'role'    => 'lead_worker',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',

			'fields' => array( 'ID', '	user_nicename' )
		);
		$lead_workers = get_users( $args );

		return $lead_workers;

	}

	function findLeads( $filters = null ) {
		global $wpdb;

		$rstart               = $filters['rstart'];
		$rend                 = $filters['rend'];
		$dir                  = $filters['dir'];
		$ob                   = $filters['ob'];
		$tablename_stage      = $wpdb->prefix . "fsms_stage";
		$tablename_users      = $wpdb->prefix . "users";
		$tablename_department = $wpdb->prefix . "fsms_department";
		$tablename            = $wpdb->prefix . "fsms_leads";
		$leads                = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,UW.user_nicename as worker,SD.department_name as subDepartmentName,D.department_name as departmentName, S.stage_name,U.user_nicename  FROM " . $tablename . " as L LEFT JOIN " . $tablename_stage . " as S ON S.id=L.lead_stage LEFT JOIN " . $tablename_users . " as U ON U.ID=L.lead_owner LEFT JOIN " . $tablename_department . " as D ON D.id=L.lead_dep_id LEFT JOIN " . $tablename_department . " as SD ON SD.id=L.lead_sub_dep_id LEFT JOIN " . $tablename_users . " as UW ON UW.ID=L.lead_worker WHERE L.lead_worker=" . get_current_user_id() . "  ORDER BY L.$ob $dir LIMIT %d, %d", $rstart, $rend ) );

		return $leads;
	}

	function getTotalLeadsCount( $filters = null ) {
		global $wpdb;
		$tablename       = $wpdb->prefix . "fsms_leads";
		$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename . " WHERE lead_worker=" . get_current_user_id() ) );

		return $totalLeadsCount;

	}

	function editLeadSheet( $vars, $id ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$lead_tablename = $wpdb->prefix . "fsms_leads";
		$result         = $wpdb->query( $wpdb->prepare( "UPDATE " . $lead_tablename . " SET
			lead_sheet=%s WHERE id=%d",
			$vars['lead_sheet'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	public static function prepare_vars( &$vars ) {
		$vars['lead_sheet'] = wp_kses_post( $vars['lead_sheet'] );

	}

}