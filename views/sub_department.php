<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_sub_deparment_add_edit' );

?>
<div id="sub_department_block">
    <form class='fsms-form' method="post">
        <table width="100%">
            <caption>
                Add new sub department
            </caption>
            <tr>
                <td>Sub department name</td>
                <td><input type="text" name="department_name"
                           value="<?php echo stripslashes( @esc_attr( $subDepartment->department_name ) ); ?>"></td>
            </tr>
            <tr>
                <td>Sub department order</td>
                <td><input type="number" class="number" name="department_order"
                           value="<?php echo stripslashes( @esc_attr( $subDepartment->department_order ) ); ?>"
                           placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Sub department enable</td>
                <td>
					<?php

					if ( $action == "edit" ) {
						$checkedYes = '';
						if ( @$subDepartment->department_status == 1 ) {
							$checkedYes = 'checked="checked"';
						}
						$checkedNo = "";
						if ( @$subDepartment->department_status == 0 ) {
							$checkedNo = 'checked="checked"';
						}
					} else {
						$checkedYes = 'checked="checked"';
						$checkedNo  = "";
					}

					?>
                    <div>
                        <label for="html">Yes</label><br>
                        <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="department_status" value="1">
                    </div>
                    <div>
                        <label for="html">No</label><br>
                        <input type="radio" name="department_status" value="0" <?php echo esc_attr( $checkedNo ); ?>>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">
                    <input type="submit" value="Save">
</div>
<input type="hidden" name="ok" value="1">
<input type="hidden" name="nonce" value="<?php echo $nonce; ?>">

</td>
</tr>

</div>
  

 