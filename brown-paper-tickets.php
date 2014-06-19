<?php
/**
 * Brown Paper Tickets Event List Plugin
 *
 * This plugin allows you to easily display your events in your
 * Wordpress posts, sidebar etc. Tickets can also be directly.
 *
 * @package   BrownPaperTickets
 * @author    Chandler Blum <chandler@brownpapertickets.com>
 * @license   GPL-2.0+
 * @link      http://www.brownpapertickets.com
 * @copyright 2014 Brown Paper Tickets
 *
 * @wordpress-plugin
 * Plugin Name:       Brown Paper Tickets
 * Plugin URI:        http://www.brownpapertickets.com
 * Description:       Display your events!
 * Version:           0.1.1
 * Author:            Chandler Blum
 * Author URI:        http://www.brownpapertickets.com
 * Text Domain:       brown-paper-tickets-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */


require_once( plugin_dir_path( __FILE__ ).'inc/brown-paper-tickets-plugin.php' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


register_activation_hook( __FILE__, array( 'BrownPaperTickets\BPTPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BrownPaperTickets\BPTPlugin', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'BrownPaperTickets\BPTPlugin', 'uninstall' ) );

add_action( 'plugins_loaded', array( 'BrownPaperTickets\BPTPlugin', 'get_instance' ) );

