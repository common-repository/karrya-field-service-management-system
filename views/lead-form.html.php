<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php
$nonce = wp_create_nonce( 'fsms_frontend_lead_add' );
?>
<div class="fsms_div">
<div class="frontEndLeadDiv">
    <form onsubmit="return leadBook(this);" id="fsmsbook<?php echo esc_attr( $shortcode_id ); ?>"
          enctype="multipart/form-data">
        <table class="frontEndLeadForm">
            <tr>
                <td>Department</td>
                <td>
                    <select name="lead_dep_id" id="lead_dep_id"
                            onchange="departmentsList(this.value,<?php echo esc_attr( $department_id ); ?>);">
                        <option>Please select</option>
						<?php foreach ( $departments as $department ):
							$selected = "";
							if ( $department_id == $department->id ) {
								$selected = "selected";
							}
							?>
                            <option <?php echo esc_attr( $selected ); ?>
                                    value="<?php echo esc_attr( $department->id ); ?>"><?php echo esc_attr( $department->department_name ); ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sub department</td>
                <td>
                    <select name="lead_sub_dep_id" id="lead_sub_dep_id">
                        <option>Please select</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Your forename</td>
                <td><input type="text" name="lead_cus_fname"></td>
            </tr>
            <tr>
                <td>Your surname</td>
                <td><input type="text" name="lead_cus_sname"></td>
            </tr>
            <tr>
                <td>Your email</td>
                <td><input type="text" name="lead_email"></td>
            </tr>
            <tr>
                <td>Your contact number</td>
                <td><input type="text" name="lead_cus_phone"></td>
            </tr>
            <tr>
                <td>Address line</td>
                <td><input type="text" name="lead_cus_address"></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input type="text" name="lead_cus_city"></td>
            </tr>
            <tr>
                <td>Postcode</td>
                <td><input type="text" name="lead_cus_postcode"></td>
            </tr>
            <tr>
                <td>Details</td>
                <td>
                    <textarea name="customer_upload_images" cols="5" rows="4"></textarea>
                </td>
            </tr>
            <tr>
                <td>Images <span style="font-size: 12px;">(*You can add mulitple images)</span></td>
                <td>
                    <input type="file" name="myfilefield[]" id="myfilefield" multiple="multiple"/>

                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="hidden" name="action" value="lead_book">

                    <input type="hidden" name="shortcode_id" value="<?php echo esc_attr( $shortcode_id ) ?>">

                    <input type="hidden" name="ok" value="1">

                    <input name="nonce" type="hidden" value="<?php echo $nonce; ?>">
                    <div class="loader" style="display:none;"></div>

                    <input id="bookbtn" class="cursorPointer saveBtn" type="button" name="" value="Book now"
                           onclick="leadBook(this.form);">
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
