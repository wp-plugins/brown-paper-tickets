# Brown Paper Tickets PHP API Wrapper
[![Build Status](https://travis-ci.org/BrownPaperTickets/BptAPI.php.svg?branch=master)](https://travis-ci.org/BrownPaperTickets/BptAPI.php)
This is a fully rewritten API Wrapper. It adheres to the PSR-02 coding standard.

## Install

Use Composer or just clone this repo and slap it into your project.

## Usage

#### Full Documentation Coming Soon

Every method has a PHPDoc comment block, unfortunately, that's the probably
the best way check it out. When instantiating a call, you always need your
Brown Paper Tickets Developer ID. 

In order to use this class you must have developer tools added to your BPT account.

To add those tools log into Brown Paper Tickets and go to [Account Functions](https://www.brownpapertickets.com/user/functions.html).


Basic Example:
``` 
$events = EventInfo('DeveloperID');

$eventsList = $events->getEvents('ClientID');

print_r($eventsList);

Array
(
[0] => Array
    (
        [id] => 443322
        [title] => Test Event
        [live] => 1
        [address1] => Brown Paper Tickets
        [address2] => 220 Nickerson St
        [city] => Seattle
        [state] => WA
        [zip] => 98103
        [shortDescription] => This is a short description.
        [fullDescription] => This is the full description. Much fuller! Lots more to say! OMG!

Use the Full Description to describe your event as completely as possible. It's common to list performers or presenters along with a short bio for each. Additional details, such as a description of the expected activities, help create interest for potential attendees and can greatly increase attendance. This is your chance to create a verbal picture of your event!
        [dates] => Array
            (
                [0] => Array
                    (
                        [id] => 880781
                        [dateStart] => 2016-08-12
                        [dateEnd] => 2016-08-12
                        [timeStart] => 7:30
                        [timeEnd] => 0:00
                        [live] => 1
                        [available] => 10000
                        [prices] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 2517973
                                        [name] => Assigned
                                        [value] => 0
                                        [serviceFee] => 0
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                                [1] => Array
                                    (
                                        [id] => 2517972
                                        [name] => General
                                        [value] => 0
                                        [serviceFee] => 0
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                                [2] => Array
                                    (
                                        [id] => 2524714
                                        [name] => SUPER PRICEY
                                        [value] => 25
                                        [serviceFee] => 1.87
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                            )

                    )

                [1] => Array
                    (
                        [id] => 882531
                        [dateStart] => 2016-12-13
                        [dateEnd] => 2016-12-13
                        [timeStart] => 14:00
                        [timeEnd] => 17:00
                        [live] => 
                        [available] => 10000
                        [prices] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 2524713
                                        [name] => SUPER PRICEY
                                        [value] => 25
                                        [serviceFee] => 1.87
                                        [venueFee] => 0
                                        [live] => 
                                    )

                            )

                    )

            )

    )

[1] => Array
    (
        [id] => 445143
        [title] => Another Test Event!
        [live] => 1
        [address1] => Tannhauser Gate
        [address2] => Alpha Orion
        [city] => Orion
        [state] => WA
        [zip] => 98107
        [shortDescription] => Unicorn Origami
        [fullDescription] => I've... seen things you people wouldn't believe... [laughs] Attack ships on fire off the shoulder of Orion. I watched c-beams glitter in the dark near the Tannhäuser Gate. All those... moments... will be lost in time, like [coughs] tears... in... rain. Time... to die...

&lt;img src="http://upload.wikimedia.org/wikipedia/en/1/1f/Tears_In_Rain.png" /&gt;
        [dates] => Array
            (
                [0] => Array
                    (
                        [id] => 881908
                        [dateStart] => 2017-08-14
                        [dateEnd] => 2017-08-15
                        [timeStart] => 13:00
                        [timeEnd] => 0:00
                        [live] => 1
                        [available] => 10000
                        [prices] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 2522667
                                        [name] => Assinged
                                        [value] => 1
                                        [serviceFee] => 1.03
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                                [1] => Array
                                    (
                                        [id] => 2522647
                                        [name] => General
                                        [value] => 10
                                        [serviceFee] => 1.34
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                            )

                    )

                [1] => Array
                    (
                        [id] => 881916
                        [dateStart] => 2018-08-12
                        [dateEnd] => 2018-08-12
                        [timeStart] => 19:00
                        [timeEnd] => 0:00
                        [live] => 1
                        [available] => 10000
                        [prices] => Array
                            (
                                [0] => Array
                                    (
                                        [id] => 2522668
                                        [name] => Assinged
                                        [value] => 1
                                        [serviceFee] => 1.03
                                        [venueFee] => 0
                                        [live] => 1
                                    )

                            )

                    )

            )

    )

)
```


### Changelog
* 6.3.2014 - Cleaned up some of the tests.

* Fixed some variable name typos. Fixed issue with dates/prices being
wrapped in an array when it is already being returned as an array.

* April 14, 2014: Intitial commit. Due to error, this commit is gone.
At this point, most endpoints have been added. Unit test coverages is
about 60% I'd say.
