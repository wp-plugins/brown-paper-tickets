<?php
/**
 * Brown Paper Tickets
 */

namespace BrownPaperTickets;


require_once( plugin_dir_path( __FILE__ ).'../lib/bptWordpress.php' );

/**
 * Load Modules
 */
require_once( plugin_dir_path( __FILE__ ).'../src/modules/general/general.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/account/account.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/appearance/appearance.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/purchase/purchase.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/event-list/event-list.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/calendar/calendar.php' );
require_once( plugin_dir_path( __FILE__ ).'../src/modules/help/help.php' );

use BrownPaperTickets\BPTSettingsFields;
use BrownPaperTickets\BPTAjaxActions;
use BrownPaperTickets\BPTWidgets;
use BrownPaperTickets\BptWordpress as Utilities;


const BPT_VERSION = '0.4.1';
const PLUGIN_SLUG = 'brown_paper_tickets';

class BPTPlugin {

	protected $settings_fields;
	static $menu_slug;
	protected static $plugin_slug;
	protected static $plugin_version;
	protected static $instance = null;

	protected static $calendar;
	protected static $general;
	protected static $account;
	protected static $appearance;
	protected static $purcahse;
	protected static $event_list;
	protected static $help;

	public function __construct() {

		self::$menu_slug = PLUGIN_SLUG . '_settings';

		self::$plugin_slug = PLUGIN_SLUG;

		self::$plugin_version = BPT_VERSION;

		$this->load_shared();

		if ( is_admin() ) {
			$this->load_admin();
		}

		self::$calendar = new Modules\Calendar;
		self::$general = new Modules\General;
		self::$account = new Modules\Account;
		self::$appearance = new Modules\Appearance;
		self::$purcahse = new Modules\Purchase;
		self::$event_list = new Modules\EventList;
		self::$help = new Modules\Help;

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

		if ( version_compare( PHP_VERSION, '5.3', '<' ) && is_admin() ) {
			exit('Sorry, the Brown Paper Tickets plugin requires PHP version 5.3 or higher but you are using '. PHP_VERSION . '. Please contact your hosting provider for more info. ');
		}

		if ( ! get_option( '_bpt_dev_id' ) && ! get_option( '_bpt_client_id' ) ) {
			update_option( '_bpt_show_wizard', 'true' );

			$account = new Modules\Account;
			$appearance = new Modules\Appearance;
			$calendar = new Modules\Calendar;
			$purchase = new Modules\Purchase;
			$event_list = new Modules\EventList;
			$help = new Modules\Help;

			$calendar->activate();
			$account->activate();
			$appearance->activate();
			$purchase->activate();
			$event_list->activate();
			$help->activate();
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

		self::delete_options();
	}

	public function load_admin() {
		add_action( 'admin_init', array( $this, 'bpt_show_wizard' ) );
		add_action( 'admin_menu', array( $this, 'create_bpt_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

	}

	public function load_shared() {
		add_action( 'init', array( $this, 'register_js_libs' ) );
	}

	public function load_admin_scripts( $hook ) {

		if ( $hook === 'toplevel_page_brown_paper_tickets_settings' ) {

			wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, BPT_VERSION );

			wp_enqueue_script(
				'bpt_admin_js',
				plugins_url( '/admin/assets/js/bpt-admin.js', dirname( __FILE__ ) ),
				array(
					'jquery',
					'ractive_js',
					'ractive_transitions_slide_js',
					'moment_with_langs_min',
				),
				true,
				true
			);

			wp_localize_script(
				'bpt_admin_js', 'bptWP', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'bpt-admin-nonce' ),
				)
			);
		}

		if ( $hook === 'admin_page_brown_paper_tickets_settings_setup_wizard' ) {

			wp_enqueue_style( 'bpt_admin_css', plugins_url( '/admin/assets/css/bpt-admin.css', dirname( __FILE__ ) ), false, BPT_VERSION );

			wp_enqueue_style( 'bpt_setup_wizard_css', plugins_url( '/admin/assets/css/bpt-setup-wizard.css', dirname( __FILE__ ) ), false, BPT_VERSION );

			wp_enqueue_script(
				'bpt_setup_wizard_js',
				plugins_url( '/admin/assets/js/bpt-setup-wizard.js', dirname( __FILE__ ) ),
				array(
					'jquery',
					'ractive_js',
					'ractive_transitions_slide_js',
					'moment_with_langs_min',
				),
				true,
				true
			);

			wp_localize_script(
				'bpt_setup_wizard_js', 'bptSetupWizardAjax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'bpt-admin-nonce' ),
				)
			);
		}
	}

	public static function register_js_libs()
	{
		wp_register_script( 'ractive_js', plugins_url( '/public/assets/js/lib/ractive.min.js', dirname( __FILE__ ) ), array(), false, true );
		wp_register_script( 'ractive_transitions_slide_js', plugins_url( '/public/assets/js/lib/ractive-transitions-slide.js', dirname( __FILE__ ) ), array( 'ractive_js' ), false, true );
		wp_register_script( 'ractive_transitions_fade_js', plugins_url( '/public/assets/js/lib/ractive-transitions-fade.js', dirname( __FILE__ ) ), array( 'ractive_js' ), false, true );
		wp_register_script( 'moment_with_langs_min', plugins_url( '/public/assets/js/lib/moment-with-langs.min.js', dirname( __FILE__ ) ), array(), false, true );
		wp_register_script( 'clndr_min_js', plugins_url( 'public/assets/js/lib/clndr.min.js', dirname( __FILE__ ) ), array( 'underscore', 'jquery' ), false, true );
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

		self::$general->load_settings();
		self::$account->load_settings();
		self::$appearance->load_settings();
		self::$purcahse->load_settings();
		self::$event_list->load_settings();
		self::$help->load_settings();
		self::$calendar->load_settings();
	}

	public function bpt_show_wizard() {

		if ( get_option( '_bpt_show_wizard' ) === 'true' ) {

			update_option( '_bpt_show_wizard', 'false' );

			if ( ! isset( $_GET['activate-multi'] ) ) {

				wp_redirect( 'admin.php?page=brown_paper_tickets_settings_setup_wizard' );

			}
		}
	}

	/**
	 * Delete all _bpt_ options directly from the database.
	 */
	private static function delete_options() {

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

	public function render_bpt_options_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-settings.php' );
	}

	public function render_bpt_setup_wizard_page() {
		require_once( plugin_dir_path( __FILE__ ) . '../admin/bpt-setup-wizard.php' );
	}

}
