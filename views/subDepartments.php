<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div id="sub_department_block">

    <table width="100%">
        <caption>
            Sub department list - <?php echo esc_attr( $departmentView->department_name ); ?>
            <span><a href="admin.php?page=fsms_departments&action=add_sub_dep&id=<?php echo esc_attr( $departmentView->id ); ?>">Add new sub dep</a></span>
        </caption>
        <div id="department_block">

            <table width="100%">
                <tr>
                    <th>Id</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Display order</th>
                    <th>Option</th>
                </tr>
				<?php foreach ( $subDepartments as $subDepartment ): ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=fsms_departments&action=department_view&id=<?php echo esc_attr( $subDepartment->id ); ?>"><?php echo esc_attr( $subDepartment->id ); ?></a>
                        </td>
                        <td><?php echo esc_attr( $subDepartment->department_name ); ?></td>
                        <td>
							<?php
							if ( $subDepartment->department_status == 0 ) {
								echo "Hide";
							} else {
								echo "Show";
							}
							?>
                        </td>
                        <td><?php echo esc_attr( $subDepartment->department_order ); ?></td>
                        <td>
                            <a href="admin.php?page=fsms_departments&action=edit&id=<?php echo esc_attr( $subDepartment->id ); ?>">Edit</a>
                            |
                            <a href="admin.php?page=fsms_departments&action=delete&id=<?php echo esc_attr( $subDepartment->id ); ?>"
                               onclick="return confirm('Are you sure?')">Delete</a></td>

                    </tr>
				<?php endforeach; ?>
            </table>

