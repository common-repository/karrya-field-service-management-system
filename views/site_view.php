<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
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
                        <td>
                            <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( $otherLead->id ); ?>"><?php echo esc_attr( $otherLead->id ); ?></a>
                        </td>
                        <td><?php echo change_product_price_display( esc_attr( $leadChargesSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadChargesSummary->totalVat ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadPaymentSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadCostsSummary->netCharge ) ); ?></td>
                        <td><?php echo change_product_price_display( esc_attr( $leadPaymentSummary->netCharge - $leadCostsSummary->netCharge ) ); ?></td>
                        <td>
                            <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( $otherLead->id ); ?>">View</a>
                        </td>

                    </tr>
				<?php endforeach; ?>
            </table>
    </fieldset>
    <hr>
</div>
</div>

 