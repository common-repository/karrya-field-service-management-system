<?php

class FSMSUsers {

	public function __construct() {
		// Register hooks
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
	}

	public function activation() {
		self::add_cap();
	}

	public function deactivation() {
		self::remove_cap();
	}

	// Remove the plugin-specific custom capability
	public static function remove_cap() {
		$roles = get_editable_roles();
		foreach ( $GLOBALS['wp_roles']->role_objects as $key => $role ) {
			if ( isset( $roles[ $key ] ) && $role->has_cap( 'add_me' ) ) {
				$role->remove_cap( 'add_me' );
			}
		}
	}


	// Add the new capability to all roles having a certain built-in capability
	public static function add_cap() {
		$admin_role = get_role( 'administrator' );
		// grant the unfiltered_html capability
		$admin_role->add_cap( 'add_me', true );
	}

}

new FSMSUsers();
 