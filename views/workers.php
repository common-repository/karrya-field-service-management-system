<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
<div id="workers_block">

    <table width="100%">
        <caption>
            Lead workers list
            <span><a href="user-new.php">Add new</a></span>
        </caption>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Option</th>
        </tr>
		<?php foreach ( $workers as $worker ): ?>
            <tr>
                <td><?php echo esc_attr( $worker->ID ); ?></a></td>
                <td><?php echo esc_attr( $worker->user_nicename ); ?></td>

                <td><a href="user-edit.php?user_id=<?php echo esc_attr( $worker->ID ); ?>">Edit</a></td>

            </tr>
		<?php endforeach; ?>

    </table>
</div>
</div>

