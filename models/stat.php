<?php

class FSMSStat {


	public static function getStatCostAndPayment() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$costAndPayment = $wpdb->get_results( $wpdb->prepare( "SELECT payment_type,if (payment_type='charge',sum(payment_amount*qty),sum(payment_amount))  as netCharge FROM " . $payment_tablename . " WHERE status=%d GROUP BY payment_type order by payment_type", 1 ) );

		//echo $wpdb->last_query;

		return $costAndPayment;
	}

	public static function getStatLeads() {
		global $wpdb;
		$leads_tablename = $wpdb->prefix . "fsms_leads";


		$totalLeads = $wpdb->get_results( ( "SELECT count(*) as totalLeads FROM " . $leads_tablename ) );

		//echo $wpdb->last_query;
		return $totalLeads;
	}

	public static function getSearchStatLeads( $from, $to ) {
		global $wpdb;
		$leads_tablename = $wpdb->prefix . "fsms_leads";


		$totalLeads = $wpdb->get_results( $wpdb->prepare( "SELECT count(*) as totalLeads FROM " . $leads_tablename . " WHERE lead_added_date between %s AND %s ", $from, $to ) );

		//echo $wpdb->last_query;
		return $totalLeads;
	}

	public static function getStatCostAndPaymentSearch( $from, $to ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";
		$leads_tablename   = $wpdb->prefix . "fsms_leads";

		$costAndPayment = $wpdb->get_results( $wpdb->prepare( "SELECT P.payment_type,sum(P.payment_amount*P.qty) as netCharge FROM " . $payment_tablename . " as P LEFT JOIN " . $leads_tablename . " as L ON L.id=P.payment_lead_id WHERE L.lead_added_date between %s AND %s AND P.status=%d GROUP BY P.payment_type order by P.payment_type", $from, $to, 1 ) );

		//echo $wpdb->last_query;

		return $costAndPayment;
	}


}