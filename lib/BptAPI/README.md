# Brown Paper Tickets PHP API
[![Build Status](https://img.shields.io/travis/brownpapertickets/BptAPI.php.svg?style=flat-square)](https://travis-ci.org/brownpapertickets/BptAPI.php) [![Packagist](https://img.shields.io/packagist/v/brown-paper-tickets/bpt-api.svg?style=flat-square)](https://packagist.org/packages/brown-paper-tickets/bpt-api) [![License MIT](http://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

The BptAPI library consists of a set of classes that enable you to easily interact with the [Brown Paper Tickets API](http://www.brownpapertickets.com/apidocs/index.html).

Since this library is still in early development, method names will possibly change.
See [CHANGELOG](CHANGELOG.md) for more information on any breaking changes.

## Install
Via Composer:
`$ composer require brown-paper-tickets/bpt-api`

## Usage

You'll want to first initialize the class that contains the methods you want to use. The class names mirror the [official API documentation](http://www.brownpapertickets.com/apidocs/index.html). 

So if you were looking to get info on an event's sales, you'd use the `SalesInfo` class. Please note, the methods names are completely different (and hopefully easier to make use of). Every time you intialize a class, you need to pass in your Brown Paper Tickets Developer ID.

For Example, to get a listing of events under a specific account, you'd use the [EventInfo](#eventinfo) class.

```php
$eventInfo = new EventInfo('Your Developer ID');
```

That will give you access to all of that class' methods.

To obtain an array containing all of the producer's events, we'd invoke the `getEvents` method. The get events method takes a total of four arguments:

| Arguments | Type | Required | Description |
|-----------|------|----------|-------------|
| `$username` | String  | No | The event producer whos events you wish to info on. |
| `$eventID`  | Integer | No | If you only want info on a single event, you can pass in it's ID. |
| `$getDates` | Boolean | No | Pass `true` if you want to get a list of dates belonging to the event. Defaults to `false`|
| `$getPrices`| Boolean | No | Pass `true` if you want to get a list of prices belogning to each Date. Defaults to `false`|

```php
$events = $eventInfo->getEvents('some user name' null, true, true);
 ```
This would return an associative array with all of the event info along with dates and prices:

```php

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
## The Classes and Methods

The library contains the following classes:
* [AccountInfo](#accountinfo)
* [CartInfo](#cartinfo)
* [EventInfo](#eventinfo)
* [ManageCart](#managecart)
* [ManageEvent](#manageevent)
* [SalesInfo](#salesinfo)

### AccountInfo
The AccountInfo class has a single method that will return info about the specified user.

#### getAccount($username)
Authorization Required: __Yes__

| Arguments | Description | Required |
|-----------|-------------|---------------|
|`$username`  |The user name of the account that you wish to get info on.| Yes |

__Returns__ 
This will return an array with the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | Integer | The producer ID |
| `username` | String | The producer's username.|
| `firstName` | String | First name |
| `lastName` | String | Last name |
| `address` | String | The address|
| `city` | String | City |
| `zip` | String | Zip Code |
| `phone` | Integer | Phone |
| `email` | String | Email |
| `nameForCheck` | String | The name that checks will be made out to. |

### CartInfo
Documentation Coming (View Source!)

### EventInfo
Authorization Required: __No__
The EventInfo class provides methods that allow you to obtain event data.

#####getEvents

| Arguments | Description | Required | Default | 
|-----------|-------------|----------|---------|
| `$username` |The user name of the account that you wish to get info on. If not given, will return a ALL active BPT events and will probably break. | No | `null` |
| `$eventID` | If passed, will only return the information for that event | No | `null` |
| `$getDates` | Whether or not to also get a list of dates. | No | `false` |
| `getPrices` | Whether or not to also get a list of prices. | No | `false` |

__Returns__

This method returns an array of event arrays that contain the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | Integer | The event ID. |
| `title` | string | Title of the Event. |
| `live` | boolean | Whether or not the event is live. |
| `address1` | string | Event's address 1. |
| `address2` | string | Event's address 2. |
| `city` | string | Event's abbreviated state. | 
| `zip` | string | Event's zip/postal code. |
| `shortDescription | string | Event's short description. |
| `fullDescription | string | Event's full description. |
| `phone` | string | Event's phone number. |
| `web` | string | Event's website. |
| `contactName` | string | Contact's name. |
| `contactPhone` | string | Contact's phone. |
| `contactAddress1` | string | Contact's address 1. |
| `contactAddress2` | string | Contact's address 2. |
| `contactCity` | string | Contact's city. |
| `contactState` | string | Contact's state. |
| `contactCountry` | string | Contact's country. |
| `contactZip` | string | Contact's zip/postal code. |
| `contactEmail` | string | Contact's email | 


### ManageCart

Some methods will return a results array with two fields. The first is `success` which is a bolean indicating whether or not it failed and a `message` field explaining why.

#### initCart($cartID = null, $createdAt = null)
Starts a new cart session with Brown Paper Tickets. You can also pass in an existing `cartID` and the time it was `createdAt`. If the cart has expired it will return a results array with `success` set to `false`.

If successful, it will return a results array with `success` set to `true` as well as a `cartID` and `cartCreatedAt` field.

__Returns__
The newly created `cartID`. This cart will expire after 15 minutes.


#### isExpired()
Determines whether or not the `cartID` has expired.

#### getCartId()

__Returns__
The `cartID`.


#### setAffiliateID($affiliateID)

Pass your affiliate ID if you want to earn a commission on the sale.

#### setPrices($prices)
Set the prices to send to the cart. Will return the set prices.
This does not update the actual cart (use `sendPrices()` for that).

This will throw out any prices with an invalid shipping method and will determine
whether or not will call names need to be required when adding shipping info.

Pass in an array of prices IDs. Each Price ID value should be set to an array
with the following fields:

| parameter | type | description |
|-----------|------|-------------|
| `shippingMethod` | integer | An integer representing shipping method*
| `quantity` | integer | the number of tickets you wish to add. |
| `affiliateID` | integer | Optional. If you wish to earn a commision, add the affiliate ID. |

*Shipping Methods 1 for Physical, 2 for Will Call, 3 for Print at Home.

```php
$prices = array(
    '12345' => array(
        'shippingMethod' => 1,
        'quantity' => 2,
    ),
    '12346' => array(
        'shippingMethod' => 3,
        'quantity' => 3
    )
);
```

Returns an array of the set prices.

#### removePrices($prices)
Pass in an array of price IDs and it will remove the price IDs passed from the set prices. This does not update the actual cart (use `sendPrices()` for that).

__Returns__
An array of the set prices.

#### sendPrices()
Send the prices to the cart via the API.

Returns the results array.

#### getPrices()
Returns the prices set.

#### getValue()
Returns the current value of the cart as it was set when adding prices.
Note: When the class sets the value, it will determine whether or not certain fields are required for the billing info. See `setShipping()` for more info.

#### setShipping($shipping)
Pass in an array of shipping information.

| field | notes |
|-------|-------|
| firstName | |
| lastName | |
| address | |
| address2 | |
| city | |
| state | |
| zip | |
| country | Values include "United States" and "Canada". |

If you have selected tickets that require Will Call names, you can pass in a different name using the `willCallFirstName` and `willCallLastName` fields. Otherwise it will default to using the first and last name fields for will call names.

#### setBilling($billing)
Pass in an array of billing information.

Always pass these fields:

| field | notes |
|-------|-------|
| firstName | |
| lastName | |

When the cart's value is greather than 0, you must also include the following fields. If you do not, then the cart will return the results array with the message ""

| field | notes |
|-------|-------|
| address | |
| address2 | |
| city | |
| state | |
| zip | |
| email | |
| phone | |
| country | Values include "United States" and "Canada". |
| type | Credit card type. Must be "Visa", "Mastercard", "Discover" or "Amex" |
| number | Credit card number. Must be a string |
| expMonth | Expiration month. |
| expYear | Expriration year. |
| cvv2 | Credit card verification code |

Returns a results array.

#### sendBilling()
Sends the billing information to the cart.

Once this has been called and is successful, you will no longer be able to `sendPrices()`, `sendShipping()` or `sendBilling()`. 

Returns a results array with the following fields:

| field | notes |
|-------|-------|
| success | boolean |
| message | A description of the results |
| ticketUrl | If successful and this shipping method was chosen, a link to the print-at-home tickets |
| cartID | the ID of the cart ID |
| receiptURL | If successful, a URL to the order's receipt on BPT. | 

#### getReceipt()
Returns the results received by the `sendBilling()` method. 

### SalesInfo
Documentation Coming (View Source!)


## Latest Changes

(See [CHANGELOG](CHANGELOG.md) for full set of changes)

### v0.12

**The `ManageCart` class has been completely rewritten!**

* __Breaking Changes__
    * `ManageCart`
        * The ManageCart class has been complely rewritten. See [README](README.md#managecart) for new API.
## License
The MIT License (MIT)

Copyright (c) 2015 Brown Paper Tickets

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
