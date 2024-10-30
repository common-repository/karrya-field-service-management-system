<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_department_add_edit' );
?>
<div class="fsms_div">
    <div id="department_block">
        <form class='fsms-form' method="post">
            <table width="100%">
                <caption>
                    Add new department
                </caption>
                <tr>
                    <td>Department name</td>
                    <td><input type="text" name="department_name"
                               value="<?php echo esc_attr( @$department->department_name ) ?>"></td>
                </tr>
                <tr>
                    <td>Department order</td>
                    <td><input type="number" class="number" name="department_order"
                               value="<?php echo esc_attr( @$department->department_order ) ?>" placeholder="0">
                    </td>
                </tr>
                <tr>
                    <td>Department enable</td>
                    <td>
						<?php

						if ( $action == "edit" ) {
							$checkedYes = '';
							if ( @$department->department_status == 1 ) {
								$checkedYes = 'checked="checked"';
							}
							$checkedNo = "";
							if ( @$department->department_status == 0 ) {
								$checkedNo = 'checked="checked"';
							}
						} else {
							$checkedYes = 'checked="checked"';
							$checkedNo  = "";
						}

						?>
                        <div>
                            <label for="html">Yes</label><br>
                            <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="department_status"
                                   value="1">
                        </div>
                        <div>
                            <label for="html">No</label><br>
                            <input type="radio" name="department_status"
                                   value="0" <?php echo esc_attr( $checkedNo ); ?>>
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
 