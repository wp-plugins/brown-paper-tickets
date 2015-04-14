<?php

namespace BrownPaperTickets\Modules\General;

require_once( plugin_dir_path( __FILE__ ).'../../../src/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTPlugin as plugin;
use BrownPaperTickets\bptWordpress as Utilities;

class Inputs {
	public static function section() {

	}

	public function cache_time() {
		$increment  = 1;
		$cache_time = get_option( '_bpt_cache_time' );
		$cache_unit = get_option( '_bpt_cache_unit' );

		?>
			<div class="cache-time-wrapper">
				<p>Enabling caching of your event data will increase page load times.</p>
				<p>By setting the time below, you will tell the plugin to save the event data to the database temporarily and to serve the event data from there, rather than having to pull it in through the Brown Paper Tickets API every page load</p>
				<label for="cache-time-increment">Cache Time</label>
				<select id="cache-time-increment" name="_bpt_cache_time">
					<option value="false" <?php esc_attr_e( selected( $cache_time, '0' ) );?>>Do Not Cache</option>
					<option value="0" <?php esc_attr_e( selected( $cache_time, '0' ) );?>>Cache Indefinitely</option>
		<?php
		while ( $increment < 50 ) {
						echo '<option value="' . esc_attr( $increment ) . '"' . esc_attr( selected( $cache_time, $increment ) ) . '>' . esc_attr( $increment ) . '</option>';
						$increment++;
					}
		?>
				</select>
				<label for="cache-time-unit">Cache Unit</label>
				<select id="cache-time-unit" name="_bpt_cache_unit">
					<option value="minutes" <?php selected( $cache_unit, 'minutes' ); ?>>Minutes</option>
					<option value="hours" <?php selected( $cache_unit, 'hours' ); ?>>Hours</option>
					<option value="days" <?php selected( $cache_unit, 'days' ); ?>>Days</option>
				</select>


				<div class="<?php esc_attr_e( plugin::get_menu_slug() ); ?>_help">
					<span>?</span>
					<div>
						<p>Select the amount of time you would like to cache your event data.</p>
						<p>Setting this option will decrease the amount of time it takes for the data to load</p>
						<p></p>
					</div>
				</div>

				<div class="bpt-advanced-options">
					<button class="button-large button" id="bpt-delete-cache">Delete Cache</button>
					<img class="bpt-loading hidden" src="<?php echo esc_url( Utilities::plugin_root_url() . '/public/assets/img/loading.gif' ); ?>">
					<p class="bpt-success-message hidden"></p>
					<p class="bpt-error-message hidden"></p>
				</div>
			</div>
		<?php
	}
}