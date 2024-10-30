<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_stage_add_edit' );

?>
<div class="fsms_div">
    <form class='fsms-form' method="post">
        <table>
            <caption><?php echo esc_attr( $action ); ?> stage</caption>
            <thead></thead>
            <tbody>

            <tr>
                <td>Stage name</td>
                <td><input class="form-control" type="text" name="stage_name"
                           value="<?php echo esc_attr( @$stage->stage_name ) ?>" size="150"></td>
            </tr>
            <tr>
                <td>Stage order</td>
                <td><input class="form-control number" type="number" name="stage_order"
                           value="<?php echo esc_attr( @$stage->stage_order ) ?>" size="2" placeholder="0"></td>
            </tr>
            <tr>
                <td>Stage enable</td>
                <td>
					<?php

					if ( $action == "edit" ) {
						$checkedYes = '';
						if ( @$stage->stage_status == 1 ) {
							$checkedYes = 'checked="checked"';
						}
						$checkedNo = "";
						if ( @$stage->stage_status == 0 ) {
							$checkedNo = 'checked="checked"';
						}
					} else {
						$checkedYes = 'checked="checked"';
						$checkedNo  = "";
					}

					?>
                    <div>
                        <label for="html">Yes</label><br>
                        <input type="radio" <?php echo esc_attr( $checkedYes ); ?> name="stage_status" value="1">
                    </div>
                    <div>
                        <label for="html">No</label><br>
                        <input type="radio" name="stage_status" value="0" <?php echo esc_attr( $checkedNo ); ?>>
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