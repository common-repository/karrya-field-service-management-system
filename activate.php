<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * create database table
 */

function fsms_activate() {
	global $wpdb;


	$charset_coallate = $wpdb->get_charset_collate();

	$table_name_lead = $wpdb->prefix . 'fsms_leads';
	$sql1            = "CREATE TABLE $table_name_lead (
              id int(11) NOT NULL AUTO_INCREMENT,
              lead_added_date datetime NOT NULL,
              lead_owner int(11) NOT NULL,
              lead_dep_id int(11) NOT NULL,
              lead_sub_dep_id int(11) NOT NULL,
              lead_cus_fname varchar(200) NOT NULL,
              lead_cus_sname varchar(200) NOT NULL,
              lead_cus_phone varchar(12) NOT NULL,
              lead_cus_address varchar(200) NOT NULL,
              lead_cus_city varchar(200) NOT NULL,
              lead_cus_postcode varchar(200) NOT NULL,
              lead_stage int(11) NOT NULL,
              lead_details text NOT NULL,
              lead_app_date datetime NOT NULL,
              cust_id int(11) NOT NULL,
              site_id int(11) NOT NULL,
              lead_email varchar(200) NOT NULL,
              lead_worker int(11) NOT NULL,
              invoice_top_blcok text NOT NULL,
              invoice_bottom_blcok text NOT NULL,
              quote_top_blcok text NOT NULL,
              quote_bottom_blcok text NOT NULL,
              customer_upload_images text NOT NULL,
              lead_sheet text NOT NULL,
              subscriber_id int(11) NOT NULL,
              PRIMARY KEY (id)
            ) $charset_coallate";

	$table_name_stage = $wpdb->prefix . 'fsms_stage';
	$sql2             = "CREATE TABLE $table_name_stage (
              id int(11) NOT NULL AUTO_INCREMENT,
              stage_name varchar(200) NOT NULL,
              stage_status int(11) NOT NULL,
              stage_order int(11) NOT NULL,
              PRIMARY KEY (id)
            ) $charset_coallate";

	$table_name_site = $wpdb->prefix . 'fsms_site';
	$sql3            = "CREATE TABLE $table_name_site (
              id int(11) NOT NULL AUTO_INCREMENT,
              cust_id int(11) NOT NULL,
              site_name varchar(200) NOT NULL,
              site_city varchar(200) NOT NULL,
              site_status int(11) NOT NULL,
              PRIMARY KEY (id)
            )  $charset_coallate";

	$table_name_payment = $wpdb->prefix . 'fsms_payment';
	$sql4               = "CREATE TABLE $table_name_payment (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          payment_lead_id int(11) NOT NULL,
                          payment_amount float NOT NULL,
                          payment_description varchar(200) NOT NULL,
                          payment_type varchar(10) NOT NULL,
                          status int(1) NOT NULL,
                          vat float NOT NULL,
                          display_order int(11) NOT NULL,
                          is_add_to_supplier_sheet int(11) NOT NULL,
                          is_deduct_from_stock int(11) NOT NULL,
                          is_add_to_quote int(11) NOT NULL,
                          is_publish_to_quote int(11) NOT NULL,
                          is_add_to_invoice int(11) NOT NULL,
                          is_publish_to_invoice int(11) NOT NULL,
                          qty float NOT NULL,
                          charge_type int(11) NOT NULL,
                          sku varchar(255) NOT NULL,
                          entry_category varchar(100) NOT NULL DEFAULT 'charge',
                          supplier_id int(11) NOT NULL,
                          PRIMARY KEY (id)
                        )    $charset_coallate";

	$table_name_message = $wpdb->prefix . 'fsms_message';

	$sql5 = "CREATE TABLE $table_name_message (
              id int(11) NOT NULL AUTO_INCREMENT,
              message_parent_id int(11) NOT NULL,
              message_added_by int(11) NOT NULL,
              message_lead_id int(11) NOT NULL,
              message_for_id int(11) NOT NULL,
              message text NOT NULL,
              message_added_time datetime NOT NULL,
              user_type int(1) NOT NULL,
              PRIMARY KEY (id)
            )  $charset_coallate";

	$table_name_invoice = $wpdb->prefix . 'fsms_invoice';

	$sql6 = "CREATE TABLE $table_name_invoice (
              id int(11) NOT NULL AUTO_INCREMENT,
              lead_id int(11) NOT NULL,
              sender_id int(11) NOT NULL,
              send_to_email varchar(200) NOT NULL,
              send_date datetime NOT NULL,
              send_subject varchar(200) NOT NULL,
              invoice_type varchar(20) NOT NULL,
              send_cc_email text NOT NULL,
              is_approve int(11) NOT NULL DEFAULT -1,
              is_approve_date datetime NOT NULL,
              is_approve_by int(11) NOT NULL,
              PRIMARY KEY (id)
            )  $charset_coallate";

	$table_name_department = $wpdb->prefix . 'fsms_department';
	$sql7                  = "CREATE TABLE $table_name_department (
              id int(11) NOT NULL AUTO_INCREMENT,
              department_name varchar(200) NOT NULL,
              department_parent_id int(11) NOT NULL,
              department_order int(10) NOT NULL,
              department_status int(1) NOT NULL,
              PRIMARY KEY (id)
            )  $charset_coallate";

	$table_name_customer = $wpdb->prefix . 'fsms_customer';
	$sql8                = "CREATE TABLE $table_name_customer (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          cust_fname varchar(200) NOT NULL,
                          cust_lname varchar(200) NOT NULL,
                          cust_phone varchar(12) NOT NULL,
                          cust_status int(1) NOT NULL,
                          cust_email varchar(200) NOT NULL,
                          subscriber_id int(11) NOT NULL,
                          PRIMARY KEY (id)
                        )  $charset_coallate";

	$table_name_log = $wpdb->prefix . 'fsms_lead_log';
	$sql9           = "CREATE TABLE $table_name_log (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        lead_id int(11) NOT NULL,
                        action_date_time datetime NOT NULL,
                        action_text text NOT NULL,
                        action_done int(11) NOT NULL,
                        action_type varchar(200) NOT NULL,
                        PRIMARY KEY (id)
                      )  $charset_coallate";

	$table_name_stock = $wpdb->prefix . 'fsms_stock';
	$sql10            = "CREATE TABLE $table_name_stock (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          added_date datetime NOT NULL,
                          sku text NOT NULL,
                          description varchar(255) NOT NULL,
                          added_stock_count float NOT NULL,
                          used_stock_count int(11) NOT NULL,
                          is_lac int(1) NOT NULL,
                          lead_id int(11) NOT NULL,
                          charge_type int(11) NOT NULL,
                          buy_amount float NOT NULL,
                          sell_amount float NOT NULL,
                          supplier_id int(11) NOT NULL,
                          PRIMARY KEY (id)
                        ) $charset_coallate";

	$table_name_charge_type = $wpdb->prefix . 'fsms_charge_type';
	$sql11                  = "CREATE TABLE $table_name_charge_type (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      charge_type varchar(255) NOT NULL,
                      status int(11) NOT NULL,
                      display_order int(11) NOT NULL,
                      list_in int(11) NOT NULL,
                      entry_category varchar(10) NOT NULL,
                      PRIMARY KEY (id)
                    )  $charset_coallate";


	// require WordPress dbDelta() function
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
	dbDelta( $sql2 );
	dbDelta( $sql3 );
	dbDelta( $sql4 );
	dbDelta( $sql5 );
	dbDelta( $sql6 );
	dbDelta( $sql7 );
	dbDelta( $sql8 );
	dbDelta( $sql9 );
	dbDelta( $sql10 );
	dbDelta( $sql11 );


	/* Create Lead owner User Role */
	add_role(
		'lead_owner',
		__( 'Lead owner' ),
		array(
			'read' => true,

		)
	);
	/* Create Lead worker Role */
	add_role(
		'lead_worker',
		__( 'Lead worker' ),
		array(
			'read' => true,
		)
	);

    /* Create Lead supplier Role */
    add_role(
        'lead_supplier',
        __( 'Lead supplier' ),
        array(
            'read' => true,
        )
    );

    /* Create Lead customer Role */
    add_role(
        'lead_customer',
        __( 'Lead customer' ),
        array(
            'read' => true,
        )
    );

	$role = get_role( 'administrator' );
	$role->add_cap( 'admin_lead_setting' );

	$role = get_role( 'lead_owner' );
	$role->add_cap( 'lead_summary_owner' );
	$role->add_cap( 'upload_files' );

	$role = get_role( 'lead_worker' );
	$role->add_cap( 'lead_summary_worker' );
	$role->add_cap( 'upload_files' );

	$role = get_role( 'subscriber' );
	$role->add_cap( 'lead_summary_subscriber' );

    $role = get_role( 'lead_supplier' );
    $role->add_cap( 'lead_summary_supplier' );

    $role = get_role( 'lead_customer' );
    $role->add_cap( 'lead_summary_subscriber' );


}

function fsms_insert_custom_table_data() {
	global $wpdb;

	$charge_type    = "Charge";
	$status         = 1;
	$display_order  = 1;
	$list_in        = 1;
	$entry_category = "charge";


	$table_name = $wpdb->prefix . 'fsms_charge_type';

	$rows_affected = $wpdb->insert( $table_name, array( 'charge_type'    => $charge_type,
	                                                    'status'         => $status,
	                                                    'display_order'  => $display_order,
	                                                    'list_in'        => $list_in,
	                                                    'entry_category' => $entry_category
	) );

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $rows_affected );
}