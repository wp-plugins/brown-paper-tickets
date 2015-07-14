<?php

namespace BrownPaperTickets\Modules;
require_once( plugin_dir_path( __FILE__ ).'../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/appearance-inputs.php' );

class Appearance extends Module {

	public function register_settings() {
		register_setting( self::$menu_slug, self::$setting_prefix . 'event_list_style' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'calendar_style' );
	}

	public function register_sections() {

		$section_title = 'Appearance Settings';
		$section_suffix = '_appearance';

		$inputs = new Appearance\Inputs();

		add_settings_section(
			$section_title,
			$section_title,
			array( $inputs, 'section' ),
			self::$menu_slug . $section_suffix
		);

		add_settings_field(
			self::$setting_prefix . 'event_list_style', // The ID of the input.
			'Event List Appearance', // The title of the field.
			array( $inputs, 'event_list' ), // Event HTML callback
			self::$menu_slug . $section_suffix, // The settings page.
			$section_title // The section that the field will be rendered in.
		);

		add_settings_field(
			self::$setting_prefix . 'calendar_style',
			'Calendar Appearance',
			array( $inputs, 'calendar' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);
	}

	public function set_default_setting_values() {
		$event_list_options = get_option(
			self::$setting_prefix . 'event_list_style'
		);

		$calendar_options = self::$menu_slug . self::$setting_prefix . 'calendar_style';

		if ( ! $event_list_options ) {
			$event_list_options = array(
				'use_style' => false,
				'custom_css' => '',
			);

			update_option( self::$menu_slug . self::$setting_prefix . 'event_list_style', $event_list_options );
		}

		if ( ! $calendar_options ) {
			$calendar_options = array(
				'use_style' => false,
				'custom_css' => '',
			);

			update_option( self::$menu_slug . self::$setting_prefix . 'calendar_style', $calendar_options );
		}
	}

	public function remove_setting_values() {
		delete_option( self::$menu_slug . self::$setting_prefix . 'calendar_style' );
		delete_option( self::$menu_slug . self::$setting_prefix . 'event_list_style' );
	}

}