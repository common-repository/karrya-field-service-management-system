<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>

<table>
    <caption>
        <div></div>
    </caption>
    <tr>
        <td>Total # leads</td>
        <td>Turnover</td>
        <td>Cost</td>
        <td>Payment</td>
        <td>Profit</td>
    </tr>
    <tr>
        <td><?php echo @esc_attr( $leadCountSearch[0]->totalLeads ); ?></td>
        <td><?php echo change_product_price_display( @esc_attr( $costAndPaymentSearch[0]->netCharge ) ); ?>  </td>
        <td><?php echo change_product_price_display( @esc_attr( $costAndPaymentSearch[1]->netCharge ) ); ?> </td>
        <td><?php echo change_product_price_display( @esc_attr( $costAndPaymentSearch[2]->netCharge ) ); ?></td>
        <td><?php echo change_product_price_display( @esc_attr( $costAndPaymentSearch[2]->netCharge - @$costAndPaymentSearch[1]->netCharge ) ); ?> </td>
    </tr>
</table> 