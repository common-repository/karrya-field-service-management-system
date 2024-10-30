<?php

class FSMSInvoice {


	private function add( $vars ) {
		global $wpdb;
		$vars              = FSMSInvoice::prepare_vars( $vars );
		$invoice_tablename = $wpdb->prefix . "fsms_invoice";
		$result            = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $invoice_tablename . " SET invoice_type='invoice',
			lead_id	=%d, send_date =NOW(), sender_id=%d, send_to_email=%s, send_cc_email=%s", $vars['lead_id'], get_current_user_id(), $vars['send_to_email'], $vars['send_cc_email'] ) );

		$lastid = $wpdb->insert_id;
		//echo $wpdb->last_query;	
		if ( $result === false ) {
			return false;
		}

		return $lastid;
	}

	// prepare and sanitize vars
	private function prepare_vars( $vars ) {
		$vars['lead_id']       = sanitize_text_field( $vars['lead_id'] );
		$vars['sender_id']     = sanitize_text_field( $vars['sender_id'] );
		$vars['send_to_email'] = sanitize_email( $vars['send_to_email'] );
		$vars['send_cc_email'] = sanitize_email( $vars['send_cc_email'] );

		return $vars;

	}

	public function send_invoice_to_customer_email() {
		global $wpdb;

		$lead_id               = sanitize_text_field( ( isset( $_POST['lead_id'] ) ) ? ( $_POST['lead_id'] ) : ( 0 ) );
		$send_to_email         = sanitize_email( $_POST['send_to_email'] );
		$send_cc_email         = sanitize_email( $_POST['send_cc_email'] );
		$send_to_email_subject = sanitize_text_field( $_POST['send_to_email_subject'] );
		$nonce                 = $_POST['nonce'];


		if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_send_invoice' ) ) {
			$invoiceDivContent = wp_kses_post( wp_unslash( $_POST['invoiceDivContent'] ) );
			$vars              = array(
				"send_to_email" => $send_to_email,
				"lead_id"       => $lead_id,
				"sender_id"     => $sender_id,
				"send_cc_email" => $send_cc_email,
				"nonce"         => $nonce
			);

			$lastid   = FSMSInvoice::add( $vars );
			$fileName = "viewInvoice_" . $lastid . ".html";

			FSMSInvoice::invoiceToHtml( $invoiceDivContent, $fileName );

			$from_email_address = sanitize_email( get_option( 'from_email_address' ) );
			if ( empty( $from_email_address ) ) {
				$from_email_address = sanitize_email( $email_options['admin_email'] );
			}

			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: ' . $from_email_address . "\r\n";
			$to      = $send_to_email;
			$subject = $send_to_email_subject;
			$message = $invoiceDivContent;
			//$result  = mail( $to, $subject, $message, $headers );
			$result = wp_mail( $to, $subject, $message, $headers );
			$status = $result ? 'OK' : "Error: " . $GLOBALS['phpmailer']->ErrorInfo;


			$response = "<div style='font-size:18px;color:green;'>Email successfully send Invoice #" . esc_attr( $lastid ) . "</div>";
		}

		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $response );
		die();
	}

	public function invoiceToHtml( $html, $fileName ) {
		$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'fsms_invoices';
		wp_mkdir_p( $uploads_dir );
		file_put_contents( $uploads_dir . '/' . $fileName, $html );
	}


	function find( $id = 0 ) {
		global $wpdb;

		$invoice_tablename = $wpdb->prefix . "fsms_invoice";
		$tablename_users   = $wpdb->prefix . "users";
		$invoices          = $wpdb->get_results( $wpdb->prepare( "SELECT INV.*, U.user_nicename FROM " . $invoice_tablename . " as INV  LEFT JOIN " . $tablename_users . " as U ON U.ID=INV.sender_id WHERE INV.invoice_type='invoice' AND INV.lead_id =%d", $id ) );

		//echo $wpdb->last_query;
		return $invoices;
	}

	function findByid( $id = 0 ) {
		global $wpdb;

		$invoice_tablename = $wpdb->prefix . "fsms_invoice";
		$tablename_users   = $wpdb->prefix . "users";
		$invoices          = $wpdb->get_results( $wpdb->prepare( "SELECT INV.*, U.user_nicename FROM " . $invoice_tablename . " as INV  LEFT JOIN " . $tablename_users . " as U ON U.ID=INV.sender_id WHERE INV.invoice_type='invoice' AND INV.id =%d", $id ) );

		//echo $wpdb->last_query;
		return $invoices;
	}
}