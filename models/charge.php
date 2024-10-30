<?php

class FSMSCharge {

	public $tablename;
	public $payment_tablename;

	public function __construct() {

	}

	function edit( $vars, $id ) {
		global $wpdb;
		FSMSChargeType::findEntryCategory();
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$charge_amount      = sanitize_text_field( $_POST['payment_amount'] );
		$charge_description = sanitize_text_field( $_POST['payment_description'] );
		//$payment_type       = sanitize_text_field( $_POST['payment_type'] );

		$charge_type   = sanitize_text_field( $_POST['charge_type'] );
		$display_order = sanitize_text_field( $_POST['display_order'] );

		$is_publish_to_quote = sanitize_text_field( $_POST['is_publish_to_quote'] );

		$is_publish_to_invoice = sanitize_text_field( $_POST['is_publish_to_invoice'] );

		$supplier_id = sanitize_text_field( $_POST['supplier_id'] );

		$is_add_to_quote = sanitize_text_field( @ $_POST['is_add_to_quote'] );
		if ( $is_add_to_quote == "on" ) {
			$is_add_to_quote = 1;
		} else {
			$is_add_to_quote = 0;
		}
		$is_add_to_invoice = sanitize_text_field( @ $_POST['is_add_to_invoice'] );
		if ( $is_add_to_invoice == "on" ) {
			$is_add_to_invoice = 1;
		} else {
			$is_add_to_invoice = 0;
		}
		$is_deduct_from_stock = sanitize_text_field( @$_POST['is_deduct_from_stock'] );
		if ( $is_deduct_from_stock == "on" ) {
			$is_deduct_from_stock = 1;
		} else {
			$is_deduct_from_stock = 0;
		}
		$vat    = sanitize_text_field( $_POST['vat'] );
		$sku    = sanitize_text_field( $_POST['sku'] );
		$qty    = (float) sanitize_text_field( $_POST['qty'] );
		$status = sanitize_text_field( $_POST['status'] );

		$old_sku = sanitize_text_field( $_POST['old_sku'] );
		$old_qty = (float) sanitize_text_field( $_POST['old_qty'] );
		$lead_id = (int) sanitize_text_field( $_POST['lead_id'] );
		if ( $old_sku == $sku ) {

			$changedQty = (float) ( $qty - $old_qty );

			if ( $changedQty > 0 ) {

				FSMSStock::updateStockAvailability( $sku, $changedQty, 0, 1, $lead_id, $charge_type );
			} else if ( $changedQty < 0 ) {
				$changedQty = (float) ( $old_qty - $qty );
				FSMSStock::deductStockAvailability( $sku, $changedQty, 0, 1, $lead_id, $charge_type );
			}

		}

		if ( $status == "on" ) {
			$status = 1;
		} else {
			$status = 0;
		}

		$entryCategoryObj = FSMSChargeType::findEntryCategory( $charge_type );

		$entryCategory = $entryCategoryObj[0]->entry_category;
		if ( empty( $entryCategory ) ) {
			$entryCategory = "charge";
		}

		$result = $wpdb->query( $wpdb->prepare( "UPDATE " . $payment_tablename . " SET
			payment_amount	=%f, payment_description	=%s,  payment_type =%s, status=%d,charge_type=%s,display_order=%d,is_publish_to_quote=%d,is_publish_to_invoice=%d,is_add_to_quote=%d,is_add_to_invoice=%d,is_deduct_from_stock=%d,vat=%d,sku=%s,qty=%f,entry_category=%s,supplier_id=%d WHERE id=%d", $charge_amount, $charge_description, 'charge', $status, $charge_type, $display_order, $is_publish_to_quote, $is_publish_to_invoice, $is_add_to_quote, $is_add_to_invoice, $is_deduct_from_stock, $vat, $sku, $qty, $entryCategory, $supplier_id, $id ) );
		//echo $wpdb->last_query;
		if ( $result === false ) {
			return false;
		}

		return true;
	}

	function charge_insert_to_tb() {
		global $wpdb;
		FSMSChargeType::findEntryCategory();
		$payment_tablename  = $wpdb->prefix . "fsms_payment";
		$lead_id            = intval( $_POST['lead_id'] );
		$charge_amount      = sanitize_text_field( $_POST['charge_amount'] );
		$charge_description = sanitize_text_field( $_POST['charge_description'] );
		$payment_type       = sanitize_text_field( $_POST['payment_type'] );

		$charge_type           = sanitize_text_field( $_POST['charge_type'] );
		$display_order         = sanitize_text_field( $_POST['display_order'] );
		$is_publish_to_quote   = sanitize_text_field( $_POST['is_publish_to_quote'] );
		$is_publish_to_invoice = sanitize_text_field( $_POST['is_publish_to_invoice'] );
		$is_add_to_quote       = sanitize_text_field( $_POST['is_add_to_quote'] );
		$is_add_to_invoice     = sanitize_text_field( $_POST['is_add_to_invoice'] );
		$is_deduct_from_stock  = sanitize_text_field( $_POST['is_deduct_from_stock'] );
		$vat                   = sanitize_text_field( $_POST['vat'] );
		$sku                   = sanitize_text_field( $_POST['sku'] );
		$qty                   = sanitize_text_field( $_POST['qty'] );
		$supplier_id = sanitize_text_field( $_POST['supplier_id'] );

		$entryCategoryObj = FSMSChargeType::findEntryCategory( $charge_type );

		$entryCategory = $entryCategoryObj[0]->entry_category;
		if ( empty( $entryCategory ) ) {
			$entryCategory = "charge";
		}

		$result   = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $payment_tablename . " SET supplier_id=%d,
			payment_amount	=%f, payment_description	=%s, payment_lead_id=%d, payment_type =%s, status=%d,charge_type=%s,display_order=%d,is_publish_to_quote=%d,is_publish_to_invoice=%d,is_add_to_quote=%d,is_add_to_invoice=%d,is_deduct_from_stock=%d,vat=%d,sku=%s,qty=%f,entry_category=%s",$supplier_id, $charge_amount, $charge_description, $lead_id, 'charge', 1, $charge_type, $display_order, $is_publish_to_quote, $is_publish_to_invoice, $is_add_to_quote, $is_add_to_invoice, $is_deduct_from_stock, $vat, $sku, $qty, $entryCategory ) );
		$response = $wpdb->last_query;
		FSMSStock::updateStockAvailability( $sku, $qty, 0, 1, $lead_id, $charge_type );
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $response );
		die();
	}

	public static function getCharges( $lead_id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$leadCharges = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'charge', 1 ) );

		//echo $wpdb->last_query;
		return $leadCharges;
	}

	public static function getChargesById( $id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";

		$leadCharges = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE id=%d", $id ) );

		//echo $wpdb->last_query;
		return $leadCharges;
	}

	public static function getChargesSummary( $lead_id ) {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";


		$leadCharges = $wpdb->get_results( $wpdb->prepare( "SELECT sum(payment_amount*qty) as netCharge, sum(payment_amount*qty*vat)/100 as totalVat, sum(payment_amount*qty*vat)/100 as grossCharge  FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d", $lead_id, 'charge', 1 ) );

		//echo $wpdb->last_query;
		return $leadCharges;
	}

	public function view_charge_block() {
		global $wpdb;
		$payment_tablename = $wpdb->prefix . "fsms_payment";
		$lead_id           = intval( $_POST['lead_id'] );
		$leadChargeView    = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM " . $payment_tablename . " WHERE payment_lead_id=%d AND payment_type=%s AND status=%d ORDER BY display_order ASC", $lead_id, 'charge', 1 ) );

		if ( esc_attr( get_option( 'fsms_vat' ) ) > 0 ) {
			$vat = ( esc_attr( get_option( 'fsms_vat' ) ) );

		} else {
			$vat = 20;

		}

		$response .= "<fieldset>
    <legend>Charge:</legend><table>    
        			<caption>Charge section
        				<span class='cursorPointer' id='addChargeNew' onclick='showChargeAddEditBlock();'>Add</span>
        			</caption>
        			 <tr>
			        <td colspan='9' style='display:none' id='addChargeForm'>
			        	";

		$response     .= "
 <div><div id='charge_add_tbl'>
 <table>
 <tr>
 	<th>Charge type</th>
 	<th>Order</th>
 	<th>? Quote</th>
 	<th>? Invoice</th>
 	<th>+ quote</th>
 	<th>+ invoce</th>
 	<th style='display:none;'>- stock</th>
 	<th>SKU</th>
 	<th>Description</th>
 	<th>Qty</th>
 	<th>Amount</th> 	 
 	<th>Vat</th>
 	<th>Total</th>
 	<th></th>
 </tr>
 <tr>
 	<td>
 		<select name='charge_type' id='charge_type' style='width:50px;'>
 		";
		$_chargeTypes = new FSMSChargeType();
		$chargeTypes  = $_chargeTypes->findActive();
		foreach ( $chargeTypes as $chargeType ):
			$response .= "<option value='" . $chargeType->id . "'>" . $chargeType->charge_type . "</option>";
		endforeach;
		$response .= "</select>
 	</td>
 	<td>
 		<input type='number' name='display_order' id='display_order' style='width:50px;'>
 	</td>
 	<td>
 		 
 		<select name='is_publish_to_quote' style='width:50px;' id='is_publish_to_quote'>
 			<option value='1'>show line + total</option>
 			<option value='2'>hide line + total</option>
 			<option value='3'>hide line - total</option>
 			<option value='4'>hide amount + total</option>
 			<option value='5'>hide amount - total</option>
 			<option value='6'>show line - total</option>
 		</select>
 	</td>
 	<td>
 		 
 		<select name='is_publish_to_invoice' style='width:50px;' id='is_publish_to_invoice'>
 			<option value='1'>show line + total</option>
 			<option value='2'>hide line + total</option>
 			<option value='3'>hide line - total</option>
 			<option value='4'>hide amount + total</option>
 			<option value='5'>hide amount - total</option>
 			<option value='6'>show line - total</option>
 		</select>
 	</td>
 	<td><input type='checkbox' name='is_add_to_quote' id='is_add_to_quote' checked></td>
 	<td><input type='checkbox' name='is_add_to_invoice' id ='is_add_to_invoice' checked></td>
 	<td  style='display:none;'><input type='checkbox' name='is_deduct_from_stock' id='is_deduct_from_stock'></td>
 	<td><input type='text' onkeyup='searchSku();' autocomplete='off'  name='sku' id='sku' 	style='width:70px;' placeholder='sku'>
 		<div id='suggesstion-box'></div>
 	</td>
 	<td><input type='text' autocomplete='off' id='charge_description' name='charge_description' placeholder='description'>
 		<div id='suggesstion-box-d'></div>
 	</td>
 	<td><input type='number' id='qty' name='qty' placeholder='1' value='1' style='width:50px;'></td>
 	<td><input type='number' id='charge_amount' name='charge_amount' class='number' placeholder='0.00'></td>
 	<td><input type='number' id='vat' name='vat' class='number' placeholder='' value='" . $vat . "'>
 	<input type='hidden' id='supplier_id' name='supplier_id' class='number' >
 	</td>
 	<td></td>
 	<td>
 	<input type='button' class='saveBtn' id='add_charge' name='' value='Save' onclick='addCharge()' style='margin-bottom:5px;width:70px;'>
	<input type='button' id='cancel_charge' name='' value='Cancel' onclick='cancelCharge()' style='width:70px;'></td>
 </tr>
 </table>
	
	
	
</div>
</div>";
		$response .= "</td>
			        </tr>
			        <tr>
			           <th width='10%'>Id</th>
			           <th>Display</th>
			           <th>Type</th>
					    <th width='50%'>Description</th>
					    <th>Qty</th>
						<th>Amount</th>
						<th>Vat</th>
						<th>Total</th>
						<th>Option</th>
			        </tr>
			       

					 ";

		$vatAccumulated = 0;
		$totAccumulated = 0;
		$netAccumulated = 0;
		$row_id         = 1;
		foreach ( $leadChargeView as $leadCharge ):

			$fsms_vat       = ( $leadCharge->vat ) / 100;
			$fsms_total_per = ( 100 + ( $leadCharge->vat ) ) / 100;


			if ( $leadCharge->entry_category == "charge" ) {
				$qty = $leadCharge->qty;
				if ( $qty == 0 ) {
					$qty = 1;
				}
				if ( $leadCharge->is_publish_to_invoice == 1 || $leadCharge->is_publish_to_invoice == 2 || $leadCharge->is_publish_to_invoice == 4 ) {
					$vatAccumulated += $leadCharge->payment_amount * $fsms_vat * $qty;
					$totAccumulated += $leadCharge->payment_amount * $fsms_total_per * $qty;
					$netAccumulated += $leadCharge->payment_amount * $qty;
				}
				$hideInInvoice = "<span style='color:red;' title='Not display in invoice'>In</span>";
				if ( $leadCharge->is_publish_to_invoice == 1 ) {
					$hideInInvoice = "<span style='color:green;' title='show line + total'>In</span>";
				}
				if ( $leadCharge->is_publish_to_invoice == 2 ) {
					$hideInInvoice = "<span style='color:green;' title='hide line + total'>In</span>";
				}
				if ( $leadCharge->is_publish_to_invoice == 3 ) {
					$hideInInvoice = "<span style='color:green;' title='hide line - total'>In</span>";
				}
				if ( $leadCharge->is_publish_to_invoice == 4 ) {
					$hideInInvoice = "<span style='color:green;' title='hide amount + total'>In</span>";
				}
				if ( $leadCharge->is_publish_to_invoice == 5 ) {
					$hideInInvoice = "<span style='color:green;' title='hide amount - total'>In</span>";
				}
				if ( $leadCharge->is_publish_to_invoice == 6 ) {
					$hideInInvoice = "<span style='color:green;' title='show line - total'>In</span>";
				}
				$hideInQuote = "<span style='color:red;' title='Not display in quote'>Qu</span>";
				if ( $leadCharge->is_publish_to_quote == 1 ) {
					$hideInQuote = "<span style='color:green;' title='show line + total'>Qu</span>";
				}
				if ( $leadCharge->is_publish_to_quote == 2 ) {
					$hideInQuote = "<span style='color:green;' title='hide line + total'>Qu</span>";
				}
				if ( $leadCharge->is_publish_to_quote == 3 ) {
					$hideInQuote = "<span style='color:green;' title='hide line - total'>Qu</span>";
				}
				if ( $leadCharge->is_publish_to_quote == 4 ) {
					$hideInQuote = "<span style='color:green;' title='hide amount + total'>Qu</span>";
				}
				if ( $leadCharge->is_publish_to_quote == 5 ) {
					$hideInQuote = "<span style='color:green;' title='hide amount - total'>Qu</span>";
				}
				if ( $leadCharge->is_publish_to_quote == 6 ) {
					$hideInQuote = "<span style='color:green;' title='show line - total'>Qu</span>";
				}
				$hideAddQuote = "<span style='color:red;' title='Not added to quote'>+Q</span>";
				if ( $leadCharge->is_add_to_quote == 1 ) {
					$hideAddQuote = "<span style='color:green;' title='Added to quote'>+Q</span>";
				}
				$hideAddInvoice = "<span style='color:red;' title='Not added to inovice'>+I</span>";
				if ( $leadCharge->is_add_to_invoice == 1 ) {
					$hideAddInvoice = "<span style='color:green;' title='Added to invoice'>+I</span>";
				}
				$hideAddStock = "<span style='color:red;' title='Not deduct from stock'>-S</span>";
				if ( $leadCharge->is_deduct_from_stock == 1 ) {
					$hideAddStock = "<span style='color:green;' title='Deduct from stock'>-S</span>";
				}
				$entryCategoryObj = FSMSChargeType::findEntryCategory( $leadCharge->charge_type );

				$charge_type = $entryCategoryObj[0]->charge_type;
				$response    .= "
					<tr class='entry_" . esc_attr( $leadCharge->id ) . "'>
					    
						<td>" . esc_attr( $row_id ) . "</td>
						<td>" . wp_kses_post( $hideInInvoice ) . " " . wp_kses_post( $hideInQuote ) . " " . wp_kses_post( $hideAddQuote ) . " " . wp_kses_post( $hideAddInvoice ) . "</td>
						<td>" . esc_attr( $charge_type ) . "</td>
						<td>" . esc_attr( $leadCharge->payment_description ) . "</td>
						<td>" . esc_attr( $leadCharge->qty ) . "</td>
						<td>" . esc_attr( $leadCharge->payment_amount ) . "</td>
						<td>" . esc_attr( change_product_price_display( $leadCharge->payment_amount * $fsms_vat * $qty ) ) . "</td>
						<td>" . esc_attr( change_product_price_display( $leadCharge->payment_amount * $fsms_total_per * $qty ) ) . "</td>
						<td><a href='admin.php?page=fsms_leads&action=charge_edit&id=" . esc_attr( $leadCharge->id ) . "&lead_id=" . esc_attr( $lead_id ) . "'><span title=''>Edit</span></a> | <span class='cursorPointer' onclick='deleteEntry(" . esc_attr( $leadCharge->id ) . ",1);'>Delete</span></td>
					</tr>";
				$row_id ++;
			}
			if ( $leadCharge->entry_category == "nonCharge" ) {
				$response .= "
					<tr class='entry_" . $leadCharge->id . "'>
						<td colspan='5'>
						" . $leadCharge->payment_description . "
						</td>
						<td><a href='admin.php?page=fsms_leads&action=charge_edit&id=" . esc_attr( $leadCharge->id ) . "&lead_id=" . esc_attr( $lead_id ) . "'><span title=''>Edit</span></a> | <span class='cursorPointer' onclick='deleteEntry(" . esc_attr( $leadCharge->id ) . ",1);'>Delete</span></td>
					</tr>
					";
			}

		endforeach;
		$response .= "
					 <tr>
					 	<td colspan='7' class='invoiceDetails'>Net charge</td>
					 	<td>" . esc_attr( change_product_price_display( $netAccumulated ) ) . "</td>
					 	<td></td>
					 <tr>
					 <tr>
					 	<td colspan='7' class='invoiceDetails'>Vat</td>
					 	<td>" . esc_attr( change_product_price_display( $vatAccumulated ) ) . "</td>
					 	<td></td>
					 <tr>
					 <tr>
					 	<td colspan='7' class='invoiceDetails'>Total</td>
					 	<td>" . esc_attr( change_product_price_display( $totAccumulated ) ) . "</td>
					 	<td></td>
					 <tr>
					</table> </fieldset>";

		$responseCharge = array( "charge" => $response );
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $responseCharge );
		die();
	}

}