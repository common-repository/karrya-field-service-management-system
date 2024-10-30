<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_send_invoice' );

?>
<div class="fsms_div">
    <div class="headerLinksDiv">
        <div class="headerLinkDiv">
            <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( $id ); ?>">View</a>
        </div>
        <div class="headerLinkDiv">
            <a href="admin.php?page=fsms_leads&action=edit&id=<?php echo esc_attr( $id ); ?>">Edit</a>
        </div>
        <div class="clearBoth"></div>
    </div>
    <hr>
    <div id="invoiceDiv">
        <div><?php echo wp_kses_post( wp_unslash( $email_header ) ); ?></div>
        <div class="invoiceBlock">
            <div class="compnayAddressblock" style="float: right; min-height: 200px;">
				<?php echo nl2br( wp_kses_post( get_option( 'fsms_company_address' ) ) ); ?>
            </div>
            <div class="clearBoth" style="clear: both;"></div>
            <div class="customerAddressblock" style="min-height: 200px;">
                <div><?php echo esc_attr( $leadView->lead_cus_fname ); ?></div>
                <div><?php echo esc_attr( $leadView->lead_cus_sname ); ?></div>
                <div><?php echo esc_attr( $leadView->lead_cus_address ); ?></div>
                <div><?php echo esc_attr( $leadView->lead_cus_city ); ?></div>
                <div><?php echo esc_attr( $leadView->lead_cus_postcode ); ?></div>
            </div>
        </div>
        <div class="invoiceBlock">
            <div><?php echo wp_kses_post( wp_unslash( $invoice_top_blcok ) ); ?></div>
        </div>
        <div class="invoiceBlock">
            <div class="invoiceDetails">
                <div class="invoiceTextId">Invoice: #<?php echo esc_attr( $leadView->id ); ?></div>
                <div class="invoiceTextDate">Invoice date: <?php echo date( "Y-m-d" ); ?></div>
            </div>
            <table width="100%" class="chargeSummaryBlcokClz">
                <tr>
                    <th>ID</th>
                    <th style="text-align: left;">Description</th>
                    <th style="text-align: right;" class="headingAmount">Amount</th>
                    <th style="text-align: right;" class="headingAmount">Vat</th>
                    <th style="text-align: right;" class="headingAmount">Total</th>
                </tr>
				<?php 
                $vatAccumulated = 0;
                $totAccumulated = 0;
                $netAccumulated = 0;
                $row_id         = 0;
                foreach ( $leadCharges as $leadCharge ):
					$fsms_vat = ( $leadCharge->vat ) / 100;
					$fsms_total_per = ( 100 + ( $leadCharge->vat ) ) / 100;

					
					if ( $leadCharge->entry_category == "charge" && $leadCharge->is_add_to_invoice == 1 ) {
						$qty = $leadCharge->qty;
						if ( $qty == 0 ) {
							$qty = 1;
						}


						$is_publish_to_invoice = $leadCharge->is_publish_to_invoice;

						if ( $is_publish_to_invoice == 1 or $is_publish_to_invoice == 2 or $is_publish_to_invoice == 4 ) {
							$row_id ++;
							$vatAccumulated += $leadCharge->payment_amount * $fsms_vat * $qty;
							$totAccumulated += $leadCharge->payment_amount * $fsms_total_per * $qty;
							$netAccumulated += $leadCharge->payment_amount * $qty;
						}


						?>

                        <tr>
                            <td><?php echo esc_attr( $row_id ); ?></td>
                            <td style="text-align: left;"><?php echo esc_attr( $leadCharge->payment_description ); ?></td>
                            <td style="text-align: right;" class="amount">
								<?php
								if ( $is_publish_to_invoice == 1 ) {
									echo change_product_price_display( esc_attr( $leadCharge->payment_amount * $qty ) );
								}
								?>

                            </td>
                            <td style="text-align: right;"  class="amount">
								<?php
								if ( $is_publish_to_invoice == 1 ) {
									echo change_product_price_display( esc_attr( $leadCharge->payment_amount * $fsms_vat * $qty ) );
								}
								?></td>
                            <td style="text-align: right;" class="amount">
								<?php
								if ( $is_publish_to_invoice == 1 ) {
									echo change_product_price_display( esc_attr( $leadCharge->payment_amount * $fsms_total_per * $qty ) );
								}
								?>

                            </td>
                        </tr>
						<?php
					}
					if ( $leadCharge->entry_category == "nonCharge" && $leadCharge->is_add_to_invoice == 1 ) {
						?>
                        <tr>
                            <td style="text-align: left;" colspan="6">
								<?php echo esc_attr( $leadCharge->payment_description ); ?>
                            </td>
                        </tr>
						<?php
					}
				endforeach;
				?>
                <tr>
                    <td style="text-align: right;" colspan="4" class="amountText">Net charge</td>
                    <td style="text-align: right;" class="amount totalVat"><?php echo change_product_price_display( esc_attr( @$netAccumulated ) ); ?></td>

                </tr>
                <tr>
                    <td style="text-align: right;" colspan="4" class="amountText">Vat</td>
                    <td style="text-align: right;" class="amount netCharge"><?php echo change_product_price_display( esc_attr( @$vatAccumulated ) ); ?></td>

                </tr>

                <tr>
                    <td style="text-align: right;" colspan="4" class="amountText">Total</td>
                    <td style="text-align: right;" class="amount grossCharge"><?php echo change_product_price_display( esc_attr( @$totAccumulated ) ); ?></td>

                </tr>
            </table>
        </div>


        <div class="invoiceBlock">
            <div><?php echo wp_kses_post( wp_unslash( $invoice_bottom_blcok ) ); ?></div>
        </div>
        <div><?php echo wp_kses_post( wp_unslash( $email_footer ) ); ?></div>

    </div>
    <hr>
    <div class="invoiceSendBlock">
        <table>
            <caption>Send invoice</caption>
            <tr>
                <td width="25%">To email</td>
                <td>
                    <input type="text" id="send_to_email" name="send_to_email"
                           value="<?php echo esc_attr( $leadView->lead_email ); ?>" placeholder="To email">
                </td>
            </tr>
            <tr>
                <td>Cc email</td>
                <td><input type="text" id="send_cc_email" name="send_cc_email" value="" placeholder="Cc email"></td>
            </tr>
            <tr>
                <td>Email subject</td>
                <td><input type="text" id="send_to_email_subject" name="send_to_email_subject"
                           value="<?php echo esc_attr( "Invoice #" . $leadView->id ); ?>" placeholder="Email subject">
                </td>
            </tr>
            <tr>
                <td>
                    <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( $leadView->id ); ?>"><input
                                type="button" name="" id="cancel_invoice" value="Back"></a>
                </td>
                <td>
                    <input id="ok" type="hidden" name="ok" value="1">
                    <input id="nonce" name="nonce" type="hidden" value="<?php echo $nonce; ?>">
                    <input type="button" name="" id="send_invoice" class="saveBtn" value="Send invoice">
                    <div class="loader" style="display:none;"></div>
                </td>
            </tr>
        </table>


    </div>
</div>



 
 