<?php

class FSMSShortcodes {
	public static $shortcode_ids;

	public function __construct() {
		add_action( 'wp_ajax_nopriv_list_subdepartment', array( "FSMSDepartment", 'list_subdepartment' ) );
		add_action( 'wp_ajax_nopriv_lead_book', array( $this, 'lead_book' ) );
		add_action( 'wp_ajax_lead_book', array( $this, 'lead_book' ) );

	}

	function lead_book() {


		if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_frontend_lead_add' ) ) {

			$files = $_FILES['myfilefield'];

			//Loop through all the uploaded files
			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file    = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ]
					);
					$_FILES  = array( 'uploaded_file' => $file );
					$file_id = media_handle_upload( 'uploaded_file', 0 );

					if ( ! is_wp_error( $file_id ) ) {
						$imgSrc .= "<br><div><a target='_blank' href='" . wp_get_attachment_url( $file_id ) . "'>" . wp_get_attachment_image( $file_id, 'thumbnail' ) . "</a></div>";
					}
				}
			}


			$_customer  = new FSMSCustomer();
			$customerId = $_customer->addFromLead( $_POST );
			$_site      = new FSMSSite();
			$siteId     = $_site->addFromLead( $_POST, $customerId );

			$otherDetails                    = array(
				"site_id" => intval( $siteId ),
				"cust_id" => intval( $customerId )
			);
			$_POST['customer_upload_images'] = wp_kses_post( $_POST['customer_upload_images'] ) . "<br>" . $imgSrc;
			$_lead                           = new FSMSLead();
			$leadInsertId                    = $_lead->add( $_POST, $otherDetails );


			$welcomeMessage = FSMSEmail::sendWelcomeEmail( $leadInsertId );

		}
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $welcomeMessage );
		die();
	}

	// displays and processes the booking form
	static function leadBookingForm( $shortcode_id = null ) {


		global $wpdb, $post;
		if ( empty( $shortcode_id ) ) {
			$shortcode_id = self:: get_id();
		}
		ob_start();

		// display the booking form
		wphostel_enqueue_datepicker();
		include( FSMS_PATH . "/views/lead-form.html.php" );

		$content = ob_get_clean();

		return $content;
	} // end booking

	static function get_id() {
		if ( empty( self:: $shortcode_ids ) ) {
			self:: $shortcode_ids = array();
		}
		$current_id = count( self:: $shortcode_ids );
		$current_id ++;
		self:: $shortcode_ids[] = $current_id;

		return $current_id;
	}

	public static function departmentLeadBookingForm( $att ) {
		global $wpdb, $post;
		$department_id     = 0;
		$sub_department_id = 0;
		if ( ! empty( $att['department_id'] ) ) {
			$department_id = esc_attr( $att['department_id'] );
		}
		if ( ! empty( $att['sub_department_id'] ) ) {
			$sub_department_id = esc_attr( $att['sub_department_id'] );
		}
		if ( empty( $shortcode_id ) ) {
			$shortcode_id = self:: get_id();
		}
		ob_start();
		$departments = FSMSDepartment::find();
		include( FSMS_PATH . "/views/lead-form.html.php" );

		echo "<script>
				jQuery( document ).ready(function( $ ) {
				 departmentsList(" . $department_id . "," . $sub_department_id . ");
				   });
			</script>";

		$content = ob_get_clean();

		return $content;


	}


}

new FSMSShortcodes();