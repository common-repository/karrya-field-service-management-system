<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_charge_payment_add_edit' );
?>
<div class="fsms_div">
    <div id="charge_add_edit_block">
        <form class='fsms-form' method="post">
            <table width="100%">
                <caption>
                    Charge Edit
                </caption>
                <tr>
                    <td>Charge type</td>

                    <td>
                        <select name='charge_type' id='charge_type' style=''>
							<?php
							foreach ( $chargeTypes as $chargeType ):
								$selected = "";
								if ( $charge->charge_type == $chargeType->id ) {
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
                    <td>Display order</td>
                    <td>
                        <input type='number' name='display_order' id='display_order' style=''
                               value="<?php echo esc_attr( @$charge->display_order ); ?>">
                    </td>
                </tr>
                <tr>
                    <td>? Quote</td>
                    <td>
						<?php
						$is_publish_to_quote = $charge->is_publish_to_quote;

						?>
                        <select name='is_publish_to_quote' style='' id='is_publish_to_quote'>
                            <option value='1' <?php if ( $is_publish_to_quote == 1 ) {
								echo esc_attr( "selected" );
							} ?>>show line + total
                            </option>
                            <option value='2' <?php if ( $is_publish_to_quote == 2 ) {
								echo esc_attr( "selected" );
							} ?>>hide line + total
                            </option>
                            <option value='3' <?php if ( $is_publish_to_quote == 3 ) {
								echo esc_attr( "selected" );
							} ?>>hide line - total
                            </option>
                            <option value='4' <?php if ( $is_publish_to_quote == 4 ) {
								echo esc_attr( "selected" );
							} ?>>hide amount + total
                            </option>
                            <option value='5' <?php if ( $is_publish_to_quote == 5 ) {
								echo esc_attr( "selected" );
							} ?>>hide amount - total
                            </option>
                            <option value='6' <?php if ( $is_publish_to_quote == 6 ) {
								echo esc_attr( "selected" );
							} ?>>show line - total
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>? Invoice</td>
                    <td>
						<?php
						$is_publish_to_invoice = $charge->is_publish_to_invoice;

						?>

                        <select name='is_publish_to_invoice' style='' id='is_publish_to_invoice'>
                            <option value='1' <?php if ( $is_publish_to_invoice == 1 ) {
								echo esc_attr( "selected" );
							} ?>>show line + total
                            </option>
                            <option value='2' <?php if ( $is_publish_to_invoice == 2 ) {
								echo esc_attr( "selected" );
							} ?>>hide line + total
                            </option>
                            <option value='3' <?php if ( $is_publish_to_invoice == 3 ) {
								echo esc_attr( "selected" );
							} ?>>hide line - total
                            </option>
                            <option value='4' <?php if ( $is_publish_to_invoice == 4 ) {
								echo esc_attr( "selected" );
							} ?>>hide amount + total
                            </option>
                            <option value='5' <?php if ( $is_publish_to_invoice == 5 ) {
								echo esc_attr( "selected" );
							} ?>>hide amount - total
                            </option>
                            <option value='6' <?php if ( $is_publish_to_invoice == 6 ) {
								echo esc_attr( "selected" );
							} ?>>show line - total
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>+ quote</td>
                    <td>
						<?php
						$checked = "";
						if ( $charge->is_add_to_quote == 1 ) {
							$checked = "checked";
						}
						?>
                        <input type='checkbox' <?php echo esc_attr( $checked ); ?> name='is_add_to_quote'
                               id='is_add_to_quote'></td>
                </tr>

                <tr>
                    <td>+ invoce</td>
                    <td>
						<?php
						$checked = "";
						if ( $charge->is_add_to_invoice == 1 ) {
							$checked = "checked";
						}
						?>
                        <input type='checkbox' <?php echo esc_attr( $checked ); ?> name='is_add_to_invoice'
                               id='is_add_to_invoice'></td>
                </tr>

                <tr style="display:none;">
                    <td>- stock</td>
                    <td>
						<?php
						$checked = "";
						if ( $charge->is_deduct_from_stock == 1 ) {
							$checked = "checked";
						}
						?>
                        <input type='checkbox' <?php echo esc_attr( $checked ); ?> name='is_deduct_from_stock'
                               id='is_deduct_from_stock'></td>
                </tr>
                <tr style="display:none;">
                    <td>+ Supplier sheet</td>
                    <td>
						<?php
						$checked = "";
						if ( $charge->is_add_to_supplier_sheet == 1 ) {
							$checked = "checked";
						}
						?>
                        <input type='checkbox' <?php echo esc_attr( $checked ); ?> name='is_add_to_supplier_sheet'
                               id='is_add_to_supplier_sheet'></td>
                </tr>

                

                <tr>
                    <td>SKU</td>
                    <td><input id="sku" onkeyup='searchSku();' autocomplete='off' type='text' name='sku' id='sku' style='width:70px;' placeholder='sku'
                               value="<?php echo esc_attr( @$charge->sku ); ?>">
                     <div id='suggesstion-box'></div>          
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><input autocomplete='off' type='text' id='description' name='payment_description' placeholder='description'
                               value="<?php echo esc_attr( @$charge->payment_description ); ?>"></td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td>

                        <select id="supplier_id" name="supplier_id">
                            <option>Please select</option>
                            <?php foreach ( $lead_suppliers as $user ):
                                //print_r($user);
                                $selectedOwner = "";
                                if ( $lead->lead_owner == $user->supplier_id ) {
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
                    <td>Qty</td>
                    <td><input type='number' id='qty' name='qty' style='width:100px;'
                               value="<?php echo esc_attr( @$charge->qty ); ?>"></td>
                </tr>

                <tr>
                    <td>Amount</td>
                    <td><input type='number' id='sell_amount' name='payment_amount'  style='width:100px;'
                               value="<?php echo esc_attr( @$charge->payment_amount ); ?>"></td>
                </tr>

                <tr>
                    <td>Vat</td>
                    <td><input type='number' id='vat' name='vat' class='number'
                               value="<?php echo esc_attr( @$charge->vat ); ?>"></td>
                </tr>

                <tr>
                    <td>+ status</td>
                    <td>
						<?php
						$checked = "";
						if ( $charge->status == 1 ) {
							$checked = "checked";
						}
						?>
                        <input type='checkbox' <?php echo esc_attr( $checked ); ?> name='status' id='status'></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td></td>
                    <td>
                        <input type="submit" value="Save">
                        <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( @$lead_id ); ?>">
                            <input type="button" value="cancel"></a>
    </div>
    <input type="hidden" name="old_sku" value="<?php echo esc_attr( @$charge->sku ); ?>">
    <input type="hidden" name="old_qty" value="<?php echo esc_attr( @$charge->qty ); ?>">
    <input type="hidden" name="lead_id" value="<?php echo esc_attr( @$lead_id ); ?>">
    <input type="hidden" name="ok" value="1">
    <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

    </td>
    </tr>

</div>
</table>
</form>
</div>
 