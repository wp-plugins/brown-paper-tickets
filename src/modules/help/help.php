<?php

namespace BrownPaperTickets\Modules;

require_once( plugin_dir_path( __FILE__ ).'../bpt-module-class.php' );
require_once( plugin_dir_path( __FILE__ ).'/help-ajax.php' );
class Help extends Module {

	public function register_sections() {
		$section_title  = 'Help';
		$section_suffix = '_help';

		add_settings_section( $section_title, $section_title, array( $this, 'contents' ), self::$menu_slug . $section_suffix );
	}

	public function load_admin_ajax_actions() {
		add_action( 'wp_ajax_bpt_get_all_options', array( 'BrownPaperTickets\Modules\Help\Ajax', 'get_all_options' ) );
	}

	public function contents() {
		?>
			<p>
				<h2>F.A.Q.</h2>
				<ul>
					<li>
						<h3>I've updated some of my event's on Brown Paper Tickets but the changes are not showing up in the plugin. Why is that?</h3>
						<p>
							You most likely have enabled the plugin the cache the data the plugin pulls in from Brown Paper Tickets.
						</p>
						<p>
							There are a few ways to solve this:
							<ol>
								<li>You could wait for the cache to expire.</li>
								<li>You could delete the cache and force the plugin to refresh the data.</li>
							</ol>
						</p>
						<p>To delete the cache, simply go to the "General Settings" tab above and click "Delete Cache".</p>
					</li>
					<li>
						<h3>I am 100% certain that my developer ID and client ID are correct. What is going on?</h3>
						<p>It's possible that your client ID is not attached to your developer tools.</p>

						<p>To add your account:</p>

						<ol>
							<li>Go to <a target="_blank" href="https://www.brownpapertickets.com/developer/accounts.html">Authorized Accounts</a>.</li>
							<li>If your account is listed under "Current Account", click "Edit" and then "Delete Account".</li>
							<li>On the next screen, under "Add a Client" enter in your username and password, select the permissions you need and hit "Add Client Account".</li>
							<li>Your account should now be authorized.</li>
						</ol>

						<p>If you are still having issues, send an email to <a href="mailto:support@brownpaperticekts.com">support@brownpapertickets.com</a></p>
					</li>
					<li>
						<h3>My password protected prices are being displayed by the plugin, how do I prevent that?</h3>
						<p>When you're logged into Wordpress as an Administrator, go to the post/page where the event list is being displayed. You should see a green "HIDE PRICE" link under the prices. Clicking that will hide the price from any visitor to the site who is not logged in as an admin.</p>
					</li>
					<li>
						<h3>How can I customize the look and feel of the event list or the calendar?</h3>

						<p>
							Click on the appearance tab above. You'll be able to paste custom CSS for the event list and calendar there.
						</p>
					</li>
					<li>
				</ul>
			</p>
			<h3>Setup Wizard</h3>
			<ul>
				<li>Go To <a href="<?php echo get_admin_url( null, 'admin.php' ); ?>?page=brown_paper_tickets_settings_setup_wizard">Setup Wizard</a></li>
			</ul>
			<h3>Debug</h3>
		<?php
			global $wpdb;
			echo '<strong>PHP Version: </strong>' . phpversion() . '<br />';
			echo '<strong>OS: </strong>' . php_uname() . '<br />';
			$curl_version = curl_version();
			echo '<strong>cURL Version: </strong>' . $curl_version['version'];
			?>
			<h4><a id="test-api" href="#test-api">Test connection to the API.</a></h4><div id="test-api-results"></div>
			<h4><a id="get-all-options" href="#get-debug">Get all options saved by the plugin</a></h4>
			<div id="all-options-results">
			</div>
<script type="text/html" id="all-options-template">
{{ #if options}}
<textarea>{{ #options:option}}{{ option }}: {{ . }}
{{ /options}}
</textarea>
{{ /if }}
</script>
			<?php
	}
}
