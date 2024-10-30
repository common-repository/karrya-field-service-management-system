<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="headerLinksDiv">
    <div class="headerLinkDiv">
        <a href="admin.php?page=fsms_work_leads&action=list">Leads</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#lead_sheet_block">View lead sheet</a>
    </div>
    <div class="headerLinkDiv">
        <a href="admin.php?page=fsms_work_leads&action=lead_sheet&id=<?php echo esc_attr( $id ); ?>">Add lead sheet</a>
    </div>

</div>