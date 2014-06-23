=== Brown Paper Tickets ===
Contributors: Chandler Blum
Donate Link: N/A
Tags: bpt, brown paper tickets
Requires at least: 3.6
Tested up to: 3.9.1
Stable tag: 0.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Brown Paper Tickets Plugin is a simple way to display your Brown Paper Tickets events in a Wordpress post/page.

== Description ==

The Brown Paper Tickets plugin is a simple way to display events in a Wordpress post/page. You can display a single event, a list of all events or all of your events in a calendar.

The Brown Paper Tickets plugin is Free Software, released under the [GNU GPL v2 or later](http://www.gnu.org/licenses/gpl-2.0.txt). Certain libraries used by the plugin (see About section) are licensed under the MIT License.

It's source code can be found on [Github](https://github.com/chantron/brown-paper-tickets-wordpress).

**There are some caveats to using this plugin. PLEASE READ!**

* The data returned by the [pricelist](http://www.brownpapertickets.com/apidocs/2.0/pricelist.html) API call does not make a distinction between password protected prices and regular prices. As a result, prices that are typically hidden by passwords on BPT will show up via the plugin. **DO NOT use this plugin if you intend to use the event list feature or want your password protected prices to stay hidden.** Calendar format should be OK as it does not make the price list API call.

**Please use the [Issues](https://github.com/chantron/brown-paper-tickets-wordpress/issues/new) page to submit bugs, feature requests, etc.**

== Installation == 

To install the plugin, download the zip, extract it and upload the extracted folder to your plugins directory on your webserver.

From there, activate the plugin as normal. The plugin should take you through a setup wizard. If for some reason it doesn't, on the bottom of your Wordpress Admin menu you should see a "BPT Settings" link.

To obtain your developer ID, you must first have developer tools added to your Brown Paper Tickets account. First log into BPT, then go to [Account Functions](https://www.brownpapertickets.com/user/functions.html). Click Developer Tools and then add. You'll see a new link in the BPT navigation titled "Developer". Click that and you'll see your developer ID listed at the top.

Your client ID is typically whatever you use to log into Brown Paper Tickets.

== Plugin Usage ==

To use the plugin place the shortcode ``` [list_event] ``` in the post
or page that you want the listing to appear.

= All Events =

[list_events]

= Single Event Listing =
Use the ```[list_events event_id="XXXXXX"]``` shortcode to display a single event (XXXXXX is the event ID).

**The default shipping options set by the plugin are Will-Call and Print at Home. If your events are using something different, go to the "BPT Settings" page in the Wordpress Admin to set them.**

= Calendar Format =

Display a calendar listing all of your events:

    [event_calendar]

You can optionally pass in a ```client_id``` attribute to display another producers events in a calendar.

== About the Calendar ==

This plugin makes use of the following Free/Open Source Software:

- [CLNDR.js](http://kylestetz.github.io/CLNDR/)

- [Ractive.js](http://www.ractivejs.org/)

- [Moment.js](http://momentjs.com/)

== Frequently Asked Questions ==

= I've updated some of my event's on Brown Paper Tickets but the changes are not showing up in the plugin. Why is that? =

You most likely have enabled the plugin the cache the data the plugin pulls in from Brown Paper Tickets. 

There are a few ways to solve this:
    - You could wait for the cache to expire.
    - You could delete the cache and force the plugin to refresh the data.
        - To Do that, simply go to the "General Settings" tab above and click "Delete Cache".

= I am 100% certain that my developer ID and client ID are correct. What is going on? =

It's possible that your client ID is not attached to your developer tools.

To add your account:

- Go to <a target="_blank" href="https://www.brownpapertickets.com/developer/accounts.html">Authorized Accounts</a>.

- If your account is listed under "Current Account", click "Edit" and then "Delete Account".

- On the next screen, under "Add a Client" enter in your username and password, select the permissions you need and hit "Add Client Account".

- Your account should now be authorized.

= My password protected prices are being displayed by the plugin, how do I prevent that? =

Unfortunately, you cannot at this time.

The ability to prevent specific prices from being displayed is a top priority for the next release

= How can I customize the look and feel of the event list or the calendar? =

At the moment, not easily. You could edit the event-list's style sheet directly (located plugin directory under ```brown-paper-tickets/assets/css/event-list-short-code.css```).

The ability to easily add your custom style sheet is a top priority for the next release.

== Upgrade Notice ==

No upgrade notes.

== Changelog ==

= v0.1.2 =

**Improvements**

* Added proper uninstall functions.

**Bug Fixes**

* Fixed issue where event calendar wasn't being displayed if a widget
wasn't in place.

* Fixed issue where the cache wasn't being deleted properly. 

**Miscellaneous**

* Updated header in main plugin file.


= v0.1.1 =
**Improvements**

* Users can now list multiple events in the same shortcode event_id
attribute.

**Bug Fixes**

* Added 100% width to the pricing table on the default event list theme.

* Fixed issue with PHP versions below 5.3. Changed short array syntax
to array()

* Added proper checks for various shortcode spelling.

* Updated BptAPI library to latest version which fixes a bug where
API errors weren't being returned as an array.

* Fixed bug where event list is displayed only when there is no error.

* Fixed bug where using the event ID of an event not belonging to the
default producer would call the BPT API using the default client ID.

* Fixed issue with loading gif not displaying.

* Fixed issue where data from the API was returned too early.

**Miscellaneous**

* Updated Readme to reflect WP version requirement. has_shortcode()
was introduced in version 3.6.

= v0.1 =

* Initial Release
== Screenshots ==
1.
2.
3.