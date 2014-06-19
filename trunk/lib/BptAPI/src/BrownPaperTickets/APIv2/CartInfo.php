<?php

namespace BrownPaperTickets\APIv2;

class CartInfo extends BptAPI
{
    /**
     * Returns an array containing the contents of the specified cart.
     * 
     * @param  string $cartID The Cart ID
     * @return array          An array containing the carts of the cart.
     */
    public function getCartContents($cartID)
    {
        $apiOptions = array(
            'endpoint' => 'cartcontents',
            'cart_id' => $cartID
        );

        $apiResponse = $this->callAPI($apiOptions);

        $cartContentsXML = $this->parseXML($apiResponse);

        if (isset($cartContentsXML['error'])) {
            return $cartContentsXML;
        }
        $cartContents = array();

        foreach ($cartContentsXML->ticket as $ticket) {
            $singleTicket = array(
                'eventID' => (string) $ticket->event_id,
                'eventName' => (string) $ticket->e_name,
                'dateID' => (string) $ticket->date_id,
                'beginTime' => (string) $ticket->begin_time,
                'endTime' => (string) $ticket->end_time,
                'priceID' => (string) $ticket->price_id,
                'priceName' => (string) $ticket->price_name,
                'priceValue' => (string) $ticket->price,
                'shippingMethod' => (string) $ticket->shipping,
                'seatingSection' => (string) $ticket->section,
                'seatingRow' => (string) $ticket->row,
                'seatingSeat' => (string) $ticket->seat,
                'willCallLastName' => (string) $ticket->lname,
                'willCallFirstName' => (string) $ticket->fname
            );

            $cartContents[] = $singleTicket;
        }
        
        return $cartContents;
    }


    /**
     * Get the value of the specified Cart
     * @param  string $cartID The cart ID.
     * @return [type]         [description]
     */
    public function getCartValue($cartID)
    {
        $apiOptions = array(
            'endpoint' => 'cartvalue',
            'cart_id' => $cartID
        );

        $apiResults = $this->callAPI($apiOptions);

        $cartValueXML = $this->parseXML($apiResults);

        if (isset($cartValueXML['error'])) {
            return $cartValueXML;
        }
        $cartValue = array(
            'cartValue' => (string) $cartValueXML->val,
            'cartCurrency' => (string) $cartValueXML->currency,
            'shippingNeeded' => $this->convertToBool($cartValueXML->shipping)
        );

        return $cartValue;
    }
}
