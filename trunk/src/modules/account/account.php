<?php

namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ).'../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/account-ajax.php' );
require_once( plugin_dir_path( __FILE__ ).'/account-inputs.php' );

class Account extends Module {


	public function load_admin_ajax_actions() {
		add_action( 'wp_ajax_bpt_get_account', array( 'BrownPaperTickets\Modules\Account\Ajax', 'get_account' ) );
		add_action( 'wp_ajax_bpt_test_account', array( 'BrownPaperTickets\Modules\Account\Ajax', 'test_account' ) );
	}

	public function load_public_ajax_actions() {
		// Not sure if I need this?
		// add_action( 'wp_ajax_nopriv_bpt_get_account', array( 'BrownPaperTickets\Modules\Account\Ajax', 'get_account' ) );
	}

	public function register_sections() {

		$section_suffix = '_api';
		$section_title  = 'API Credentials';

		$inputs = new Account\Inputs();

		add_settings_section( $section_title, $section_title, array( $inputs, 'section' ), self::$menu_slug . $section_suffix );

		add_settings_field(
			self::$setting_prefix . 'dev_id',
			'Developer ID',
			array( $inputs, 'developer_id' ),
			self::$menu_slug . $section_suffix, $section_title
		);

		add_settings_field(
			self::$setting_prefix . 'client_id',
			'Client ID',
			array( $inputs, 'client_id' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);
	}

	public function register_settings() {
		register_setting( self::$menu_slug, self::$setting_prefix . 'dev_id' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'client_id' );
	}
}