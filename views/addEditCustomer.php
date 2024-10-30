<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_customer_add_edit' );

?>
<div class="fsms_div">
    <form class='fsms-form' method="post">
        <table>
            <caption><?php echo esc_attr( $action ); ?> customer</caption>
            <thead></thead>
            <tbody>

            <tr>
                <td>Customer first name</td>
                <td><input class="form-control" type="text" name="cust_fname"
                           value="<?php echo esc_attr( @$customer->cust_fname ) ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer surname</td>
                <td><input class="form-control" type="text" name="cust_lname"
                           value="<?php echo esc_attr( @$customer->cust_lname ) ?>"></td>
            </tr>
            <tr>
                <td>Customer email</td>
                <td><input class="form-control" type="text" name="cust_email"
                           value="<?php echo esc_attr( @$customer->cust_email ) ?>"></td>
            </tr>
            <tr>
                <td>Customer phone</td>
                <td><input class="form-control" type="text" name="cust_phone"
                           value="<?php echo esc_attr( @$customer->cust_phone ) ?>"></td>
            </tr>
            <tr>
                <td>Customer enable</td>
                <td>
					<?php

					if ( $action == "edit" ) {
						$checkedYes = '';
						if ( @$customer->cust_status == 1 ) {
							$checkedYes = 'checked="checked"';
						}
						$checkedNo = "";
						if ( @$customer->cust_status == 0 ) {
							$checkedNo = 'checked="checked"';
						}
					} else {
						$checkedYes = 'checked="checked"';
						$checkedNo  = "";
					}

					?>
                    <div>
                        <label for="html">Yes</label><br>
                        <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="cust_status" value="1">
                    </div>
                    <div>
                        <label for="html">No</label><br>
                        <input type="radio" name="cust_status" value="0" <?php echo esc_attr( $checkedNo ); ?>>
                    </div>
                </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="">
                    <input type="submit" value="Save">
</div>
<input type="hidden" name="ok" value="1">
<input name="nonce" type="hidden" value="<?php echo $nonce; ?>">
</td>
</tr>
</tbody>

</table>


</form>
</div>
