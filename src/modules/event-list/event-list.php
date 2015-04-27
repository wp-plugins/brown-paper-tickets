<?php

namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ) . '../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ) . '/event-list-inputs.php' );
require_once( plugin_dir_path( __FILE__ ) . '/event-list-ajax.php' );
require_once( plugin_dir_path( __FILE__ ) . '/event-list-shortcode.php' );

class EventList extends \BrownPaperTickets\Modules\Module {

	static $section_suffix = '_event';

	private static $event_section_title  = 'Event Display Settings';
	private static $date_section_title  = 'Date Display Settings';
	private static $price_section_title = 'Price Display Settings';
	private static $hidden_prices_section_title  = 'Password Protected Prices';

	public function load_shortcode() {
		add_shortcode( 'list-event', array( 'BrownPaperTickets\Modules\EventList\Shortcode', 'list_event_shortcode' ) );
		add_shortcode( 'list_event', array( 'BrownPaperTickets\Modules\EventList\Shortcode', 'list_event_shortcode' ) );

		add_shortcode( 'list-events', array( 'BrownPaperTickets\Modules\EventList\Shortcode', 'list_event_shortcode' ) );
		add_shortcode( 'list_events', array( 'BrownPaperTickets\Modules\EventList\Shortcode', 'list_event_shortcode' ) );
	}

	public function register_settings() {

		// Event Settings
		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_location_after_description'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_full_description'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'sort_events'
		);


		// Date Settings
		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_dates'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'date_format'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'time_format'
		);

		// custom_date_field is registered but it doesn't have a settings filed added.
		// That is added manually in the settings-fields.
		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'custom_date_format'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'custom_time_format'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_sold_out_dates'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_past_dates'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_end_time'
		);

		// Price Settings
		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_prices'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'shipping_methods'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'shipping_countries'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'currency'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'price_sort'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'show_sold_out_prices'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'include_service_fee'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'price_max_quantity'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'hidden_prices'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'price_intervals'
		);

		register_setting(
			self::$menu_slug,
			self::$setting_prefix . 'price_include_fee'
		);
	}

	public function register_sections() {

		$input = new EventList\Inputs;

		add_settings_section( self::$event_section_title, self::$event_section_title, null,
		self::$menu_slug . self::$section_suffix );
		add_settings_section( self::$date_section_title, self::$date_section_title, null,
		self::$menu_slug . self::$section_suffix );
		add_settings_section( self::$price_section_title, self::$price_section_title, null,
		self::$menu_slug . self::$section_suffix );
		add_settings_section( self::$hidden_prices_section_title, self::$hidden_prices_section_title, null,
		self::$menu_slug . self::$section_suffix );

		// Add the settings fields.
		// Event Fields

		add_settings_field(
			self::$setting_prefix . 'show_full_description',
			'Display Full Description by Default',
			array( $input, 'show_full_description' ),
			self::$menu_slug . self::$section_suffix, self::$event_section_title );

		add_settings_field(
			self::$setting_prefix . 'show_location_after_description',
			'Display Location After Description',
			array( $input, 'show_location_after_description' ),
			self::$menu_slug . self::$section_suffix, self::$event_section_title );

		add_settings_field(
			self::$setting_prefix . 'sort_events',
			'Sort Events',
			array( $input, 'sort_events' ),
			self::$menu_slug . self::$section_suffix, self::$event_section_title );

		// Date Fields

		add_settings_field(
			self::$setting_prefix . 'show_dates',
			'Display Dates',
			array( $input, 'show_dates' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'show_past_dates',
			'Display Past Dates',
			array( $input, 'show_past_dates' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'show_end_time',
			'Display Event End Time',
			array( $input, 'show_end_time' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'show_sold_out_dates',
			'Display Sold Out Dates',
			array( $input, 'show_sold_out_dates' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'date_format',
			'Date Format',
			array( $input, 'date_format' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'time_format',
			'Time Format',
			array( $input, 'time_format' ),
			self::$menu_slug . self::$section_suffix, self::$date_section_title
		);

		// Price Fields
		add_settings_field(
			self::$setting_prefix . 'show_prices',
			'Display Prices',
			array( $input, 'show_prices' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'show_sold_out_prices',
			'Display Sold Out Prices',
			array( $input, 'show_sold_out_prices' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'shipping_methods',
			'Shipping Methods',
			array( $input, 'shipping_methods' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'shipping_countries',
			'Default Shipping Country',
			array( $input, 'shipping_countries' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'currency',
			'Currency',
			array( $input, 'currency' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'price_sort',
			'Price Sort',
			array( $input, 'price_sort' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'include_service_fee',
			'Include Service Fee',
			array( $input, 'include_service_fee' ),
			self::$menu_slug . self::$section_suffix, self::$price_section_title
		);

		add_settings_field(
			self::$setting_prefix . 'hidden_prices',
			'Hidden Prices',
			array( $input, 'hidden_prices' ),
			self::$menu_slug . self::$section_suffix, self::$hidden_prices_section_title
		);

	}

	public function load_public_js( $hook ) {

	}

	public function load_public_css( $hook ) {

	}

	public function set_default_setting_values() {

		update_option( self::$setting_prefix . 'show_full_description', 'false' );
		update_option( self::$setting_prefix . 'show_location_after_description', 'false' );

		// Date Settings
		update_option( self::$setting_prefix . 'show_dates', 'true' );
		update_option( self::$setting_prefix . 'date_format', 'MMMM Do, YYYY' );
		update_option( self::$setting_prefix . 'time_format', 'hh:mm A' );
		update_option( self::$setting_prefix . 'show_sold_out_dates', 'false' );
		update_option( self::$setting_prefix . 'show_past_dates', 'false' );
		update_option( self::$setting_prefix . 'show_end_time', 'true' );

		// Price Settings
		update_option( self::$setting_prefix . 'show_prices', 'true' );
		update_option( self::$setting_prefix . 'shipping_methods', array( 'print_at_home', 'will_call' ) );
		update_option( self::$setting_prefix . 'shipping_countries', 'United States' );
		update_option( self::$setting_prefix . 'currency', 'usd' );
		update_option( self::$setting_prefix . 'price_sort', 'value_asc' );
		update_option( self::$setting_prefix . 'show_sold_out_prices', 'false' );
		update_option( self::$setting_prefix . 'include_service_fee', 'false' );

		update_option( self::$setting_prefix . 'hidden_prices', array() );

		update_option( self::$setting_prefix . 'price_max_quantity', array() );
		update_option( self::$setting_prefix . 'price_intervals', array() );
		update_option( self::$setting_prefix . 'price_include_fee', array() );
	}

	public function load_admin_ajax_actions() {
		add_action(
			'wp_ajax_bpt_set_price_max_quantity',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'set_price_max_quantity' )
		);

		add_action(
			'wp_ajax_bpt_hide_prices',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'hide_prices' )
		);

		add_action(
			'wp_ajax_bpt_unhide_prices',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'unhide_prices' )
		);

		add_action(
			'wp_ajax_bpt_get_events',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'get_events' )
		);

		add_action(
			'wp_ajax_bpt_set_price_intervals',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'set_price_intervals' )
		);

		add_action(
			'wp_ajax_bpt_set_price_include_fee',
			array( 'BrownPaperTickets\Modules\EventList\Ajax', 'set_price_include_fee' )
		);
	}

	public function load_public_ajax_actions() {
		add_action(
			'wp_ajax_nopriv_bpt_get_events', array( 'BrownPaperTickets\Modules\EventList\Ajax', 'get_events' ) );
	}
}
