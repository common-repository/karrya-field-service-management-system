<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div id="log_block">
    <fieldset>
        <legend>Logs:</legend>
        <table width="100%">
            <caption>Logs

            </caption>
            <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Details</th>
                <th>Done</th>

            </tr>
			<?php foreach ( $logs as $log ): ?>
                <tr>
                    <td><?php echo esc_attr( $log->id ); ?></td>
                    <td><?php echo esc_attr( $log->action_date_time ); ?></td>
                    <td><?php echo esc_attr( $log->action_text ); ?></td>
                    <td><?php echo esc_attr( $log->user_nicename ); ?></td>
                </tr>
			<?php endforeach; ?>
        </table>
    </fieldset>
    <hr>
</div>