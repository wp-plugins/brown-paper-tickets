(function($) {
    'use strict';
    var eventList,
        bptAPI;

    bptAPI = {
        postID: '',
        loadEvents: function loadEvents() {
            
            this.postID = bptEventFeedAjax.postID;
            
            var bptData = {
                action: 'bpt_get_events',
                bptNonce: bptEventFeedAjax.bptNonce,
                postID: bptEventFeedAjax.postID
            };


            if ( bptEventFeedAjax.clientID ) {
                bptData.clientID = bptEventFeedAjax.clientID;
            }

            if ( bptEventFeedAjax.eventID ) {
                bptData.eventID = bptEventFeedAjax.eventID;
            }

            $('div.bpt-loading').fadeIn();

            $.ajax(
                bptEventFeedAjax.ajaxurl,
                {
                    type: 'POST',
                    data: bptData,
                    accepts: 'json',
                    dataType: 'json'
                }
            )
            .always(function() {
                $('div.bpt-loading').hide();
            })
            .fail(function() {

                eventList.set({
                    bptError: 'Unknown Error'
                });

            })
            .done(function(data) {
                if (data.error) {

                    console.log(data);

                    eventList.set({
                        bptError: data
                    });

                }

                if ( !data.error ) {

                    eventList.set({
                        bptEvents: data
                    });

                }
            })
            .always(function() {
                
            });
            
        }
    };

    $(document).ready(function(){

        eventList = new Ractive({
            el: '#bpt-event-list',
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

        bptAPI.loadEvents();

        eventList.on({
            showFullDescription: function showFullDescription(event) {
                event.original.preventDefault();
                $(event.node).parent().next('.bpt-event-full-description').toggle('hidden');
            }
        });

    });

})(jQuery);