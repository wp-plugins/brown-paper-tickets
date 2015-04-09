<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules\Account;

require_once( plugin_dir_path( __FILE__ ).'../../../src/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTPlugin as plugin;


class Inputs {

	public static function section() {
		?>
			<h3>Setup your Brown Paper Tickets Developer ID and Client ID.</h3>
		<?php
	}

	public static function developer_id() {
		?>
		<div class="dev-id-wrapper">
			<input name="_bpt_dev_id" value="<?php esc_attr_e( get_option( '_bpt_dev_id' ) );?>" type="text" placeholder="<?php esc_attr_e( 'Developer ID' ); ?>">
			<div class="<?php esc_attr_e( plugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						To access your developer ID, go here.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public static function client_id() {
		?>
		<div class="client-id-wrapper">
			<input name="_bpt_client_id" value="<?php esc_attr_e( get_option( '_bpt_client_id' ) );?>" type="text" placeholder="<?php esc_attr_e( 'Client ID' ); ?>">
			<div class="<?php esc_attr_e( plugin::get_menu_slug() ) ?>_help">
				<span>?</span>
				<div>
					<p>
						This is your Brown Paper Tickets username.
					</p>
				</div>
			</div>
		</div>
		<?php
	}
}