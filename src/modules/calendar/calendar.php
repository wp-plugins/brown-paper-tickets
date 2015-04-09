<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ) . '../bpt-module-class.php' );
require_once( 'calendar-ajax.php' );
require_once( 'calendar-shortcode.php' );
require_once( 'calendar-widget.php' );
require_once( 'calendar-inputs.php' );

class Calendar extends Module {

	public function init_actions() {
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
	}

	public function load_admin_js( $hook ) {

	}

	public function load_public_js( $hook ) {

	}

	public function load_admin_css( $hook ) {

	}

	public function load_public_css( $hook ) {

	}

	public function load_shared_js( $hook ) {

	}

	public function load_shared_css( $hook ) {

	}

	public function load_public_ajax_actions() {
		add_action( 'wp_ajax_nopriv_bpt_get_calendar_events', array( 'BrownPaperTickets\Modules\Calendar\Ajax', 'get_events' ) );
	}

	public function load_admin_ajax_actions() {
		add_action( 'wp_ajax_bpt_get_calendar_events', array( 'BrownPaperTickets\Modules\Calendar\Ajax', 'get_events' ) );
	}

	public function load_menus() {

	}

	public function load_widgets() {
		register_widget( 'BrownPaperTickets\Modules\Calendar\Widget' );
	}

	public function load_shortcode() {
		add_shortcode( 'event-calendar', array( 'BrownPaperTickets\Modules\Calendar\Shortcode', 'calendar' ) );
		add_shortcode( 'event_calendar', array( 'BrownPaperTickets\Modules\Calendar\Shortcode', 'calendar' ) );
	}

	public function register_sections() {

		$section_suffix = '_calendar';
		$section_title  = 'Calendar Settings';

		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );

		$inputs = new Calendar\Inputs;

		add_settings_field(
			self::$setting_prefix . 'show_upcoming_events_calendar',
			'Display Upcoming Events in Calendar',
			array( $inputs, 'show_upcoming_events' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);

		add_settings_field(
			self::$setting_prefix . 'date_text_calendar',
			'"Events on..." text for the calendar event view',
			array( $inputs, 'date_text' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);

		add_settings_field(
			self::$setting_prefix . 'purchase_text_calendar',
			'Text to display for purchase links',
			array( $inputs, 'purchase_text' ),
			self::$menu_slug . $section_suffix,
			$section_title
		);
	}

	public function register_settings() {
		register_setting( self::$menu_slug, self::$setting_prefix . 'show_upcoming_events_calendar' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'date_text_calendar' );
		register_setting( self::$menu_slug, self::$setting_prefix . 'purchase_text_calendar' );
	}

	public function display_settings_sections() {

	}

	public function set_default_setting_values() {
		update_option( self::$setting_prefix . 'show_upcoming_events_calendar', 'false' );
		update_option( self::$setting_prefix . 'date_text_calendar', 'Events on ' );
		update_option( self::$setting_prefix . 'purchase_text_calendar', 'Buy Tickets' );
	}
}
