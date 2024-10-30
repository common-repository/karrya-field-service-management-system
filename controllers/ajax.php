<?php
// procedural function to dispatch ajax requests
function fsms_ajax() {
	global $wpdb, $user_ID;

	echo FSMSLeads:: book();

	exit;
}