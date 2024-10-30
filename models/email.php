<?php

class FSMSEmail {

	public function __construct() {

	}

	public static function sendWelcomeEmail( $id ) {
		global $wpdb;

		$placeholderArr = FSMSEmail::getPlaceholderValues( $id );


		$leadObjArr  = $placeholderArr['lead_veiw'];
		$placeholder = $placeholderArr['placeholder'];

		$to                   = sanitize_email( $leadObjArr->lead_email );
		$from_email_address   = sanitize_email( get_option( 'from_email_address' ) );
		$thanks_email_subject = esc_attr( get_option( 'thanks_email_subject' ) );
		$thanks_email         = wp_kses_post( get_option( 'thanks_email' ) );
		if ( empty( $from_email_address ) ) {
			$from_email_address = sanitize_email( $email_options['admin_email'] );
		}
		if ( empty( $thanks_email_subject ) ) {
			$subject = esc_attr( "Thanks your lead -" . $id );
		} else {

			$subject = FSMSEmail::getEmailPlaceholder( $placeholder, $thanks_email_subject );
		}
		if ( empty( $thanks_email ) ) {
			$messageBody = esc_attr( "Thanks your lead -" . $id );
		} else {

			$messageBody = FSMSEmail::getEmailPlaceholder( $placeholder, $thanks_email );
		}
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: ' . $from_email_address . "\r\n";

		//$result = mail( $to, $subject, $messageBody, $headers );

		$result = wp_mail( $to, $subject, wp_kses_post($messageBody), $headers );

		$status = $result ? 'OK' : "Error: " . $GLOBALS['phpmailer']->ErrorInfo;

		return wp_kses_post( $messageBody );

	}

	public static function getEmailPlaceholder( $values, $txt ) {
		$placeholder = array(
			"((lead_id))",
			"((customer_name))",
			"((customer_email))",
			"((customer_phone))",
			"((department))",
			"((sub_department))",
			"((customer_address))",
			"((customer_city))",
			"((customer_postcode))",
			"((lead_worker))",
			"((lead_owner))",
			"((lead_stage))"
		);
		$newtxt      = str_replace( $placeholder, $values, $txt );

		return wp_kses_post(nl2br($newtxt) );
	}

	public static function getPlaceholderValues( $id ) {
		global $wpdb;

		$_leadArr       = FSMSLead::view( $id );
		$_lead          = $_leadArr[0];
		$lead_id        = $id;
		$customer_name  = $_lead->lead_cus_fname . " " . $_lead->lead_cus_sname;
		$customer_email = $_lead->lead_email;
		$customer_phone = $_lead->lead_cus_phone;
		$department     = $_lead->department_name;
		$sub_department = $_lead->sub_department_name;

		$customer_address  = $_lead->lead_cus_address;
		$customer_postcode = $_lead->lead_cus_postcode;
		$customer_city     = $_lead->lead_cus_city;
		$lead_app_date     = $_lead->lead_app_date;

		$lead_worker = $_lead->worker;
		$lead_owner  = $_lead->owner;
		$lead_stage  = $_lead->stage_name;


		$placeholderValues = array(
			'lead_veiw'   => $_lead,
			'placeholder' => array(
				$lead_id,
				$customer_name,
				$customer_email,
				$customer_phone,
				$department,
				$sub_department,
				$customer_address,
				$customer_city,
				$customer_postcode,
				$lead_worker,
				$lead_owner,
				$lead_stage
			)
		);


		return $placeholderValues;
	}

}