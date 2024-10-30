<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
<div id="chargeType_block">

    <table width="100%">
        <caption>
            Charge type list
            <span><a href="admin.php?page=fsms_charge_types&action=add">Add new charge type</a></span>
        </caption>
        <thead>
        <tr>
            <th>ID</th>
            <th>Entry category</th>
            <th>Charge type</th>
            <th>Section</th>
            <th>Status</th>
            <th>Display order</th>
            <th>Option</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $chargeTypes as $chargeType ): ?>
            <tr>
                <td>
                    <a href="admin.php?page=fsms_departments&action=fsms_charge_types&id=<?php echo esc_attr( $chargeType->id ); ?>"><?php echo esc_attr( $chargeType->id ); ?></a>
                </td>
                <td><?php echo esc_attr( $chargeType->entry_category ); ?></td>
                <td><?php echo esc_attr( $chargeType->charge_type ); ?></td>
                <td>
					<?php
					if ( $chargeType->list_in == 1 ) {
						$savedCharge = 'Charge';
					}
					if ( $chargeType->list_in == 2 ) {
						$savedCharge = 'Cost';
					}
					if ( $chargeType->list_in == 3 ) {
						$savedCharge = 'Payment';
					}
					echo esc_attr( $savedCharge );
					?></td>
                <td>
					<?php
					if ( $chargeType->status == 0 ) {
						echo "Hide";
					} else {
						echo "Show";
					}
					?>
                </td>
                <td><?php echo esc_attr( $chargeType->display_order ); ?></td>
                <td>
                    <a href="admin.php?page=fsms_charge_types&action=edit&id=<?php echo esc_attr( $chargeType->id ); ?>">Edit</a>
                    |
                    <a href="admin.php?page=fsms_charge_types&action=delete&id=<?php echo esc_attr( $chargeType->id ); ?>"
                       onclick="return confirm('Are you sure?')">Delete</a></td>

            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>

