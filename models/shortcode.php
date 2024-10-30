<?php

class FSMSLead {

	public $tablename;
	public $payment_tablename;

	public function __construct() {
		global $wpdb;
		$this->tablename         = $wpdb->prefix . "fsms_leads";
		$this->payment_tablename = $wpdb->prefix . "fsms_payment";


	}


	function add( $vars, $otherDetails ) {
		global $wpdb;
		die();
		$this->prepare_vars( $vars );

		$result = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $this->tablename . " 
			SET
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

			site_id=%d

			
			",
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

			$otherDetails['site_id']
		) );


		if ( $result === false ) {
			return false;
		}

		return true;
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
			$vars['lead_stage']


			, $id
		) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['lead_cus_fname']       = sanitize_text_field( $vars['lead_cus_fname'] );
		$vars['lead_cus_sname']       = sanitize_text_field( $vars['lead_cus_sname'] );
		$vars['quote_bottom_blcok']   = sanitize_text_field( $vars['quote_bottom_blcok'] );
		$vars['quote_top_blcok']      = sanitize_text_field( $vars['quote_top_blcok'] );
		$vars['invoice_bottom_blcok'] = sanitize_text_field( $vars['invoice_bottom_blcok'] );
		$vars['invoice_top_blcok']    = sanitize_text_field( $vars['invoice_top_blcok'] );

		$vars['lead_email']        = sanitize_email( $vars['lead_email'] );
		$vars['lead_details']      = sanitize_text_field( $vars['lead_details'] );
		$vars['lead_cus_postcode'] = sanitize_text_field( $vars['lead_cus_postcode'] );
		$vars['lead_cus_city']     = sanitize_text_field( $vars['lead_cus_city'] );
		$vars['lead_cus_address']  = sanitize_text_field( $vars['lead_cus_address'] );
		$vars['lead_cus_phone']    = sanitize_text_field( $vars['lead_cus_phone'] );
		$vars['lead_app_date']     = sanitize_text_field( $vars['lead_app_date'] );


	}
	// list all rooms, paginated. 
	// allow filters
	function view( $id = 0 ) {
		global $wpdb;
		$leadView = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $this->tablename . " WHERE id=%d", $id ) );

		/*echo "<pre>";
	    print_r($leadView);
	    echo "</pre>";*/

		return $leadView;
	}
	// list all leads, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;

		$rstart               = $filters['rstart'];
		$rend                 = $filters['rend'];
		$dir                  = $filters['dir'];
		$ob                   = $filters['ob'];
		$tablename_stage      = $wpdb->prefix . "fsms_stage";
		$tablename_users      = $wpdb->prefix . "users";
		$tablename_department = $wpdb->prefix . "fsms_department";
		$leads                = $wpdb->get_results( $wpdb->prepare( "SELECT L.*,UW.user_nicename as worker,SD.department_name as subDepartmentName,D.department_name as departmentName, S.stage_name,U.user_nicename  FROM " . $this->tablename . " as L LEFT JOIN " . $tablename_stage . " as S ON S.id=L.lead_stage LEFT JOIN " . $tablename_users . " as U ON U.ID=L.lead_owner LEFT JOIN " . $tablename_department . " as D ON D.id=L.lead_dep_id LEFT JOIN " . $tablename_department . " as SD ON SD.id=L.lead_sub_dep_id LEFT JOIN " . $tablename_users . " as UW ON UW.ID=L.lead_worker  ORDER BY L.$ob $dir LIMIT %d, %d", $rstart, $rend ) );

		//echo $wpdb->last_query;
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
		$id                = intval( $id );
		$payment_tablename = $wpdb->prefix . "fsms_leads";
		$lead              = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $payment_tablename . " WHERE id=%d", $id ) );

		return $lead;
	}


}