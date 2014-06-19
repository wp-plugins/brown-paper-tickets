(function($) {
    'use strict';

    var navigation,
        customDateFormat,
        customTimeFormat,
        bptWelcomePanel,
        bptAPI;

    navigation = {
        loadTab: function loadTab() {
            var currentTab = this.getAnchor;
            this.switchTabs(currentTab);
        },
        switchTabs: function hideTabs(tab) {
            var currentTab = tab,
                tabs = this.getTabs();

            if (!tab) {
                currentTab = tabs[0];
            }

            this.setAnchor(tab);

            $('#bpt-settings-wrapper').children('div').hide();

            if (!currentTab) {
                $('a[href="#account-setup"]').addClass('selected-tab');
                $('#bpt-settings-wrapper div:first-child').show();
                return;
            }

            $('div' + currentTab).show();
            $('a.bpt-admin-tab').removeClass('selected-tab');
            $('a[href="' + currentTab + '"]').addClass('selected-tab');
        },
        getAnchor: function getAnchor() {
            var anchor = window.location.hash.substring(1);

            if (!anchor) {
                return false;
            }

            anchor = '#' + anchor;

            return anchor;
        },
        getTabs: function getTabs() {
            var tabs = [];

            $('#brown_paper_tickets_settings ul li').each(function() {
               tabs.push($(this).children('a').attr('href')); 
            });

            return tabs;
        },
        setAnchor: function setAnchor(tab) {

            if (tab === this.getAnchor()) {
                return;
            }
            
            document.location.replace(tab);

        }
    };

    customDateFormat = function() {

        var selectedDateFormat = $('select#date-format option').filter(':selected');

        if (selectedDateFormat.val() === 'custom') {
            $('input#custom-date-format-input').removeClass('hidden');
        } else {
            $('input#custom-date-format-input').addClass('hidden');
        }
    };

    customTimeFormat = function() {

        var selectedTimeFormat = $('select#time-format option').filter(':selected');

        if (selectedTimeFormat.val() === 'custom') {
            $('input#custom-time-format-input').removeClass('hidden');
        } else {
            $('input#custom-time-format-input').addClass('hidden');
        }
    };

    bptAPI = {
        getAccount: function getAccount() {
            $.ajax(
                bptWP.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_get_account',
                        // varsx
                        // send the nonce along with the request
                        bptNonce : bptWP.bptNonce,
                        bptData: 'account',
                    },
                    accepts: 'json',
                    dataType: 'json'

                }
            ).done(function(data) {
                bptWelcomePanel.set({
                    account: data
                });
            }).fail(function(data) {
                bptWelcomePanel.set({
                    error: data
                });
            });
        },
        deleteCache: function deleteCache() {
            $.ajax(
                bptWP.ajaxurl,
                {
                    type: 'POST',
                    data: {
                        // wp ajax action
                        action : 'bpt_delete_cache',
                        // vars
                        // send the nonce along with the request
                        bptNonce : bptWP.bptNonce,
                    },
                    accepts: 'json',
                    dataType: 'json'

                }
            )
            .always(function() {
                $('.bpt-loading').hide();
                
            }).done(function(data) {

                $('.bpt-loading').hide();
                $('#bpt-refresh-events').show();

                $('.bpt-advanced-options .bpt-success-message')
                .text(data.message)
                .fadeIn(500)
                .delay(2000)
                .fadeOut(500);
                // bptWelcomePanel.set({
                //     request: {
                //         result: data.result,
                //         message: data.message
                //     }
                // });
            }).fail(function(data) {
                $('.bpt-advanced-options .bpt-error-message')
                .text(data.message)
                .fadeIn(500)
                .delay(2000)
                .fadeOut(500);
            });
        }
    };



    $(document).ready(function() {



        navigation.switchTabs(navigation.getAnchor());

        $('a.bpt-admin-tab').click(function(e) {
            e.preventDefault();
            var tab = $(this).attr('href');
            navigation.switchTabs(tab);
        });


        customDateFormat();
        customTimeFormat();

        $('select#date-format').change(function() {
            customDateFormat();
        });

        $('select#time-format').change(function() {
            customTimeFormat();
        });

        $('.bpt-welcome-panel-close').click(function(event) {
            event.preventDefault();

            $('.bpt-welcome-panel').toggle();
        });

        $('#bpt-delete-cache').click(function(event) {
            event.preventDefault();
            $('.bpt-loading').show();
            bptAPI.deleteCache();
        });

        bptWelcomePanel = new Ractive({
            el: '.bpt-welcome-panel-content',
            template: '#bpt-welcome-panel-template',
            data: {}
        });

        bptAPI.getAccount();

    });
})(jQuery);