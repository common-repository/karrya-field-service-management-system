<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
    <table id="summary_block_table">
        <caption>Summary</caption>
        <tr>
            <td>Deparment</td>
            <td><?php echo esc_attr( $leadView->department_name ); ?></td>
        </tr>
        <tr>
            <td>Sub department</td>
            <td><?php echo esc_attr( $leadView->sub_department_name ); ?></td>
        </tr>
        <tr>
            <td>Owner</td>
            <td><?php echo esc_attr( $leadView->owner ); ?></td>
        </tr>
        <tr>
            <td>Worker</td>
            <td><?php echo esc_attr( $leadView->worker ); ?></td>
        </tr>
        <tr>
            <td>Name</td>
            <td><?php echo esc_attr( $leadView->lead_cus_fname ); ?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><?php echo esc_attr( $leadView->lead_cus_address ); ?><?php echo esc_attr( $leadView->lead_cus_city ); ?><?php echo esc_attr( $leadView->lead_cus_postcode ); ?></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><?php echo esc_attr( $leadView->lead_cus_phone ); ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo esc_attr( $leadView->lead_email ); ?></td>
        </tr>
        <tr>
            <td>Stage</td>
            <td><?php echo esc_attr( $leadView->stage_name ); ?></td>
        </tr>
        <tr>
            <td>Appointment date</td>
            <td><?php echo esc_attr( $leadView->lead_app_date ); ?></td>
        </tr>
        <tr>
            <td>Details</td>
            <td><?php echo wp_kses_post( $leadView->customer_upload_images ); ?></td>
        </tr>
    </table>
    <div id="lead_sheet_block">
        <table>
            <caption>Lead sheet

            </caption>
            <tr>
                <td>Lead sheet</td>
                <td><?php echo wp_kses_post( wp_unslash( $leadView->lead_sheet ) ); ?></td>
            </tr>
        </table>
    </div>
</div>

<?php
include( FSMS_PATH . "/views/subscriber/invoice_subscriber.php" );
?>
<?php
include( FSMS_PATH . "/views/subscriber/quotes_subscriber.php" );
?>