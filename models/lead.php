<?php

class FSMSLead {

	public $tablename;
	public $payment_tablename;

	public function __construct() {
		global $wpdb;
		$this->tablename         = $wpdb->prefix . "fsms_leads";
		$this->payment_tablename = $wpdb->prefix . "fsms_payment";


	}

	public function checkOwnerShip( $id, $userType ) {
		global $wpdb;
		$tablename       = $wpdb->prefix . "fsms_leads";
		$totalLeadsCount = 0;
		if ( $userType == 3 ) {
			$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename . " WHERE lead_worker=" . get_current_user_id() . " AND id=" . $id ) );
		}
		if ( $userType == 2 ) {
			$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename . " WHERE lead_owner=" . get_current_user_id() . " AND id=" . $id ) );
		}
		if ( $userType == 4 ) {
			$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $tablename . " WHERE subscriber_id =" . get_current_user_id() . " AND id=" . $id ) );
		}
		//echo $wpdb->last_query;
		//echo $totalLeadsCount;
		return $totalLeadsCount;

	}

	static function approve( &$vars, $id ) {
		global $wpdb;
		$invoice_tablename = $wpdb->prefix . "fsms_invoice";
		$result            = $wpdb->query( $wpdb->prepare( "UPDATE " . $invoice_tablename . " SET
			is_approve=%s, is_approve_date=%s,is_approve_by=%d WHERE id=%d",
			$vars['is_approve'], date( "Y-m-d H:i:s" ), get_current_user_id(), $id ) );
		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	public static function add( $vars, $otherDetails ) {
		global $wpdb;

		FSMSLead::prepare_vars( $vars );
		$tablename = $wpdb->prefix . "fsms_leads";
		$result    = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $tablename . " 
			SET
			customer_upload_images	=%s, 
			lead_added_date	=%s, 
			lead_cus_fname=%s, 
			lead_cus_sname=%s,
			invoice_top_blcok=%s,
			invoice_bottom_blcok=%s,

			quote_top_blcok=%s,
			quote_bottom_blcok=%s,
			lead_cus_phone=%s,
			lead_cus_address=%s,
			lead_cus_city=%s,

			lead_cus_postcode=%s,
			lead_details=%s,
			lead_app_date=%s,
			lead_email=%s,
			lead_owner =%d,

			lead_worker =%d,
			lead_dep_id=%d,
			lead_sub_dep_id=%d,	
			lead_stage=%d,
			cust_id=%d,
			subscriber_id=%d,
			site_id=%d

			
			",
			$vars['customer_upload_images'],
			date( "Y-m-d H:i:s" ),
			$vars['lead_cus_fname'],
			$vars['lead_cus_sname'],
			$vars['invoice_top_blcok'],
			$vars['invoice_bottom_blcok'],

			$vars['quote_top_blcok'],
			$vars['quote_bottom_blcok'],
			$vars['lead_cus_phone'],
			$vars['lead_cus_address'],
			$vars['lead_cus_city'],

			$vars['lead_cus_postcode'],
			$vars['lead_details'],
			$vars['lead_app_date'],
			$vars['lead_email'],
			$vars['lead_owner'],

			$vars['lead_worker'],
			$vars['lead_dep_id'],
			$vars['lead_sub_dep_id'],
			$vars['lead_stage'],
			$otherDetails['cust_id'],
			get_current_user_id(),
			$otherDetails['site_id']
		) );


		//echo $wpdb->last_query;
		$lastid = $wpdb->insert_id;
		//die();	
		if ( $result === false ) {
			return false;
		}

		return $lastid;
	}

	function edit( $vars, $id ) {
		global $wpdb;

		$this->prepare_vars( $vars );

		$result = $wpdb->query( $wpdb->prepare( "UPDATE  " . $this->tablename . " 
			SET 
			
			lead_cus_fname=%s, 
			lead_cus_sname=%s,
			invoice_top_blcok=%s,
			invoice_bottom_blcok=%s,

			quote_top_blcok=%s,
			quote_bottom_blcok=%s,
			lead_cus_phone=%s,
			lead_cus_address=%s,
			lead_cus_city=%s,

			lead_cus_postcode=%s,
			lead_details=%s,
			lead_app_date=%s,
			lead_email=%s,
			lead_owner =%d,

			lead_worker =%d,
			lead_dep_id=%d,
			lead_sub_dep_id=%d,	
			lead_stage=%d
			 

			
		    WHERE id=%d",

			$vars['lead_cus_fname'],
			$vars['lead_cus_sname'],
			wpautop( $vars['invoice_top_blcok'] ),
			wpautop( $vars['invoice_bottom_blcok'] ),

			wpautop( $vars['quote_top_blcok'] ),
			wpautop( $vars['quote_bottom_blcok'] ),
			$vars['lead_cus_phone'],
			$vars['lead_cus_address'],
			$vars['lead_cus_city'],

			$vars['lead_cus_postcode'],
			wpautop( $vars['lead_details'] ),
			$vars['lead_app_date'],
			$vars['lead_email'],
			$vars['lead_owner'],

			$vars['lead_worker'],
			$vars['lead_dep_id'],
			$vars['lead_sub_dep_id'],
			$vars['lead_stage']


			, $id
		) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// prepare and sanitize vars
	public static function prepare_vars( &$vars ) {
		$vars['lead_cus_fname']       = sanitize_text_field( $vars['lead_cus_fname'] );
		$vars['lead_cus_sname']       = sanitize_text_field( $vars['lead_cus_sname'] );
		$vars['quote_bottom_blcok']   = wp_kses_post( $vars['quote_bottom_blcok'] );
		$vars['quote_top_blcok']      = wp_kses_post( $vars['quote_top_blcok'] );
		$vars['invoice_bottom_blcok'] = wp_kses_post( $vars['invoice_bottom_blcok'] );
		$vars['invoice_top_blcok']    = wp_kses_post( $vars['invoice_top_blcok'] );

		$vars['lead_email']        = sanitize_email( $vars['lead_email'] );
		$vars['lead_details']      = wp_kses_post( $vars['lead_details'] );
		$vars['lead_cus_postcode'] = sanitize_text_field( $vars['lead_cus_postcode'] );
		$vars['lead_cus_city']     = sanitize_text_field( $vars['lead_cus_city'] );
		$vars['lead_cus_address']  = sanitize_text_field( $vars['lead_cus_address'] );
		$vars['lead_cus_phone']    = sanitize_text_field( $vars['lead_cus_phone'] );
		$vars['lead_app_date']     = sanitize_text_field( $vars['lead_app_date'] );


	}
	// list all lead, paginated. 
	// allow filters
	public static function view( $id = 0 ) {
		global $wpdb;
		$tablename            = $wpdb->prefix . "fsms_leads";
		$tablename_stage      = $wpdb->prefix . "fsms_stage";
		$tablename_users      = $wpdb->prefix . "users";
		$tablename_department = $wpdb->prefix . "fsms_department";
		$tablename_leads      = $wpdb->prefix . "fsms_leads";

		$leadView = $wpdb->get_results( $wpdb->prepare( "SELECT L.*, S.stage_name, D.department_name ,SD.department_name as sub_department_name, UW.user_nicename as worker ,U.user_nicename as owner  FROM " . $tablename_leads . " as L LEFT JOIN " . $tablename_stage . " as S ON S.id=L.lead_stage LEFT JOIN " . $tablename_users . " as U ON U.ID=L.lead_owner LEFT JOIN " . $tablename_department . " as D ON D.id=L.lead_dep_id LEFT JOIN " . $tablename_department . " as SD ON SD.id=L.lead_sub_dep_id LEFT JOIN " . $tablename_users . " as UW ON UW.ID=L.lead_worker  WHERE L.id=%d", $id ) );

		return $leadView;
	}
	// list all leads, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$user = wp_get_current_user();

		$rstart               = $filters['rstart'];
		$rend                 = $filters['rend'];
		$dir                  = $filters['dir'];
		$ob                   = $filters['ob'];
		$tablename_stage      = $wpdb->prefix . "fsms_stage";
		$tablename_users      = $wpdb->prefix . "users";
		$tablename_department = $wpdb->prefix . "fsms_department";
		if ( in_array( 'lead_owner', (array) $user->roles ) ) {
			$leads = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,UW.user_nicename as worker,SD.department_name as subDepartmentName,D.department_name as departmentName, S.stage_name,U.user_nicename  FROM " . $this->tablename . " as L LEFT JOIN " . $tablename_stage . " as S ON S.id=L.lead_stage LEFT JOIN " . $tablename_users . " as U ON U.ID=L.lead_owner LEFT JOIN " . $tablename_department . " as D ON D.id=L.lead_dep_id LEFT JOIN " . $tablename_department . " as SD ON SD.id=L.lead_sub_dep_id LEFT JOIN " . $tablename_users . " as UW ON UW.ID=L.lead_worker WHERE L.lead_owner=" . get_current_user_id() . "  ORDER BY L.$ob $dir LIMIT %d, %d", $rstart, $rend ) );
		} else {
			$leads = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,UW.user_nicename as worker,SD.department_name as subDepartmentName,D.department_name as departmentName, S.stage_name,U.user_nicename  FROM " . $this->tablename . " as L LEFT JOIN " . $tablename_stage . " as S ON S.id=L.lead_stage LEFT JOIN " . $tablename_users . " as U ON U.ID=L.lead_owner LEFT JOIN " . $tablename_department . " as D ON D.id=L.lead_dep_id LEFT JOIN " . $tablename_department . " as SD ON SD.id=L.lead_sub_dep_id LEFT JOIN " . $tablename_users . " as UW ON UW.ID=L.lead_worker  ORDER BY L.$ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}

		return $leads;
	}

	function getTotalLeadsCount( $filters = null ) {
		global $wpdb;
		$totalLeadsCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $this->tablename . "" ) );

		return $totalLeadsCount;

	}

	// return specific lead details
	function get( $id ) {
		global $wpdb;
		$id             = intval( $id );
		$lead_tablename = $wpdb->prefix . "fsms_leads";
		$lead           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $lead_tablename . " WHERE id=%d", $id ) );

		return $lead;
	}

	public static function findOtherLeads( $id ) {
		global $wpdb;
		$id              = intval( $id );
		$leads_tablename = $wpdb->prefix . "fsms_leads";
		$otherLeads      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $leads_tablename . " WHERE cust_id=%d", $id ) );


		return $otherLeads;

	}


}