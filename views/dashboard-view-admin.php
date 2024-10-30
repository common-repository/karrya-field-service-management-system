<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_stat_search' );

?>
<div class="fsms_div">
<div class="fsms_notification"> 
<div>Welcome to Karrya</div>
<p>Karrraya is a free plugin. You can request a new feature or report any bug via email <a href="mailto:abc@example.com?subject=Feedback Karrya">wapnsihantha@gmail.com</a>. If you are satisfying with this plugin please write and SEO article about your company and the plugin, then submit your article to the <a target="_blank" href="https://www.enuyanu.com/free-directory-submission.php?from=karrya">enuryanu.com</a>.</p>
</div>
<table>
    <caption>
        <div>Karrya Field service management system</div>
    </caption>
    <thead>
    <tr>
        <th>Total # leads</th>
        <th>Turnover</th>
        <th>Cost</th>
        <th>Payment</th>
        <th>Profit</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo @esc_attr( $leadCount[0]->totalLeads ); ?></td>
        <td><?php echo @change_product_price_display( esc_attr( $costAndPayment[0]->netCharge ) ); ?>  </td>
        <td><?php echo @change_product_price_display( esc_attr( $costAndPayment[1]->netCharge ) ); ?> </td>
        <td><?php echo @change_product_price_display( esc_attr( $costAndPayment[2]->netCharge ) ); ?></td>
        <td><?php echo @esc_attr( change_product_price_display( $costAndPayment[2]->netCharge - $costAndPayment[1]->netCharge ) ); ?> </td>
    </tr>
    </tbody>
</table>

<div id="totalFilterDiv">
    <form action="admin.php?page=fsms_options&action=search" method="post">

        <table>
            <caption>
                Search
            </caption>
            <tr>
                <td>Month from</td>
                <td><input type="text" autocomplete="off" id="fromDate" name="fromDate"
                           value="<?php echo @esc_attr( $fromdate ); ?>"></td>
            </tr>
            <tr>
                <td>Month to</td>
                <td><input type="text" autocomplete="off" id="toDate" name="toDate"
                           value="<?php echo @esc_attr( $todate ); ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Search">
</div>

<input type="hidden" name="ok" value="1">
<input name="nonce" type="hidden" name="ok" value="<?php echo $nonce; ?>">
</td>
</tr>
</table>

</form>
</div>
 