# Changelog

## v0.13.1

* `ManageCart->initCart()` will now accept two arguments. `cartID` and `cartCreatedAt`.

## v0.13

**The `ManageCart` class has been completely rewritten!**

* __General Fixes__
    * Removed unnecessary type casting in `ManageCart`.

* __Breaking Changes__
    * `ManageCart`
        * The ManageCart class has been complely rewritten. See [README](README.md#managecart) for new API.

## v0.11
* __General New Stuff__
    * **Properties**

        * `errors` - An array of errors.

        * `logErrors` - whether or not to log errors in the array.

    * Added a params array to the `__constuct` function. This is optional. Currently
    you can use it to set the `logErrors` property e.g. `$params = array('logErrors' => true);`

    * Added a some tests.

* __New Methods__
    * `setOption($option, $value)` - Set an option. Currently only accepts `'logErrors'`.

    * `getOption($option)` - gets an option. If the option is invalid, it will throw an exception.

    * `setError($methodName, $description)` - Set an error.

    * `getErrors($newest)` - Get the errors array. Pass in `'newest'` to get only
    the newest error.

* __Breaking Changes__
    * All arguments for `changeEvent`/`changeDate`/`changePrice` are now passed through
    an array. You must now include the `username` (no longer camelCase) field in it.

    * __RENAMED METHODS__
        * `EventInfo->getEventImages()` has been renamed to `EventInfo->getImages()`.

        * `ManageCart->addPricesToCart()` has been renamed to `ManageCart->addPrices()`.

        * `ManageCart->removePricesFromCart()` has been renamed to `ManageCart->removePrices()`.

        * `ManageCart->addShippingInfo()` has been renamed to `ManageCart->addShipping()`.

        * `ManageCart->addBillingInfo()` has been renamed to `ManageCart->addBilling()`.

        * `CartInfo->getCartContents()` has been renamed to `CartInfo->getContents()`.

        * `CartInfo->getCartValue()` has been renamed to `CartInfo->getValue()`.


    * Some methods now have different return values. Generally speaking, if an
    error array was returned before, it will now return `false` and if `logErrors`
    is true, put the error in the `errors` array.

        * `CartInfo->getContents()` returns info array if successful, false if not.

        * `CartInfo->getValue()` returns info array if successful, false if not.

        * `ManageEvent` the change methods returns true if successful, false if not.

        * `AccountInfo->getAccount()` returns the account info array if successful, false if not.

        * `SalesInfo` methods return info array if successful, false if not.

        * `EventInfo->getImages()` returns false if an error has occured (bad event),
        null if no images are found or the array of images.

* __Bug Fixes__
    * `ManageCart->addPrices()` will now skip prices sent with invalid shipping methods.
    * Fixed parameters on `ManageEvent->createEvent()`/`ManageEvent->changeEvents()`.
    * Added proper checks for parameters on `ManageEvent->addDate`/`ManageEvent->changeDate` and `ManageEvent->addPrice`/`ManageEvent->changePrice`.

## v0.10.6
* __New Method__
    * `EventInfo->getEventImages()` - Added ability to get URLs to an event's
    images.

## v0.10.5
* __Breaking Changes__
    * `ManageCart->addPricesToCart()` - Fixed issue where adding a
    price with no quantity to the cart would still pass through the
    rest of the loop. If you passed in quantities of 0 to remove prices
    from the cart, use the new method `removePricesFromCart()`.

* __New Method__
    * `ManageCart->removePricesFromCart()` - You only need to pass in
    an array of price IDs.