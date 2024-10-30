<?php

class FSMSPayment {

	public $tablename;
	public $payment_tablename;

	public function __construct() {

	}

	public static function getChargesById( $id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$leadCharges = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE id=%d", $id ) );

		//echo $wpdb->last_query;
		return $leadCharges;
	}

	function payment_insert_to_tb() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$lead_id             = sanitize_text_field( ( isset( $_POST['lead_id'] ) ) ? ( $_POST['lead_id'] ) : ( 0 ) );
		$payment_amount      = sanitize_text_field( $_POST['payment_amount'] );
		$payment_description = sanitize_text_field( $_POST['payment_description'] );
		$payment_type        = sanitize_text_field( $_POST['payment_type'] );
		$result              = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $payment_tablename . " SET
			payment_amount	=%f, payment_description	=%s, payment_lead_id=%d, payment_type =%s , status=%d", $payment_amount, $payment_description, $lead_id, 'payment', 1 ) );
		$response            = $wpdb->last_query;
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $response );
		die();
	}

	public function payment_delete_from_tb() {
		global $wpdb;

		$id                = sanitize_text_field( ( isset( $_POST['id'] ) ) ? ( $_POST['id'] ) : ( 0 ) );
		$payment_tablename = $wpdb->prefix . "fsms_payment";
		$result            = $wpdb->query( $wpdb->prepare( "UPDATE  " . $payment_tablename . " SET status=%d WHERE id=%d", 0, $id ) );
		$response          = $wpdb->last_query;

		$paymentDataArr = FSMSPayment::getChargesById( $id );
		/*echo "<pre>";
		print_r($paymentDataArr);
		echo "</pre>";*/
		$lead_id     = $paymentDataArr[0]->payment_lead_id;
		$changedQty  = $paymentDataArr[0]->qty;
		$sku         = $paymentDataArr[0]->sku;
		$charge_type = $paymentDataArr[0]->charge_type;
		FSMSStock::deductStockAvailability( $sku, $changedQty, 0, 1, $lead_id, $charge_type );
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $response );
		die();
	}

	public function view_payment_block() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$lead_id         = sanitize_text_field( ( isset( $_POST['lead_id'] ) ) ? ( $_POST['lead_id'] ) : ( 0 ) );
		$leadPaymentView = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'payment', 1 ) );

		$response .= " <fieldset>
    <legend>Payment:</legend><table>    
        			<caption>Payment section
        				<span class='cursorPointer' id='addPaymentNew' onclick='showPaymentAddEditBlock();'>Add</span>
        			</caption>
        			 <tr>
			        <td colspan='4' style='display:none' id='addPaymentForm'>
			        	<div >

<div>
	<input type='text' id='payment_description' name='payment_description' placeholder='description'>
	<input type='number' id='payment_amount' name='payment_amount' class='number' placeholder='0.00'>
	<input type='button' class='saveBtn' id='add_payment' name='' value='Save' onclick='addPayment()'>
	<input type='button' id='cancel_payment' name='' value='Cancel' onclick='cancelPayment()'>
</div>
</div>
			        </td>
			        </tr>
			        <tr>
			           <th width='10%'>Id</th>
						<th width='50%'>Description</th>
						<th>Amount</th>
						<th>Option</th>
			        </tr>
			       

					 ";
		foreach ( $leadPaymentView as $leadPayment ):
			$response .= "
					</tr>
						<td>" . esc_attr( $leadPayment->id ) . "</td>
						<td>" . esc_attr( $leadPayment->payment_description ) . "</td>
						<td>" . esc_attr( $leadPayment->payment_amount ) . "</td>
						<td><span title='Just delete and Add new'>Edit</span> | <span class='cursorPointer' onclick='deleteEntry(" . esc_attr( $leadPayment->id ) . ",2);'>Delete</span></td>
					</tr>";
		endforeach;
		$response .= "
					 
					</table>  </fieldset>";

		$responsePayment = array( "payment" => $response );
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $responsePayment );
		die();
	}

	public static function getPaymentSummary( $lead_id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";


		$leadPayments = $wpdb->get_results( $wpdb->prepare( "SELECT sum(payment_amount) as netCharge FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'payment', 1 ) );

		//echo $wpdb->last_query;
		return $leadPayments;
	}

}