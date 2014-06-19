<?php

namespace BrownPaperTickets\APIv2;

class SalesInfo extends BptAPI
{

    /**
     * Get the Event Sales info for all events or a specific event,
     * or a specific event's specific date.
     *
     * @param string  $userName       The Username of the Authorized
     *                                Account. Required.
     * @param string  $eventID        The Event ID. Optional.
     * @param string  $dateID         The Date ID. Optional.
     * @param boolean $getOnlyCurrent Whether or not to only get
     *                                sales info for events that
     *                                are currently on active
     *
     * @return array
     */
    public function getEventSales(
        $userName,
        $eventID = '',
        $dateID = '',
        $getOnlyCurrent = false
    ) {
        $apiOptions = array(
            'endpoint' => 'eventsales',
            'account' => $userName,
            'event_id' => $eventID,
            'date_id' => $dateID,
            'current' => $getOnlyCurrent
        );

        $apiResults = $this->callAPI($apiOptions);

        $eventSalesXML = $this->parseXML($apiResults);

        if (isset($eventSalesXML['error'])) {
            return $eventSalesXML;
        }

        $eventSales = array();

        foreach ($eventSalesXML as $eventSale) {
            $singleEventSale = array(
                'title' => $eventSale->title,
                'link' => $eventSale->link,
                'eventID' => $eventSale->e_number,
                'eventStatus' => $eventSale->event_status,
                'ticketsSold' => $eventSale->tickets_sold,
                'collectedValue' => $eventSale->collected_value,
                'paidValue' => $eventSale->paid_value
            );

            $eventSales[] = $singleEventSale;
        }

        return $eventSales;
    }


    ////////////////////////////
    // Sales/Order Data Calls //
    ////////////////////////////

    /**
     * Get the sales data of a specific date or all dates
     *
     * @param string $userName The username of the event owner.
     *                         Required.
     * @param string $eventID  The Event ID. Required.
     * @param string $dateID   The Price ID. Required.
     *
     * @return [type]
     */
    public function getDateSales(
        $userName,
        $eventID,
        $dateID = ''
    ) {
        $apiOptions = array(
            'endpoint' => 'datesales',
            'account' => $userName,
            'event_id' => $eventID,
            'date_id' => $dateID
        );

        $apiResults = $this->callAPI($apiOptions);

        $dateSalesXML = $this->parseXML($apiResults);

        if (isset($dateSalesXML['error'])) {
            return $dateSalesXML;
        }

        $dateSales = array();

        foreach ($dateSalesXML as $dateSale) {

            $singleDate = array(
                'id' => $dateSale->date_id,
                'beginTime' => $dateSale->begin_time,
                'endTime' => $dateSale->end_time,
                'ticketsSold' => $dateSale->date_tickets_sold,
                'collectedValue' => $dateSale->date_collected_value,
                'prices' => array()
            );

            foreach ($dateSale->price as $price) {
                $singlePrice = array(
                    'id' => $price->price_id,
                    'name' => $price->price_name,
                    'ticketsSold' => $price->price_tickets_sold,
                    'collectedValue' => $price->price_collected_value
                );

                $singleDate['prices'][] = $singlePrice;
            }

            $dateSales[] = $singleDate;
        }

        return $dateSales;
    }

    /**
     * Get Order Info for a Specific Event, Date and Price
     *
     * @param string  $userName Your account. It must be in
     *                          the Authorized Accounts list.
     * @param integer $eventID  The ID of the Event
     * @param string  $dateID   The ID of the Date
     * @param string  $priceID  The ID of the Price
     *
     * @return array  $sales   An array of sales information.
     */
    public function getOrders(
        $userName,
        $eventID = '',
        $dateID = '',
        $priceID = ''
    ) {
        $apiOptions = array(
            'endpoint' => 'orderlist',
            'account' => $userName,
            'event_id' => $eventID,
            'date_id' => $dateID,
            'price_id' => $priceID
        );

        $apiResults = $this->callAPI($apiOptions);

        $ordersXML = $this->parseXML($apiResults);

        if (isset($ordersXML['error'])) {
            return $ordersXML;
        }

        $orders = array();

        foreach ($ordersXML->item as $sale) {

            $singleOrder = array(
                'time' => $sale->order_time,
                'dateID' => $sale->date_id,
                'priceID' => $sale->price_id,
                'quantity' => $sale->quantity,
                'firstName' => $sale->fname,
                'lastName' => $sale->lname,
                'address' => $sale->address,
                'city' => $sale->city,
                'state' => $sale->state,
                'zip' => $sale->zip,
                'country' => $sale->country,
                'email' => $sale->email,
                'phone' => $sale->phone,
                'creditCard' => $sale->cc,
                'shippingMethod' => $sale->shipping_method,
                'notes' => $sale->order_notes,
                'ticketNumber' => $sale->ticket_number,
                'section' => $sale->section,
                'row' => $sale->row,
                'seat' => $sale->seat
            );

            // put the singleSale into the sales array
            $orders[] = $singleOrder;
        }

        return $orders;

    }
}
