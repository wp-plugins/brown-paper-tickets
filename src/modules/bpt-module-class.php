<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ).'../brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BptWordpress as Utilities;

class Module {

	protected static $menu_slug = null;
	protected static $setting_prefix = '_bpt_';
	static $section_title;
	static $section_suffix;

	public function __construct() {

		self::$menu_slug = BPTPlugin::$menu_slug;

		$this->init_actions();

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_js' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_css' ) );
			add_action( 'admin_menu', array( $this, 'load_menus' ) );
			$this->load_admin_ajax_actions();
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_js' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_shared_js' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_shared_css' ) );

		$this->load_public_ajax_actions();
		$this->load_shortcode();
	}

	public function init_actions() {

	}

	public function load_settings() {
		$this->register_settings();
		$this->register_sections();
		$this->custom_functions();
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

	public function load_admin_ajax_actions() {

	}

	public function load_menus() {

	}

	public function load_shortcode() {

	}

	public function load_public_ajax_actions() {

	}

	public function load_widgets() {

	}

	public function register_sections() {

	}

	public function register_settings() {

	}

	public function display_settings_sections() {

	}

	public function custom_functions() {

	}

	public function set_default_setting_values() {

	}

	public function remove_setting_values() {

	}

	public function activate() {
		$this->set_default_setting_values();
	}

	public function deactivate() {
		$this->remove_setting_values();
	}

}