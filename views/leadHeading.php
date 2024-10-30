<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="headerLinksDiv">
    <div class="headerLinkDiv">
        <a href="admin.php?page=fsms_leads&action=edit&id=<?php echo esc_attr( $leadView->id ); ?>">Edit</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#other_lead_block">Other Leads</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#payment_block">Payment</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#charge_block">Charge</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#cost_block">Cost</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#summary_block_table">Summary</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#invoice_block">Invoice</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#quote_block">Qutoes</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#site_block">Site</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#log_block">Log</a>
    </div>
    <div class="headerLinkDiv">
        <a href="#lead_sheet_block">Lead sheet</a>
    </div>
</div>