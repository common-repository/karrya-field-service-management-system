<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
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
</div>
