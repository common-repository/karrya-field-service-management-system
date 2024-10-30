<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
    <div id="charge_block"></div>
    <div id="cost_block"></div>
    <div id="payment_block"></div>
    <div id="summary_block"></div>

    <div id="invoice_block">
        <fieldset>
            <legend>Invoice:</legend>
            <table width="100%">
                <caption>Invoice
                    <span class='cursorPointer'><a
                                href="admin.php?page=fsms_leads&action=send_invoice&id=<?php echo esc_attr( $leadView->id ); ?>">Send invoice</a></span>
                </caption>
                <tr>
                    <th>Id</th>
                    <th>To email</th>
                    <th>Send by</th>
                    <th>Send date</th>
                    <th>Status</th>
                    <th>Option</th>
                </tr>
				<?php foreach ( $invoices as $invoice ): ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=fsms_leads&action=send_invoice_view&id=<?php echo esc_attr( $invoice->id ); ?>"><?php echo esc_attr( $invoice->id ); ?></a>
                        </td>
                        <td>To:<?php echo esc_attr( $invoice->send_to_email ); ?><br>
                            Cc:<?php echo esc_attr( $invoice->send_cc_email ); ?>
                        </td>

                        <td><?php echo esc_attr( $invoice->user_nicename ); ?></td>
                        <td><?php echo esc_attr( $invoice->send_date ); ?></td>
                        <td><?php

							$approveOrNot = "<span class='pending'>Pending</span>";
							if ( $invoice->is_approve == 1 ) {
								$approveOrNot = "<span class='approveC'>Approved</span>";
							} else if ( $invoice->is_approve == 0 ) {
								$approveOrNot = "<span class='reject'>Rejected</span>";
							}
							echo wp_kses_post( $approveOrNot );
							?></td>
                        <td>
                            <a href="admin.php?page=fsms_leads&action=send_invoice_view&id=<?php echo esc_attr( $invoice->id ); ?>">view</a>
                            |
                            <a href="admin.php?page=fsms_leads&action=is_approve_view&id=<?php echo esc_attr( $invoice->id ); ?>&lead_id=<?php echo esc_attr( $leadView->id ); ?>&quoteOrInvoice=1">Approve</a>
                        </td>

                    </tr>
				<?php endforeach; ?>
            </table>
        </fieldset>
        <hr>
    </div>
    <div id="quote_block">
        <fieldset>
            <legend>Quote:</legend>
            <table width="100%">
                <caption>Quote
                    <span class='cursorPointer'><a
                                href="admin.php?page=fsms_leads&action=send_quote&id=<?php echo esc_attr( $leadView->id ); ?>">Send quote</a></span>
                </caption>
                <tr>
                    <th>Id</th>
                    <th>To email</th>
                    <th>Send by</th>
                    <th>Send date</th>
                    <th>Status</th>
                    <th>Option</th>
                </tr>
				<?php foreach ( $quotes as $quote ): ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=fsms_leads&action=is_approve_view&id=<?php echo esc_attr( $quote->id ); ?>"><?php echo esc_attr( $quote->id ); ?></a>
                        </td>
                        <td>To:<?php echo esc_attr( $quote->send_to_email ); ?><br>
                            Cc:<?php echo esc_attr( $quote->send_cc_email ); ?>
                        </td>

                        <td><?php echo esc_attr( $quote->user_nicename ); ?></td>
                        <td><?php echo esc_attr( $quote->send_date ); ?></td>
                        <td><?php

							$approveOrNot = "<span class='pending'>Pending</span>";
							if ( $quote->is_approve == 1 ) {
								$approveOrNot = "<span class='approveC'>Approved</span>";
							} else if ( $quote->is_approve == 0 ) {
								$approveOrNot = "<span class='reject'>Rejected</span>";
							}
							echo wp_kses_post( $approveOrNot );
							?></td>
                        <td>
                            <a href="admin.php?page=fsms_leads&action=send_quote_view&id=<?php echo esc_attr( $quote->id ); ?>">view</a>
                            |
                            <a href="admin.php?page=fsms_leads&action=is_approve_view&id=<?php echo esc_attr( $quote->id ); ?>&lead_id=<?php echo esc_attr( $leadView->id ); ?>&quoteOrInvoice=2">Approve</a>
                        </td>

                    </tr>
				<?php endforeach; ?>
            </table>
        </fieldset>
        <hr>
    </div>
    <div id="site_block">
        <fieldset>
            <legend>Sites:</legend>
            <table width="100%">
                <caption>Sites

                </caption>
                <tr>
                    <th>Id</th>
                    <th>Site name</th>
                    <th>Option</th>
                </tr>
				<?php foreach ( $sites as $site ): ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=fsms_sites&action=view&id=<?php echo esc_attr( $site->id ); ?>"><?php echo esc_attr( $site->id ); ?></a>
                        </td>
                        <td><?php echo esc_attr( $site->site_name ); ?></td>
                        <td>
                            <a href="admin.php?page=fsms_sites&action=view&id=<?php echo esc_attr( $site->id ); ?>">view</a>
                            | <a
                                    href="admin.php?page=fsms_sites&action=add_lead&id=<?php echo esc_attr( $site->id ); ?>">Add
                                new
                                lead</a></td>

                    </tr>
				<?php endforeach; ?>
            </table>
        </fieldset>
        <hr>
    </div>

    <div id="other_lead_block">
        <fieldset>
            <legend>Other leads of this customer:</legend>
            <table width="100%">
                <caption>Leads summary

                </caption>
                <tr>
                    <th>Id</th>
                    <th>Net</th>
                    <th>Vat</th>
                    <th>Payment</th>
                    <th>Cost</th>
                    <th>Profit</th>
                    <th>Option</th>
                </tr>
				<?php foreach ( $otherLeads as $otherLead ):
					$leadChargesSummaryArr = FSMSCharge::getChargesSummary( $otherLead->id );
					$leadChargesSummary = $leadChargesSummaryArr[0];

					$leadCostsSummaryArr = FSMSCost::getCostSummary( $otherLead->id );

					$leadCostsSummary = $leadCostsSummaryArr[0];

					$leadPaymentsSummaryArr = FSMSPayment::getPaymentSummary( $otherLead->id );
					$leadPaymentSummary     = $leadPaymentsSummaryArr[0];
					?>
                    <tr>
                        <td><a href=""><?php echo esc_attr( $otherLead->id ); ?></a></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadChargesSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadChargesSummary->totalVat ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadPaymentSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadCostsSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadPaymentSummary->netCharge - $leadCostsSummary->netCharge ) ); ?></td>
                        <td><a href="">View</a></td>

                    </tr>
				<?php endforeach; ?>
            </table>
        </fieldset>
        <hr>
    </div>
	<?php
	include( FSMS_PATH . "/views/log_view.php" );
	?>
</div>

 