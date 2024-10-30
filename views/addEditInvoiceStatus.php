<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_invoices_status' );

?>
<div class="fsms_div">
    <form class='fsms-form' method="post">
        <table>
            <caption>Approve?</caption>
            <thead></thead>
            <tbody>

            <tr>
                <td>Approve?</td>
                <td>
					<?php


					$checkedYes = '';
					if ( @$invoice->is_approve == 1 ) {
						$checkedYes = 'checked="checked"';
					}
					$checkedNo = "";
					if ( @$invoice->is_approve == 0 ) {
						$checkedNo = 'checked="checked"';
					}


					?>
                    <div>
                        <label for="html">Yes</label><br>
                        <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="is_approve" value="1">
                    </div>
                    <div>
                        <label for="html">No</label><br>
                        <input type="radio" name="is_approve" value="0" <?php echo esc_attr( $checkedNo ); ?>>
                    </div>
                </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="">
                    <input type="submit" value="Save">
</div>
<input type="hidden" name="ok" value="1">
<input type="hidden" name="quoteOrInvoice" value="<?php echo esc_attr( $quoteOrInvoice ); ?>">
<input name="nonce" type="hidden" value="<?php echo $nonce; ?>">
</td>
</tr>
</tbody>

</table>


</form>
</div>
