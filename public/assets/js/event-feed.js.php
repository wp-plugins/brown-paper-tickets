<?php 
	$post_id = $_GET['post_id'];
	$wp_object = 'bptEventFeedAjaxPost' . $post_id;
?>

(function($) {
	'use strict';
	var BptEventList;

	BptEventList = function BptEventList(postID) {

		var self = this;
		
		this.postID = postID;

		this.allEvents = [];

		this.bptWpObject = {};
	  

		this.eventList = new Ractive({
			el: '#bpt-event-list-' + self.postID,
			template: '#bpt-event-template',
			data: {

				formatDate: function formatDate(newFormat, date) {
					var singleDate = moment(date, 'YYYY-MM-DD');
					return singleDate.format(newFormat);
				},

				formatTime: function formatTime(newFormat, time) {
					var singleTime = moment(time, 'H:mm');
					return singleTime.format(newFormat);
				},

				unescapeHTML: function unescapeHTML(html) {
					return _.unescape(html);
				},

				formatPrice: function formatPrice(price, currency) {
					var separator = '.',
						priceArr;

					if (currency === '€' ) {
						separator = ',';
					}

					if (price === 0) {
						return 'Free';
					}

					priceArr = price.toString().split('.');

					if (!priceArr[1]) {
						price = priceArr[0] + separator + '00';
					} else {
						price = priceArr[0] + separator + priceArr[1]; 
					}

					return currency + '' + price;
				}
			}
		});

		this.eventList.on({
			showFullDescription: function showFullDescription(event) {
				event.original.preventDefault();
				$(event.node).parent().next('.bpt-event-full-description').toggle('hidden');
			}
		});

		self.init();
	};

	BptEventList.prototype.getEvents = function getEvents() {
		var self = this,
			bptData = {
				action: 'bpt_get_events',
				bptNonce: this.bptWpObject.bptNonce,
				postID: this.bptWpObject.postID
			};            

			if ( this.bptWpObject.clientID ) {

				bptData.clientID = this.bptWpObject.clientID;
			}

			if ( this.bptWpObject.eventID ) {

				bptData.eventID = this.bptWpObject.eventID
			}

			$('div.bpt-loading-' + self.postID).fadeIn();

			$.ajax(
				this.bptWpObject.ajaxurl,
				{
					type: 'POST',
					data: bptData,
					accepts: 'json',
					dataType: 'json'
				}
			)
			.always(function() {
				$('div.bpt-loading-' + self.postID).hide();
			})
			.fail(function() {

				self.eventList.set({
					bptError: 'Unknown Error'
				});

			})
			.done(function(data) {
				if (data.error) {

					self.eventList.set({
						bptError: data
					});

				}

				if ( !data.error ) {

					self.eventList.set({
						bptEvents: data
					});

				}
			})
			.always(function() {
				
			});
	};

	BptEventList.prototype.init = function init() {

		this.bptWpObject = <?php echo $wp_object; ?>;

		this.getEvents();

	}
	
	$(document).ready(function() {
		var bptEventList<?php echo $post_id; ?> = new BptEventList( <?php echo $post_id; ?> );
	});

})(jQuery);