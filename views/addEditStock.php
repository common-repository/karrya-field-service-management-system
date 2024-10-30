<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_stock_add_edit' );

?>
<div class="fsms_div">
    <form class='fsms-form' method="post">
        <table>
            <caption><?php echo esc_attr( $action ); ?> stock</caption>
            <thead></thead>
            <tbody>

            <tr>
                <td>SKU</td>
                <td><input id="sku" onkeyup='searchSku();' autocomplete='off' class="form-control" type="text" name="sku"
                           value="<?php echo esc_attr( @$stock->sku ) ?>" size="150">
                 <div id='suggesstion-box'></div>          
                </td>
            </tr>
            <tr>
                <td>Charge type</td>
                <td>
                    <select name='charge_type' id='charge_type' style=''>
						<?php
						foreach ( $chargeTypes as $chargeType ):
							$selected = "";
							if ( $stock->charge_type == $chargeType->id ) {
								$selected = "selected";
							}
							?>
                            <option value='<?php echo esc_attr( @$chargeType->id ); ?>' <?php echo esc_attr( $selected ); ?>>
								<?php echo esc_attr( @$chargeType->charge_type ); ?>

                            </option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><input id="description" autocomplete='off' class="form-control " type="text" name="description"
                           value="<?php echo esc_attr( @$stock->description ); ?>"></td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>

                    <select name="supplier_id" id="supplier_id">
                        <option>Please select</option>
                        <?php foreach ( $lead_suppliers as $user ):
                            //print_r($user);
                            $selectedOwner = "";
                            if ( $stock->supplier_id == $user->ID ) {
                                $selectedOwner = "selected";
                            }
                            ?>
                            <option <?php echo esc_attr( $selectedOwner ); ?>
                                    value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_attr( $user->user_nicename ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>New fill stock count</td>
                <td><input class="form-control number" type="number" name="added_stock_count"
                           value="<?php echo esc_attr( @$stock->added_stock_count ); ?>"></td>
            </tr>

            <tr>
                <td>Buy amount</td>
                <td><input id="buy_amount" class="form-control number" type="number" name="buy_amount"
                           value="<?php echo esc_attr( @$stock->buy_amount ); ?>"></td>
            </tr>
            <tr>
                <td>Sell amount</td>
                <td><input id="sell_amount" class="form-control number" type="number" name="sell_amount"
                           value="<?php echo esc_attr( @$stock->sell_amount ); ?>"></td>
            </tr>


            <tr>
                <td></td>
                <td colspan="">
                    <input type="submit" value="Save">
                    <a href="admin.php?page=fsms_stocks&action=list"><input type="button" value="Cancel"></a>
</div>
<input type="hidden" name="ok" value="1">
<input name="nonce" type="hidden" value="<?php echo $nonce; ?>">
</td>
</tr>
</tbody>

</table>
</form>
</div>