<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php foreach ( $allmessagesParentOwner as $allmessageParent ):

	$_messageReply = new FSMSMessage();
	$allReplyMessagesParent = $_messageReply->getAllMessages( $allmessageParent->user_type, $id, $allmessageParent->id );
	?>
    <div>
		<?php echo esc_attr( $allmessageParent->message_added_time ); ?>
        | <?php echo esc_attr( $allmessageParent->user_nicename ); ?>
        <span class='cursorPointer replyMessage'
              onclick="messageReplyOwner(<?php echo esc_attr( $allmessageParent->id ); ?>);">Reply</span>
        <div>
			<?php echo wp_kses_post( stripslashes( $allmessageParent->message ) ); ?>
        </div>
		<?php foreach ( $allReplyMessagesParent as $allReplyMessageParent ): ?>
            <div class="reply">
				<?php echo esc_attr( $allReplyMessageParent->message_added_time ); ?>
                <div><?php echo wp_kses_post( stripslashes( $allReplyMessageParent->message ) ); ?></div>
            </div>

		<?php endforeach; ?>
    </div>
    <hr>
<?php endforeach; ?>