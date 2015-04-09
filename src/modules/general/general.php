<?php

namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ).'../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/general-ajax.php' );
require_once( plugin_dir_path( __FILE__ ).'/general-inputs.php' );

class General extends Module {

	public function register_sections() {

		$section_suffix = '_general';
		$section_title  = 'General Settings';

		$inputs = new General\Inputs();

		// Register the cached_data array. This is to keep track of the data we have cached.

		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );

		add_settings_field(
			self::$setting_prefix . 'cache_time',
			'Cache Settings',
			array( $inputs, 'cache_time' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);
	}

	public function load_admin_ajax_actions() {
		add_action( 'wp_ajax_bpt_delete_cache', array( 'BrownPaperTickets\Modules\General\Ajax', 'delete_cache' ) );
	}

	public function register_settings() {
		register_setting( self::$menu_slug, self::$setting_prefix . 'cached_data' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'show_wizard' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'cache_time' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'cache_unit' );
	}

	public function set_default_setting_values() {
		update_option( $setting_prefix . '_bpt_cache_time', null );
	}
}