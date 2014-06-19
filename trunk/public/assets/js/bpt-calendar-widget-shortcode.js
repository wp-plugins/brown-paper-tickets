(function($) {
    'use strict';
    var BptAPI;

    BptAPI = function BptAPI() {

        var self = this;

        this.allEvents = [];

        this.currentEvents = [];

        this.getEvents();

        this.calendarTemplate = $('#bpt-calendar-widget-calendar-template').html();

        this.bptCalendarWidgetShortcode = $('.bpt-calendar-widget-shortcode').clndr({
            template: self.calendarTemplate,
            targets: {
                nextButton: 'bpt-calendar-widget-controls-next-button',
                previousButton: 'bpt-calendar-widget-controls-previous-button',
                nextYearButton: 'clndr-next-year-button',
                previousYearButton: 'clndr-previous-year-button',
                todayButton: 'clndr-today-button',
                day: 'day',
                empty: 'empty'
            },
            clickEvents: {
                click: function(target) {

                    self.currentEvents = target.events;

                    self.bptCalendarWidgetEventView.set({
                        currentEvents: self.currentEvents
                    });
                }
            }
        });

        this.bptCalendarWidgetEventView = new Ractive({
            el: '#bpt-calendar-widget-shortcode-event-view',
            template: '#bpt-calendar-widget-event-view-template',
            data: {
                currentEvents: self.currentEvents,
                formatDate: function formatDate(newFormat, date) {
                    var singleDate = moment(date, 'YYYY-MM-DD');
                    return singleDate.format(newFormat);
                },
                formatTime: function formatTime(newFormat, time) {
                    var singleTime = moment(time, 'H:mm');
                    return singleTime.format(newFormat);
                }
            }
        });
    };

    BptAPI.prototype.loadCalendar = function loadCalendar() {
        var self = this;
    };

    BptAPI.prototype.getEvents = function getEvents() {
        var self = this;

        if ( bptCalendarWidgetShortcodeAjax.clientID ) {

            $.ajax(
            bptCalendarWidgetShortcodeAjax.ajaxurl,
            {
                type: 'POST',
                data: {
                    // wp ajax action
                    action : 'bpt_get_calendar_events',
                    // varsx
                    // send the nonce along with the request
                    clientID: bptCalendarWidgetShortcodeAjax.clientID,
                    bptNonce : bptCalendarWidgetShortcodeAjax.bptNonce,
                    widgetID: bptCalendarWidgetShortcodeAjax.widgetID,
                },
                accepts: 'json',
                dataType: 'json'

            }).done(function(data) {

                self.bptCalendarWidgetShortcode.setEvents(data);
                
            }).fail(function(data) {

                self.bptCalendarWidgetShortcode.setEvents(data);

            });

        } else {

            $.ajax(
            bptCalendarWidgetShortcodeAjax.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_get_calendar_events',
                        // varsx
                        // send the nonce along with the request
                        bptNonce : bptCalendarWidgetShortcodeAjax.bptNonce,
                        widgetID: bptCalendarWidgetShortcodeAjax.widgetID,
                    },
                    accepts: 'json',
                    dataType: 'json'

                }
            ).done(function(data) {

                self.bptCalendarWidgetShortcode.setEvents(data);
                
            }).fail(function(data) {

                self.bptCalendarWidgetShortcode.setEvents(data);

            });
        }
        
    };

    BptAPI.prototype.setEvents = function setEvents(events) {
        var self = this;

        this.events = events;

        $(document).ready(function() {

            self.loadCalendar();

        });

    };



    
    $(document).ready(function() {

        var bptAPI = new BptAPI();
    });

})(jQuery);