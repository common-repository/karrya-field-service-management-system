<?php

class FSMSCost {

	public $tablename;
	public $payment_tablename;

	public function __construct() {

	}

	function cost_insert_to_tb() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";
		$lead_id           = intval( $_POST['lead_id'] );
		$cost_amount       = sanitize_text_field( $_POST['cost_amount'] );
		$cost_description  = sanitize_text_field( $_POST['cost_description'] );
		$payment_type      = sanitize_text_field( $_POST['payment_type'] );
		$result            = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $payment_tablename . " SET
			payment_amount	=%f, payment_description	=%s, payment_lead_id=%d, payment_type =%s , status=%d", $cost_amount, $cost_description, $lead_id, 'cost', 1 ) );
		$response          = $wpdb->last_query;
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $response );
		die();
	}

	public function view_cost_block() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";
		$lead_id           = intval( $_POST['lead_id'] );
		$leadCostView      = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'cost', 1 ) );

		$response .= "<fieldset>
    <legend>Cost:</legend><table>    
        			<caption>Cost section
        				<span class='cursorPointer' id='addCostNew' onclick='showCostAddEditBlock();'>Add</span>
        			</caption>
        			 <tr>
			        <td colspan='4' style='display:none' id='addCostForm'>
			        	<div >

<div>
	<input type='text' id='cost_description' name='cost_description' placeholder='description'>
	<input type='number' id='cost_amount' name='cost_amount' class='number' placeholder='0.00'>
	<input type='button' id='add_cost' name='' class='saveBtn' value='Save' onclick='addCost()'>
	<input type='button' id='cancel_cost' name='' value='Cancel' onclick='cancelCost()'>
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
		foreach ( $leadCostView as $leadCost ):
			$response .= "
					</tr>
						<td>" . esc_attr( $leadCost->id ) . "</td>
						<td>" . esc_attr( $leadCost->payment_description ) . "</td>
						<td>" . esc_attr( $leadCost->payment_amount ) . "</td>
						<td><span title='Just delete and Add new'>Edit</span> | <span class='cursorPointer' onclick='deleteEntry(" . esc_attr( $leadCost->id ) . ",3);'>Delete</span></td>
					</tr>";
		endforeach;
		$response .= "
					 
					</table> </fieldset>";

		$responseCost = array( "cost" => $response );
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $responseCost );
		die();
	}

	public static function getCostSummary( $lead_id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$leadPayments = $wpdb->get_results( $wpdb->prepare( "SELECT sum(payment_amount) as netCharge FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'cost', 1 ) );

		//echo $wpdb->last_query;
		return $leadPayments;
	}

}