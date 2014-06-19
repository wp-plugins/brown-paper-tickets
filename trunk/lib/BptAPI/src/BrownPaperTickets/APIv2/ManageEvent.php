<?php

namespace BrownPaperTickets\APIv2;

class ManageEvent extends BptAPI
{
    ///////////////////////////////
    // Create/Manage Event Calls //
    ///////////////////////////////

    /**
     * A function to Create the Events.
     * The following are required in the $eventParms
     * array.
     *
     * name
     * city
     * state
     * shortDescription
     * fullDescription
     *
     * @param string $userName    The username the event will be
     *                            created under.
     * @param array  $eventParams The event's parameters.
     *
     * @return array
     */
    
    public function createEvent($userName, $eventParams)
    {

        if (!isset($eventParams['name'])
            || !isset($eventParams['city'])
            || !isset($eventParams['state'])
            || !isset($eventParams['shortDescription'])
            || !isset($eventParams['fullDescription'])
        ) {
            throw new Exception('Missing Required Argument');
        }

        $apiOptions['endpoint'] = 'createevent';
        $apiOptions['account'] = $userName;
        $apiOptions['e_name'] = $eventParams['name'];
        $apiOptions['e_city'] = $eventParams['city'];
        $apiOptions['e_state'] = $eventParams['state'];
        $apiOptions['e_short_description'] = $eventParams['shortDescription'];
        $apiOptions['e_description'] = $eventParams['fullDescription'];

        if (isset($eventParams['address1'])) {
            $apiOptioons['e_address1'] = $eventParams['address1'];
        }
        if (isset($eventParams['address2'])) {
            $apiOptions['e_address2'] = $eventParams['address2'];
        }

        if (isset($eventParams['zip'])) {
            $apiOptions['e_zip'] = $eventParams['zip'];
        }
        if (isset($eventParams['phone'])) {
            $apiOptions['e_phone'] = $eventParams['phone'];
        }
        if (isset($eventParams['web'])) {
            $apiOptions['e_web'] = $eventParams['web'];
        }
        if (isset($eventParams['endOfEventMessage'])) {
            $apiOptions['end_of_event_message'] = $eventParams['endOfEventMessage'];
        }

        if (isset($eventParams['endOfSaleMessage'])) {
            $apiOptions['end_of_sale_message'] = $eventParams['endOfSaleMessage'];
        }
        if (isset($eventParams['dateNotes'])) {
            $apiOptions['date_notes'] = $eventParams['dateNotes'];
        }
        if (isset($eventParams['notes'])) {
            $apiOptions['e_notes'] = $eventParams['notes'];
        }
        if (isset($eventParams['contactName'])) {
            $apiOptions['c_name'] = $eventParams['contactName'];
        }
        if (isset($eventParams['contactEmail'])) {
            $apiOptions['c_email'] = $eventParams['contactEmail'];
        }
        if (isset($eventParams['contactPhone'])) {
            $apiOptions['c_phone'] = $eventParams['contactPhone'];
        }
        if (isset($eventParams['contactFax'])) {
            $apiOptions['c_fax'] = $eventParams['contactFax'];
        }
        if (isset($eventParams['contactAddress1'])) {
            $apiOptions['c_address1'] = $eventParams['contactAddress1'];
        }
        if (isset($eventParams['contactAddress2'])) {
            $apiOptions['c_address2'] = $eventParams['contactAddress2'];
        }
        if (isset($eventParams['contactCity'])) {
            $apiOptions['c_city'] = $eventParams['contactCity'];
        }
        if (isset($eventParams['contactCountry'])) {
            $apiOptions['c_zip'] = $eventParams['contactCountry'];
        }
        if (isset($eventParams['public'])) {
            $apiOptions['c_zip'] = $eventParams['public'];
        }

        $apiResults = $this->callAPI($apiOptions);

        $createEventXML = $this->parseXML($apiResults);

        if (isset($createEventXML['error'])) {
            return $createEventXML;
        }

        $createEvent = array(
            'id' => $createEventXML['event_id']
        );

        return $createEvent;
    }
    /**
     * A function to change an.
     *
     * @param string $userName    The username the event will be
     *                            created under.
     * @param array  $eventParams The event's parameters.
     *
     * @return array
     */

    public function changeEvent($userName, $eventParams)
    {

        if (isset($eventParams['name'])) {
            $apiOptions['e_name'] = $eventParams['name'];
        }
        if (isset($eventParams['e_city'])) {
            $apiOptioons['e_city'] = $eventParams['city'];
        }
        if (isset($eventParams['e_state'])) {
            $apiOptioons['e_state'] = $eventParams['state'];
        }
        if (isset($eventParams['e_short_description'])) {
            $apiOptioons['e_short_description'] = $eventParams['shortDescription'];
        }
        if (isset($eventParams['e_description'])) {
            $apiOptioons['e_description'] = $eventParams['fullDescription'];
        }
        if (isset($eventParams['address1'])) {
            $apiOptioons['e_address1'] = $eventParams['address1'];
        }
        if (isset($eventParams['address2'])) {
            $apiOptions['e_address2'] = $eventParams['address2'];
        }
        if (isset($eventParams['zip'])) {
            $apiOptions['e_zip'] = $eventParams['zip'];
        }
        if (isset($eventParams['phone'])) {
            $apiOptions['e_phone'] = $eventParams['phone'];
        }
        if (isset($eventParams['web'])) {
            $apiOptions['e_web'] = $eventParams['web'];
        }
        if (isset($eventParams['endOfEventMessage'])) {
            $apiOptions['end_of_event_message'] = $eventParams['endOfEventMessage'];
        }
        if (isset($eventParams['endOfSaleMessage'])) {
            $apiOptions['end_of_sale_message'] = $eventParams['endOfSaleMessage'];
        }
        if (isset($eventParams['dateNotes'])) {
            $apiOptions['date_notes'] = $eventParams['dateNotes'];
        }
        if (isset($eventParams['notes'])) {
            $apiOptions['e_notes'] = $eventParams['notes'];
        }
        if (isset($eventParams['contactName'])) {
            $apiOptions['c_name'] = $eventParams['contactName'];
        }
        if (isset($eventParams['contactEmail'])) {
            $apiOptions['c_email'] = $eventParams['contactEmail'];
        }
        if (isset($eventParams['contactPhone'])) {
            $apiOptions['c_phone'] = $eventParams['contactPhone'];
        }
        if (isset($eventParams['contactFax'])) {
            $apiOptions['c_fax'] = $eventParams['contactFax'];
        }
        if (isset($eventParams['contactAddress1'])) {
            $apiOptions['c_address1'] = $eventParams['contactAddress1'];
        }
        if (isset($eventParams['contactAddress2'])) {
            $apiOptions['c_address2'] = $eventParams['contactAddress2'];
        }
        if (isset($eventParams['contactCity'])) {
            $apiOptions['c_city'] = $eventParams['contactCity'];
        }
        if (isset($eventParams['contactCountry'])) {
            $apiOptions['c_zip'] = $eventParams['contactCountry'];
        }
        if (isset($eventParams['public'])) {
            $apiOptions['c_zip'] = $eventParams['public'];
        }


        $apiResults = $this->callAPI($apiOptions);

        $changeEventXML = $this->parseXML($apiResults);

        if (isset($changeEventXML['error'])) {
            return $changeEventXML;
        }

        $changeEvent = array(
            'result' => 'success'
        );

        return $createEvent;
    }

    /**
     * Add a date to an event.
     *
     * @param string $userName   The username the event belongs to.
     * @param array  $dateParams An array of parameters.
     * It must include the following:
     *
     * event_id    integer *REQUIRED* The ID you wish to add this date to.
     * beginTime   string  *REQUIRED* The time the event begins on this
     *                     date.
     * endTime     string  *REQUIRED* The time the event ends on this
     *                     date.
     * salesEnd    string  *REQUIRED* The time you wish to end sales.
     * maxSales    integer *REQUIRED* The maximum number of sales for
     *                     this date.
     * physical    boolean Whether or not to sell physical tickets for
     *                     this date.
     * pointOfSale boolean Whether or not to make tickets available
     *                     through the Brown Paper Tickets Open Ticket
     *                     Network.
     * willCall    boolean Whether to offer tickets for Will-Call
     *                     pickup.
     *
     * @return array An array with the date ID if successfull.
     */
    public function addDate($userName, $dateParams)
    {

        if (!isset($dateParams['eventID'])
            || !isset($dateParams['beginTime'])
            || !isset($dateParams['endTime'])
            || !isset($dateParams['salesEnd'])
            || !isset($dateParams['maxSales'])
        ) {
            return;
        }

        if (!checkDateFormat($dateParams['beginTime'])
            || !checkDateFormat($dateParams['endTime'])
        ) {

            return $this->handleError($data);
        }

        $apiOptions = array(
            'endpoint' => 'changedate',
            'account' => $userName,
            'event_id' => $dateParams['eventID'],
            'begin_time' => $dateParams['beginTime'],
            'end_time' => $dateParams['endTime'],
            'sales_end' => $dateParams['salesEnd'],
            'max_sales' => $dateParams['maxSales'],
            'physical' => $dateParams['physicalTickets'],
            'pos' => $dateParams['pointOfSale'],
            'willcall' => $dateParams['willCall']
        );

        $apiResults = $this->callAPI($apiOptions);

        $addDateXML = $this->parseXML($apiResults);

        if (isset($addDateXML['error'])) {
            return $addDateXML;
        }

        $addDate = array(
            'id' => $addDateXML['date_id']
        );

        return $addDate;
    }

    /**
     * Add a date to an event.
     *
     * @param string $userName   The username the event belongs to.
     * @param array  $dateParams An array of parameters.
     * It must include the following:
     *
     * eventID     integer *REQUIRED* The ID you wish to add this date
     *                     to.
     * beginTime   string  *REQUIRED* The time the event begins on
     *                     this date.
     * endTime     string  *REQUIRED* The time the event ends on this
     *                     date.
     * salesEnd    string  *REQUIRED* The time you wish to end sales.
     * maxSales    integer *REQUIRED* The maximum number of sales for
     *                     this date.
     * physical    boolean Whether or not to sell physical tickets for
     *                     this date.
     * pointOfSale boolean Whether or not to make tickets available
     *                     through the Brown Paper Tickets Open Ticket
     *                     Network.
     * willCall    boolean Whether to offer tickets for Will-Call
     *                     pickup.
     *
     * @return array An array with a success or fail message.
     */
    public function changeDate($userName, $dateParams)
    {

        $apiOptions = array(
            'endpoint' => 'changeDate',
            'account' => $userName,
            'event_id' => $dateParams['eventID'],
            'begin_time' => $dateParams['beginTime'],
            'end_time' => $dateParams['endTime'],
            'sales_end' => $dateParams['salesEnd'],
            'max_sales' => $dateParams['maxSales'],
            'physical' => $dateParams['physicalTickets'],
            'pos' => $dateParams['pointOfSale'],
            'willcall' => $dateParams['willCall']
        );

        $apiResults = $this->callAPI($apiOptions);

        $changeDateXML = $this->parseXML($apiResults);

        if (isset($changeDateXML['error'])) {
            return $changeDateXML;
        }

        $changeDate = array(
            'result' => 'success'
        );

        return $changeDate;
    }

    /**
     * Add a price to the event
     *
     * @param string $userName    The username the event belongs to
     * @param [type] $priceParams An array of price parameters
     *
     * eventID   integer *REQUIRED* The ID of the event that this
     *                   price will be attached to.
     * dateID    integer *REQUIRED* The ID of the date that this price
     *                   will be attached to.
     * value     integer *REQUIRED* The Value of the price.
     * name      string  *REQUIRED* The name of the price.
     * startTime string  The time the price goes on sale.
     *                   Must be in the MMM-DD-YYYY 24:00
     * endTime   string  The time the prices ceases sale.
     *                   Must be in the MMM-DD-YYYY 24:00
     * maxSales  integer The maximum number of tickets
     *                   for sale at this price level.
     *
     * @return  array An array containing the ID of the price created.
     */
    public function addPrice($userName, $priceParams)
    {
        if (!isset($priceParams['eventID'])
            || !isset($priceParams['dateID'])
            || !isset($priceParams['value'])
            || !isset($priceParams['name'])
        ) {
            return $this->handleError($data);
        }

        $apiOptions = array(
            'endpoint' => 'addprice',
            'account' => $userName,
            'event_id' => $priceParams['eventID'],
            'date_id' => $priceParams['dateID'],
            'price' => $priceParams['value'],
            'price_name' => $priceParams['name'],
            'start_time' => $priceParams['startTime'],
            'end_time' => $priceParams['endTime'],
            'max_sales' => $priceParams['maxSales']
        );

        $apiResults = $this->$callAPI($apiOptions);

        $addPriceXML = $this->parseXML($apiResults);

        if (isset($addPriceXML['error'])) {
            return $addPriceXML;
        }

        $addPrice = array(
            'id' => $addPriceXML['price_id']
        );
    }

    /**
     * Add a price to the event
     *
     * @param string $userName    The username the event belongs to
     * @param [type] $priceParams An array of price parameters
     *
     * eventID   integer The ID of the event that this
     *                   price will be attached to.
     * dateID    integer The ID of the date that this price
     *                   will be attached to.
     * value     integer The Value of the price.
     * name      string  The name of the price.
     * startTime string  The time the price goes on sale.
     *                   Must be in the MMM-DD-YYYY 24:00
     * endTime   string  The time the prices ceases sale.
     *                   Must be in the MMM-DD-YYYY 24:00
     * maxSales  integer The maximum number of tickets
     *                   for sale at this price level.
     *
     * @return  array Returns an array containing a success or fail.
     */
    public function changePrice($userName, $priceParams)
    {

        $apiOptions = array(
            'endpoint' => 'changeprice',
            'account' => $userName,
            'event_id' => $priceParams['eventID'],
            'date_id' => $priceParams['dateID'],
            'price' => $priceParams['value'],
            'price_name' => $priceParams['name'],
            'start_time' => $priceParams['startTime'],
            'end_time' => $priceParams['endTime'],
            'max_sales' => $priceParams['maxSales']
        );

        $apiResults = $this->$callAPI($apiOptions);

        $addPriceXML = $this->parseXML($apiResults);

        if (isset($changePriceXML['error'])) {
            return $changePriceXML;
        }

        $changePrice = array(
            'result' => 'success'
        );

        return $changePrice;
    }
}
