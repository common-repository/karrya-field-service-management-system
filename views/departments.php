<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
<div id="department_block">

    <table width="100%">
        <caption>
            Department list
            <span><a href="admin.php?page=fsms_departments&action=add">Add new department</a></span>
        </caption>
        <thead>
        <tr>
            <th>
                <a href='admin.php?page=fsms_departments&dir=<?php echo esc_attr( $odir ); ?>&ob=id<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>ID</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_departments&dir=<?php echo esc_attr( $odir ); ?>&ob=department<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Department</a>
            </th>
            <th>Status</th>
            <th>Display order</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
		<?php foreach ( $departments as $department ): ?>
            <tr>
                <td>
                    <a href="admin.php?page=fsms_departments&action=department_view&id=<?php echo esc_attr( $department->id ); ?>"><?php echo esc_attr( $department->id ); ?></a>
                </td>
                <td><?php echo esc_attr( $department->department_name ); ?></td>
                <td>
					<?php
					if ( $department->department_status == 0 ) {
						echo "Hide";
					} else {
						echo "Show";
					}
					?>
                </td>
                <td><?php echo esc_attr( $department->department_order ); ?></td>
                <td>
                    <a href="admin.php?page=fsms_departments&action=department_view&id=<?php echo esc_attr( $department->id ); ?>">Sub
                        department view</a> | <a
                            href="admin.php?page=fsms_departments&action=edit&id=<?php echo esc_attr( $department->id ); ?>">Edit</a>
                    |
                    <a href="admin.php?page=fsms_departments&action=delete&id=<?php echo esc_attr( $department->id ); ?>"
                       onclick="return confirm('Are you sure?')">Delete</a></td>

            </tr>
		<?php endforeach; ?>
    </table>
    </tbody>
</div>

