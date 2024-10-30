<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php

$nonce = wp_create_nonce( 'fsms_lead_sheet' );

?>
<div class="fsms_div">
    <form class='fsms-form' method="post">
        <table>
            <caption>Add lead sheet
                <span><a href="admin.php?page=fsms_work_leads&action=lead_view&id=<?php echo esc_attr( $lead->id ); ?>">View</a></span>
            </caption>
            <thead></thead>
            <tbody>

            <tr>
                <td>Lead sheet</td>
                <td>
					<?php wp_editor( wp_kses_post( stripslashes( @$lead->lead_sheet ) ), 'lead_sheet' ) ?>
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