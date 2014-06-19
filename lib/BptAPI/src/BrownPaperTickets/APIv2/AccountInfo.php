<?php

namespace BrownPaperTickets\APIv2;

class AccountInfo extends BptAPI
{
    ///////////////////////////////
    // Account Information Calls //
    ///////////////////////////////

    /**
     * Get the Account information
     *
     * @param string $username The username of the account
     *                          must be authorized. Required.
     *
     * @return array
     */
    public function getAccount($username)
    {
        $apiOptions = array(
            'endpoint' => 'viewaccount',
            'account' => $username
        );

        $apiResults = $this->callAPI($apiOptions);

        $accountXML = $this->parseXML($apiResults);

        if (isset($accountXML['error'])) {
            return $accountXML;
        }

        $account = array(
            'id' => (integer) $accountXML->client_id,
            'name' => (string) $accountXML->c_client_name,
            'firstName' => (string) $accountXML->c_fname,
            'lastName' => (string) $accountXML->c_lname,
            'address' => (string) $accountXML->c_address,
            'city' => (string) $accountXML->c_city,
            'state' => (string) $accountXML->c_state,
            'zip' => (string) $accountXML->c_zip,
            'phone' => (string) $accountXML->c_phone,
            'email' => (string) $accountXML->c_email,
            'nameForChecks' => (string) $accountXML->c_name_for_checks
        );

        return $account;
    }
}
