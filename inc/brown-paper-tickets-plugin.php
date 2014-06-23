<?php
/**
 * Brown Paper Tickets
 */

namespace BrownPaperTickets;

const VERSION = '0.1.2';

const PLUGIN_SLUG = 'brown_paper_tickets';

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-settings-fields.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-ajax.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-widgets.php' );

use BrownPaperTickets\BPTSettingsFields;
use BrownPaperTickets\BPTAjaxActions;
use BrownPaperTickets\BPTWidgets;

class BPTPlugin {

	protected $dev_id;
	protected $client_id;
	protected $settings_fields;
	protected static $menu_slug;
	protected static $plugin_slug;
	protected static $plugin_version;
	protected static $instance = null;

	public function __construct() {

		$this->dev_id = get_option( '_bpt_dev_id' );
		$this->client_id = get_option( '_bpt_client_id' );
		$this->settings_fields = new BPTSettingsFields;

		self::$menu_slug = PLUGIN_SLUG.'_settings';

		self::$plugin_slug = PLUGIN_SLUG;

		self::$plugin_version = VERSION;

		$this->load_shared();
		$this->load_public();
		
		if ( is_admin() ) {
			$this->load_admin();
		}

	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function get_plugin_slug() {
		return self::$plugin_slug;
	}

	public static function get_plugin_version() {
		return self::$plugin_version;
	}

	public static function get_menu_slug() {
		return self::$menu_slug;
	}

	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		add_option( '_bpt_show_wizard', 'true' );

		self::set_default_event_option_values();
	}

	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// Important: Check if the file is the one
		// that was registered during the uninstall hook.
		if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
			return;
		}

	}

	/**
	 * This function is not in use.
	 */
	public static function uninstall() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		check_admin_referer( 'delete-selected' );

		// Important: Check if the file is the one
		// that was registered during the uninstall hook.
		if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
			return;
		}

		self::delete_bpt_options();
	}

	public function load_admin() {
		add_action( 'admin_init',array( $this, 'bpt_show_wizard' ) );
		add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

		add_action( 'wp_ajax_bpt_get_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_events' ) );
		add_action( 'wp_ajax_bpt_get_account', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_account' ) );
		add_action( 'wp_ajax_bpt_get_calendar_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_calendar_events' ) );

		add_action( 'wp_ajax_bpt_delete_cache', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_delete_cache' ) );
		add_action( 'wp_ajax_bpt_account_test', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_account_test' ) );
	}

	public function load_public() {
		add_shortcode( 'list-event', array( $this, 'list_event_shortcode' ) );
		add_shortcode( 'list_event', array( $this, 'list_event_shortcode' ) );

		add_shortcode( 'list-events', array( $this, 'list_event_shortcode' ) );
		add_shortcode( 'list_events', array( $this, 'list_event_shortcode' ) );

		add_shortcode( 'event-calendar', array( $this, 'event_calendar_shortcode' ) );
		add_shortcode( 'event_calendar', array( $this, 'event_calendar_shortcode' ) );

		add_action( 'wp_ajax_nopriv_bpt_get_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_events' ) );
		add_action( 'wp_ajax_nopriv_bpt_get_account', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_account' ) );
		add_action( 'wp_ajax_nopriv_bpt_get_calendar_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_calendar_events' ) );


		
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );
	}

	public function load_shared() {

		add_action(
			'widgets_init', function() {
				register_widget( 'BrownPaperTickets\BPTCalendarWidget' );
			}
		);

		add_action(
			'widgets_init', function(){
				register_widget( 'BrownPaperTickets\BPTEventListWidget' );
			}
		);
		
	}

	public function load_admin_scripts( $hook ) {

		if ( $hook === 'toplevel_page_brown_paper_tickets_settings' ) {

			self::load_ajax_required();

			wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, VERSION );

			wp_enqueue_script( 'bpt_admin_js', plugins_url( '/admin/assets/js/bpt-admin.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );

			wp_localize_script(
				'bpt_admin_js', 'bptWP', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'bptNonce' => wp_create_nonce( 'bpt-admin-nonce' ),
				)
			);
		}
		
		if ( $hook === 'admin_page_brown_paper_tickets_settings_setup_wizard' ) {

			self::load_ajax_required();
			
			wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, VERSION );

			wp_enqueue_style( 'bpt_setup_wizard_css', plugins_url( '/admin/assets/css/bpt-setup-wizard.css', dirname( __FILE__ ) ), false, VERSION );
			
			wp_enqueue_script( 'bpt_setup_wizard_js', plugins_url( '/admin/assets/js/bpt-setup-wizard.js', dirname( __FILE__ ) ), array( 'jquery' ), true ); 
			
			wp_localize_script(
				'bpt_setup_wizard_js', 'bptSetupWizardAjax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'bptSetupWizardNonce' => wp_create_nonce( 'bpt-setup-wizard-nonce' ),
				)
			);
		}
	}

	public static function load_ajax_required() {

		// Include Ractive Templates
		wp_enqueue_script( 'ractive_js', plugins_url( '/public/assets/js/ractive.js', dirname( __FILE__ ) ), array(), false, true );
		wp_enqueue_script( 'ractive_transitions_slide_js', plugins_url( '/public/assets/js/ractive-transitions-slide.js', dirname( __FILE__ ) ), array(), false, true );
		wp_enqueue_script( 'moment_with_langs_min', plugins_url( '/public/assets/js/moment-with-langs.min.js', dirname( __FILE__ ) ), array(), false, true );

	}

	public function load_public_scripts() {


	}

	public function create_bpt_settings() {

		add_menu_page(
			'Brown Paper Tickets',
			'BPT Settings',
			'administrator',
			self::$menu_slug,
			array( $this, 'render_bpt_options_page' ),
			'dashicons-tickets'
		);

		add_submenu_page(
			null,  //or 'options.php'
			'BPT Setup Wizard',
			'BPT Setup Wizard',
			'manage_options',
			self::$menu_slug . '_setup_wizard',
			array( $this, 'render_bpt_setup_wizard_page' )
		);


		$this->register_bpt_general_settings();
		$this->register_bpt_api_settings();
		$this->register_bpt_event_list_settings();
		$this->register_bpt_purchase_settings();
	}

	public function bpt_show_wizard() {

		if ( get_option( '_bpt_show_wizard' ) === 'true' ) {

			update_option( '_bpt_show_wizard', 'false' );

			if ( ! isset( $_GET['activate-multi'] ) ) {

				wp_redirect( 'admin.php?page=brown_paper_tickets_settings_setup_wizard' );

			}
		}
	}

	public function register_bpt_general_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_general';
		$section_title  = 'General Settings';

		register_setting( self::$menu_slug, $setting_prefix . 'show_wizard' );
		register_setting( self::$menu_slug, $setting_prefix . 'cache_time' );
		register_setting( self::$menu_slug, $setting_prefix . 'cache_unit' );

		// Register the cached_data array. This is to keep track of the data we have cached.
		register_setting( self::$menu_slug, $setting_prefix . 'cached_data' );

		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );

		add_settings_field( $setting_prefix . 'cache_time', 'Cache Settings', array( $this->settings_fields, 'get_cache_time_input' ), self::$menu_slug . $section_suffix, $section_title );
		
	}

	public function register_bpt_event_list_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_event';
		$section_title  = 'Event Display Settings';
		$date_section_title  = 'Date Display Settings';
		$price_section_title = 'Price Display Settings';

		// Event Settings
		register_setting( self::$menu_slug, $setting_prefix . 'show_location_after_description' );
		register_setting( self::$menu_slug, $setting_prefix . 'show_full_description' );

		// Date Settings
		register_setting( self::$menu_slug, $setting_prefix . 'show_dates' );
		register_setting( self::$menu_slug, $setting_prefix . 'date_format' );
		register_setting( self::$menu_slug, $setting_prefix . 'time_format' );
		// custom_date_field is registered but it doesn't have a settings filed added.
		// That is added manually in the settings-fields.
		register_setting( self::$menu_slug, $setting_prefix . 'custom_date_format' );
		register_setting( self::$menu_slug, $setting_prefix . 'custom_time_format' );
		register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_dates' );
		register_setting( self::$menu_slug, $setting_prefix . 'show_past_dates' );
		register_setting( self::$menu_slug, $setting_prefix . 'show_end_time' );

		// Price Settings
		register_setting( self::$menu_slug, $setting_prefix . 'show_prices' );
		register_setting( self::$menu_slug, $setting_prefix . 'shipping_methods' );
		register_setting( self::$menu_slug, $setting_prefix . 'shipping_countries' );
		register_setting( self::$menu_slug, $setting_prefix . 'currency' );
		register_setting( self::$menu_slug, $setting_prefix . 'price_sort' );
		register_setting( self::$menu_slug, $setting_prefix . 'show_sold_out_prices' );


		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );
		add_settings_section( $date_section_title, $date_section_title, null, self::$menu_slug . $section_suffix );
		add_settings_section( $price_section_title, $price_section_title, null, self::$menu_slug . $section_suffix );


		// Add the settings fields.
		// Event Fields
		add_settings_field( $setting_prefix . 'show_full_description', 'Display Full Description by Default', array( $this->settings_fields, 'get_show_full_description_input' ), self::$menu_slug . $section_suffix, $section_title );
		add_settings_field( $setting_prefix . 'how_location_after_description', 'Display Location After Description', array( $this->settings_fields, 'get_show_location_after_description_input' ), self::$menu_slug . $section_suffix, $section_title );
		
		// Date Fields
		add_settings_field( $setting_prefix . 'show_dates', 'Display Dates', array( $this->settings_fields, 'get_show_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
		add_settings_field( $setting_prefix . 'show_past_dates', 'Display Past Dates', array( $this->settings_fields, 'get_show_past_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
		add_settings_field( $setting_prefix . 'show_end_time', 'Display Event End Time', array( $this->settings_fields, 'get_show_end_time_input' ), self::$menu_slug . $section_suffix, $date_section_title );
		add_settings_field( $setting_prefix . 'show_sold_out_dates', 'Display Sold Out Dates', array( $this->settings_fields, 'get_show_sold_out_dates_input' ), self::$menu_slug . $section_suffix, $date_section_title );
		add_settings_field( $setting_prefix . 'date_format', 'Date Format', array( $this->settings_fields, 'get_date_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );
		add_settings_field( $setting_prefix . 'time_format', 'Time Format', array( $this->settings_fields, 'get_time_format_input' ), self::$menu_slug . $section_suffix, $date_section_title );

		// Price Fields
		add_settings_field( $setting_prefix . 'show_prices', 'Display Prices', array( $this->settings_fields, 'get_show_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
		add_settings_field( $setting_prefix . 'show_sold_out_prices', 'Display Sold Out Prices', array( $this->settings_fields, 'get_show_sold_out_prices_input' ), self::$menu_slug . $section_suffix, $price_section_title );
		add_settings_field( $setting_prefix . 'shipping_methods', 'Shipping Methods', array( $this->settings_fields, 'get_shipping_methods_input' ), self::$menu_slug . $section_suffix, $price_section_title );
		add_settings_field( $setting_prefix . 'shipping_countries', 'Default Shipping Country', array( $this->settings_fields, 'get_shipping_countries_input' ), self::$menu_slug . $section_suffix, $price_section_title );
		add_settings_field( $setting_prefix . 'currency', 'Currency', array( $this->settings_fields, 'get_currency_input' ), self::$menu_slug . $section_suffix, $price_section_title );
		add_settings_field( $setting_prefix . 'price_sort', 'Price Sort', array( $this->settings_fields, 'get_price_sort_input' ), self::$menu_slug . $section_suffix, $price_section_title );
	}

	/**
	 * Set the Default Values
	 */
	
	private static function set_default_general_option_values() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_general';

		add_option( self::$menu_slug . $setting_prefix . '_bpt_cache_time' );
	}

	private static function set_default_event_option_values() {

		$setting_prefix = '_bpt_';
		$section_suffix = '_event';
		$section_title = 'Event Display Settings';
		$date_section_title  = 'Date Display Settings';
		$price_section_title = 'Price Display Settings';

		add_option( self::$menu_slug . $setting_prefix . 'show_full_description', 'false' );
		add_option( self::$menu_slug . $setting_prefix . 'show_location_after_description', 'false' );

		// Date Settings
		add_option( self::$menu_slug . $setting_prefix . 'show_dates', 'true' );
		add_option( self::$menu_slug . $setting_prefix . 'date_format', 'MMMM Do, YYYY' );
		add_option( self::$menu_slug . $setting_prefix . 'time_format', 'hh:mm A' );

		add_option( self::$menu_slug . $setting_prefix . 'show_sold_out_dates', 'false' );
		add_option( self::$menu_slug . $setting_prefix . 'show_past_dates', 'false' );
		add_option( self::$menu_slug . $setting_prefix . 'show_end_time', 'true' );

		// Price Settings
		add_option( self::$menu_slug . $setting_prefix . 'show_prices', 'true' );
		add_option( self::$menu_slug . $setting_prefix . 'shipping_methods', array( 'print_at_home', 'will_call' ) );
		add_option( self::$menu_slug . $setting_prefix . 'shipping_countries', 'United States' );
		add_option( self::$menu_slug . $setting_prefix . 'currency', 'usd' );
		add_option( self::$menu_slug . $setting_prefix . 'price_sort', 'value_asc' );
		add_option( self::$menu_slug . $setting_prefix . 'show_sold_out_prices', 'false' );
	}

	private static function remove_general_options() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_general';

		delete_option( self::$menu_slug . $setting_prefix . '_bpt_cache_time' );
	}

	private static function remove_event_options() {

		$setting_prefix = '_bpt_';
		$section_suffix = '_event';
		$section_title = 'Event Display Settings';
		$date_section_title  = 'Date Display Settings';
		$price_section_title = 'Price Display Settings';

		delete_option( self::$menu_slug . $setting_prefix . 'show_full_description' );
		delete_option( self::$menu_slug . $setting_prefix . 'show_location_after_description' );

		// Date Settings
		delete_option( self::$menu_slug . $setting_prefix . 'show_dates' );
		delete_option( self::$menu_slug . $setting_prefix . 'date_format' );
		delete_option( self::$menu_slug . $setting_prefix . 'time_format' );

		delete_option( self::$menu_slug . $setting_prefix . 'show_sold_out_dates' );
		delete_option( self::$menu_slug . $setting_prefix . 'show_past_dates' );
		delete_option( self::$menu_slug . $setting_prefix . 'show_end_time' );

		// Price Settings
		delete_option( self::$menu_slug . $setting_prefix . 'show_prices' );
		delete_option( self::$menu_slug . $setting_prefix . 'shipping_methods' );
		delete_option( self::$menu_slug . $setting_prefix . 'shipping_countries' );
		delete_option( self::$menu_slug . $setting_prefix . 'currency' );
		delete_option( self::$menu_slug . $setting_prefix . 'price_sort' );
		delete_option( self::$menu_slug . $setting_prefix . 'show_sold_out_prices' );
	}

	private static function delete_bpt_options() {

		global $wpdb;

		$bpt_options = $wpdb->get_results(
			'SELECT *
			FROM `wp_options`
			WHERE `option_name` LIKE \'%_bpt_%\'',
			OBJECT
		);

		if ( ! empty( $bpt_options ) ) {

			foreach ( $bpt_options as $bpt_option ) {
				
				$option_name = $bpt_option->option_name;

				delete_option( $option_name );
			}
		}
	}


	/**
	 * Register the API Credential Settings Fields
	 *
	 * Set the $section title variable to what you want the 
	 */
	public function register_bpt_api_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_api';
		$section_title  = 'API Credentials';

		register_setting( self::$menu_slug, $setting_prefix . 'dev_id' );
		register_setting( self::$menu_slug, $setting_prefix . 'client_id' );

		add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );

		add_settings_field( $setting_prefix . 'dev_id', 'Developer ID', array( $this->settings_fields, 'get_developer_id_input' ), self::$menu_slug . $section_suffix, $section_title );
		add_settings_field( $setting_prefix . 'client_id', 'Client ID', array( $this->settings_fields, 'get_client_id_input' ), self::$menu_slug . $section_suffix, $section_title );
	}

	public function register_bpt_calendar_settings() {
		$setting_prefix = '_bpt_';
		$section_prefix = '_calendar';
		$section_title  = 'Calendar Settings';

	}

	public function register_bpt_purchase_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_purchase';
		$section_title  = 'Ticket Purchase Settings';

		register_setting( self::$menu_slug, $setting_prefix . 'allow_purchase' );

		add_settings_section( $section_title, $section_title, array( $this, 'render_bpt_options_page' ), self::$menu_slug . $section_suffix );

		add_settings_field( $setting_prefix . 'allow_purchase', 'Allow Purchase from Within Event List', array( $this->settings_fields, 'get_allow_purchase_input' ), self::$menu_slug . $section_suffix, $section_title );

	}

	public function list_event_shortcode( $atts ) {

		global $post;

		if ( is_home() ||
				is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list-events' ) || 
				is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list_events' ) ||
				is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list-event' ) ||
				is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'list_event' )
			) {

			$event_list_attributes = shortcode_atts(
				array(
					'event_id' => null,
					'client_id' => null,
					'event-id' => null,
					'client-id' => null,
				),
				$atts
			);

			$localized_variables = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'bptNonce' => wp_create_nonce( 'bpt-event-list-nonce' ),
				'postID' => $post->ID,
			);

			if ( $event_list_attributes['event_id'] ) {
				$localized_variables['eventID'] = $event_list_attributes['event_id'];
			}

			if ( $event_list_attributes['event-id'] ) {
				$localized_variables['eventID'] = $event_list_attributes['event-id'];
			}

			if ( $event_list_attributes['client-id'] ) {
				$localized_variables['clientID'] = $event_list_attributes['client-id'];
			}

			if ( $event_list_attributes['client_id'] ) {
				$localized_variables['clientID'] = $event_list_attributes['client_id'];
			}


			wp_enqueue_style( 'bpt_event_list_css', plugins_url( '/public/assets/css/bpt-event-list-shortcode.css', dirname( __FILE__ ) ), array(), VERSION );

			self::load_ajax_required();

			wp_enqueue_script( 'event_feed_js_' . $post->ID, plugins_url( '/public/assets/js/event-feed.js.php?post_id=' . $post->ID, dirname( __FILE__ ) ), array( 'jquery', 'underscore' ), null, true );

			wp_localize_script(
				'event_feed_js_' . $post->ID,
				'bptEventFeedAjaxPost' . $post->ID,
				$localized_variables
			);

		}

		require( plugin_dir_path( __FILE__ ) . '../public/event-list-shortcode.php' );
	}

	public function event_calendar_shortcode( $atts ) {

		$calendar_attributes = shortcode_atts(
			array(
				'client_id' => null,
				'title' => null,
			),
			$atts
		);

		$calendar_instance = array();

		if ( $calendar_attributes['client_id'] ) {
			
			$client_id = $calendar_attributes['client_id'];
			$title     = $calendar_attributes['title'];

			$calendar_instance = array(
				'client_id' => $client_id,
				'display_type' => 'producers_events',
				'title' => '',
			);
		}

		$calendar_args = array(
			'widget_id' => 'shortcode',
		);

		the_widget( 'BrownPaperTickets\BPTCalendarWidget', $calendar_instance, $calendar_args );
	}

	public function render_bpt_options_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-settings.php' );
	}

	public function render_bpt_setup_wizard_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-setup-wizard.php' );
	}

}