<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function fsms_deactivate() {
	global $wpdb;
	/*$table = $wpdb->prefix . 'fsms_leads';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_stage';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_site';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_lead_log';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_payment';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_message';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_invoice';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_department';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_customer';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_stock';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );

	$table = $wpdb->prefix . 'fsms_charge_type';
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
	*/


	$role = get_role( 'administrator' );
	$role->remove_cap( 'admin_lead_setting' );

	$role = get_role( 'lead_owner' );
	$role->remove_cap( 'lead_summary_owner' );

	$role = get_role( 'lead_worker' );
	$role->remove_cap( 'lead_summary_worker' );

	$role = get_role( 'subscriber' );
	$role->remove_cap( 'lead_summary_subscriber' );

	$role = get_role( 'lead_supplier' );
	$role->remove_cap( 'lead_summary_supplier' );

	$role = get_role( 'lead_customer' );
	$role->remove_cap( 'lead_summary_subscriber' );


	remove_role( 'lead_owner' );
	remove_role( 'lead_worker' );
	remove_role( 'lead_supplier' );
	remove_role( 'lead_customer' );
}