<?php
/**
 * Brown Paper Tickets
 */

namespace BrownPaperTickets;

const VERSION = '0.3.1';

const PLUGIN_SLUG = 'brown_paper_tickets';


require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-settings-fields.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-ajax.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-widgets.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/modules/appearance/appearance.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/modules/purchase/purchase.php' );
require_once( plugin_dir_path( __FILE__ ).'../inc/modules/event-list/event-list.php' );

use BrownPaperTickets\BPTSettingsFields;
use BrownPaperTickets\BPTAjaxActions;
use BrownPaperTickets\BPTWidgets;

class BPTPlugin {

	protected $settings_fields;
	static $menu_slug;
	protected static $plugin_slug;
	protected static $plugin_version;
	protected static $instance = null;

	protected static $appearance_settings;
	protected static $purchase_settings;
	protected static $event_list_settings;

	public function __construct() {

		$this->settings_fields = new BPTSettingsFields;

		self::$menu_slug = PLUGIN_SLUG.'_settings';

		self::$plugin_slug = PLUGIN_SLUG;

		self::$plugin_version = VERSION;

		$this->load_shared();
		$this->load_public();

		if ( is_admin() ) {
			$this->load_admin();
		}

		self::$appearance_settings = new Modules\Appearance;
		self::$purchase_settings = new Modules\Purchase;
		self::$event_list_settings = new Modules\EventList;
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

		if ( ! get_option( '_bpt_dev_id' ) && ! get_option( '_bpt_client_id' ) ) {
			update_option( '_bpt_show_wizard', 'true' );
			self::set_default_calendar_option_values();
			self::set_default_password_prices_values();

			$appearance_settings = new Modules\Appearance;
			$purchase_settings = new Modules\Purchase;
			$event_list_settings = new Modules\EventList;
			
			$appearance_settings->activate();
			$purchase_settings->activate();
			$event_list_settings->activate();
		}
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
		add_action( 'admin_init', array( $this, 'bpt_show_wizard' ) );
		add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

		add_action( 'wp_ajax_bpt_get_account', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_account' ) );
		add_action( 'wp_ajax_bpt_get_calendar_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_calendar_events' ) );

		add_action( 'wp_ajax_bpt_delete_cache', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_delete_cache' ) );
		add_action( 'wp_ajax_bpt_account_test', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_account_test' ) );
	}

	public function load_public() {

		add_shortcode( 'event-calendar', array( $this, 'event_calendar_shortcode' ) );
		add_shortcode( 'event_calendar', array( $this, 'event_calendar_shortcode' ) );

		add_action( 'wp_ajax_nopriv_bpt_get_account', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_account' ) );
		add_action( 'wp_ajax_nopriv_bpt_get_calendar_events', array( 'BrownPaperTickets\BPTAjaxActions', 'bpt_get_calendar_events' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );
	}

	public function load_shared() {
		add_action( 'init', array( $this, 'register_js_libs' ) );

		add_action(
			'widgets_init', function() {
				register_widget( 'BrownPaperTickets\BPTCalendarWidget' );
			}
		);

		// add_action(
		// 	'widgets_init', function(){
		// 		register_widget( 'BrownPaperTickets\BPTEventListWidget' );
		// 	}
		// );

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

	public static function register_js_libs()
	{
		wp_register_script( 'ractive_js', plugins_url( '/public/assets/js/lib/ractive.min.js', dirname( __FILE__ ) ), array(), false, true );
		wp_register_script( 'ractive_transitions_slide_js', plugins_url( '/public/assets/js/lib/ractive-transitions-slide.js', dirname( __FILE__ ) ), array( 'ractive_js' ), false, true );
		wp_register_script( 'moment_with_langs_min', plugins_url( '/public/assets/js/lib/moment-with-langs.min.js', dirname( __FILE__ ) ), array(), false, true );
		wp_register_script( 'clndr_min_js', plugins_url( 'public/assets/js/lib/clndr.min.js', dirname( __FILE__ ) ), array( 'underscore', 'jquery' ), false, true );
	}

	public static function load_ajax_required() {
		// Include Ractive Templates
		wp_enqueue_script( 'ractive_js' );
		wp_enqueue_script( 'ractive_transitions_slide_js' );
		wp_enqueue_script( 'moment_with_langs_min' );
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
		// Transitioning to the more modular approach.
		// $this->register_bpt_event_list_settings();
		$this->register_bpt_calendar_settings();
		$this->register_bpt_password_prices_settings();

		self::$appearance_settings->load_settings();
		self::$purchase_settings->load_settings();
		self::$event_list_settings->load_settings();
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

	}

	public function register_bpt_calendar_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_calendar';
		$section_title  = 'Calendar Settings';

		register_setting( self::$menu_slug, $setting_prefix . 'show_upcoming_events_calendar' );

		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );

		add_settings_field( $setting_prefix . 'show_upcoming_events_calendar', 'Display Upcoming Events in Calendar', array( $this->settings_fields, 'get_show_upcoming_events_calendar_input' ), self::$menu_slug . $section_suffix, $section_title );

	}

	public function register_bpt_password_prices_settings() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_password_prices';
		$section_title  = 'Password Protected Prices';

		register_setting( self::$menu_slug, $setting_prefix . 'hidden_prices' );
		add_settings_section( $section_title, $section_title, null, self::$menu_slug . $section_suffix );
		add_settings_field( $setting_prefix . 'hidden_prices', 'Hidden Prices', array( $this->settings_fields, 'get_hidden_prices_input' ), self::$menu_slug . $section_suffix, $section_title );

	}

	/**
	 * Register the API Credential Settings Fields
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

	public function load_appearance_settings() {
		require_once( plugin_dir_path( __FILE__ ).'../inc/settings-fields/appearance/appearance.php' );



	}

	public function load_settings() {

	}

	/**
	 * Set the Default Values
	 */

	private static function set_default_general_option_values() {
		$setting_prefix = '_bpt_';
		$section_suffix = '_general';

		update_option( $setting_prefix . '_bpt_cache_time' );
	}

	private static function set_default_event_option_values() {

		$setting_prefix = '_bpt_';
		$section_suffix = '_event';
		$section_title = 'Event Display Settings';
		$date_section_title  = 'Date Display Settings';
		$price_section_title = 'Price Display Settings';

		update_option( $setting_prefix . 'show_full_description', 'false' );
		update_option( $setting_prefix . 'show_location_after_description', 'false' );

		// Date Settings
		update_option( $setting_prefix . 'show_dates', 'true' );
		update_option( $setting_prefix . 'date_format', 'MMMM Do, YYYY' );
		update_option( $setting_prefix . 'time_format', 'hh:mm A' );

		update_option( $setting_prefix . 'show_sold_out_dates', 'false' );
		update_option( $setting_prefix . 'show_past_dates', 'false' );
		update_option( $setting_prefix . 'show_end_time', 'true' );

		// Price Settings
		update_option( $setting_prefix . 'show_prices', 'true' );
		update_option( $setting_prefix . 'shipping_methods', array( 'print_at_home', 'will_call' ) );
		update_option( $setting_prefix . 'shipping_countries', 'United States' );
		update_option( $setting_prefix . 'currency', 'usd' );
		update_option( $setting_prefix . 'price_sort', 'value_asc' );
		update_option( $setting_prefix . 'show_sold_out_prices', 'false' );
	}

	private static function set_default_calendar_option_values() {
		$setting_prefix = '_bpt_';
		update_option( $setting_prefix . 'show_upcoming_events_calendar', 'false' );
	}

	private static function set_default_password_prices_values() {
		$setting_prefix = '_bpt_';
		update_option( $setting_prefix . 'hidden_prices', array() );
	}

	/**
	 * Delete all _bpt_ options directly from the database.
	 */
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

	public function event_calendar_shortcode( $atts ) {

		$calendar_attributes = shortcode_atts(
			array(
				'client_id' => '',
				'title' => '',
			),
			$atts
		);

		$calendar_instance = array();
		$title = $calendar_attributes['title'];
		$calendar_instance['title'] = $title;

		if ( $calendar_attributes['client_id'] ) {

			$client_id = $calendar_attributes['client_id'];
			$calendar_instance = array(
				'client_id' => $client_id,
				'display_type' => 'producers_events',
				'title' => $title,
			);
		}

		$calendar_args = array(
			'widget_id' => 'shortcode',
		);

		ob_start();

		the_widget( 'BrownPaperTickets\BPTCalendarWidget', $calendar_instance, $calendar_args );

		return ob_get_clean();
	}

	public function render_bpt_options_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-settings.php' );
	}

	public function render_bpt_setup_wizard_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-setup-wizard.php' );
	}

}