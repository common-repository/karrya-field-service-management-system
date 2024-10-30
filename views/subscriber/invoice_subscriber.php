<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
<div id="invoice_block">
    <fieldset>
        <legend>Invoice:</legend>
        <table width="100%">
            <caption>Invoice

            </caption>
            <tr>
                <th>Id</th>
                <th>To email</th>

                <th>Send date</th>
                <th>Status</th>
                <th>Option</th>
            </tr>
			<?php foreach ( $invoices as $invoice ): ?>
                <tr>
                    <td><a href="#"><?php echo esc_attr( $invoice->id ); ?></a></td>
                    <td>To:<?php echo esc_attr( $invoice->send_to_email ); ?><br>
                        Cc:<?php echo esc_attr( $invoice->send_cc_email ); ?>
                    </td>


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
                    <td><a target="_blank"
                           href="admin.php?page=fsms_work_subscriber&action=send_invoice_view&id=<?php echo esc_attr( $invoice->id ); ?>&lead_id=<?php echo esc_attr( $leadView->id ); ?>">view</a>
                        |
                        <a href="admin.php?page=fsms_work_subscriber&action=is_approve_view&id=<?php echo esc_attr( $invoice->id ); ?>&lead_id=<?php echo esc_attr( $leadView->id ); ?>&quoteOrInvoice=1">Approve</a>
                    </td>

                </tr>
			<?php endforeach; ?>
        </table>
    </fieldset>
    <hr>
</div>
</div>