<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php
$nonce = wp_create_nonce( 'fsms_lead_message_add' );

?>
<div class="fsms_div">
<table>
    <caption>Message

    </caption>

    <tr>

        <td width="100%" valign="top">
            <table>
                <caption>Worker
                    <span onclick="showWorkerMessageBox();" class='cursorPointer'>Add message</span></caption>
                <tr id="owrkerMessage" style="display:none">
                    <td valign="top">
                        <form action="admin.php?page=fsms_work_leads&action=add_message&id=<?php echo esc_attr( $id ); ?>"
                              method="POST">
							<?php wp_editor( '', 'message_worker' ) ?>
                            <input type="hidden" name="ok" value="1">
                            <input type="hidden" name="user_type" value="3">
                            <input name="nonce" type="hidden" name="ok" value="<?php echo $nonce; ?>">
                            <input type="submit" name="send" value="Send">
                            <input type="hidden" id="" name="message_parent_id" value="0" class="message_parent_id">
                            <input type="hidden" id="message_for_id" name="message_for_id"
                                   value="<?php echo esc_attr( $leadView->lead_worker ); ?>">
                            <input onclick="cancelWorkerMessageBox();" type="button" name="cancel" value="Cancel">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
						<?php include FSMS_PATH . '/views/worker_message.php'; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>