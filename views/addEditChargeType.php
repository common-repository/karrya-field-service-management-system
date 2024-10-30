<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_charge_type_add_edit' );
?>
<div class="fsms_div">
    <div id="charge_type_block">
        <form class='fsms-form' method="post">
            <table width="100%">
                <caption>
                    Charge type
                </caption>
                <tr>
                    <td>List in</td>
                    <td>
						<?php

						if ( $action == "edit" ) {
							$checkedCharge = '';
							if ( @$chargeType->list_in == 1 ) {
								$checkedCharge = 'selected="selected"';
							}
							$checkedCost = "";
							if ( @$chargeType->list_in == 2 ) {
								$checkedCost = 'selected="selected"';
							}
							$checkedPayment = "";
							if ( @$chargeType->list_in == 3 ) {
								$checkedPayment = 'selected="selected"';
							}
						} else {

						}

						?>
                        <select name="list_in">
                            <option value="0">Please select</option>
                            <option value="1" <?php echo esc_attr( @$checkedCharge ); ?>>Charge section</option>
                            <option value="2" <?php echo esc_attr( @$checkedCost ); ?>>Cost section</option>
                            <option value="3" <?php echo esc_attr( @$checkedPayment ); ?>>Payment section</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
						<?php

						if ( $action == "edit" ) {
							$checkedCategory = '';
							if ( @$chargeType->entry_category == "charge" ) {
								$checkedCategory = 'selected="selected"';
							}
						}

						?>
                        <select name="entry_category">

                            <option value="charge" <?php echo esc_attr( @$checkedCategory ); ?>>Charge</option>
                            <option value="nonCharge" <?php echo esc_attr( @$checkedCategory ); ?>>non charge</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>charge type name</td>
                    <td><input type="text" name="charge_type"
                               value="<?php echo esc_attr( @$chargeType->charge_type ) ?>"></td>
                </tr>
                <tr>
                    <td>Display order</td>
                    <td><input type="number" class="number" name="display_order"
                               value="<?php echo esc_attr( @$chargeType->display_order ) ?>" placeholder="0">
                    </td>
                </tr>
                <tr>
                    <td>Enable</td>
                    <td>
						<?php

						if ( $action == "edit" ) {
							$checkedYes = '';
							if ( @$chargeType->status == 1 ) {
								$checkedYes = 'checked="checked"';
							}
							$checkedNo = "";
							if ( @$chargeType->status == 0 ) {
								$checkedNo = 'checked="checked"';
							}
						} else {
							$checkedYes = 'checked="checked"';
							$checkedNo  = "";
						}

						?>
                        <div>
                            <label for="html">Yes</label><br>
                            <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="status" value="1">
                        </div>
                        <div>
                            <label for="html">No</label><br>
                            <input type="radio" name="status" value="0" <?php echo esc_attr( $checkedNo ); ?>>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" value="Save">
    </div>
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

    </td>
    </tr>

</div>
</table>
</form>
</div>
 