<?php

namespace DruidFamiliar\ExampleGroupByQueries;

use DruidFamiliar\ExampleResponseObjects\ExampleReferralByCompanyResponseObject;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;

/**
 * Class ReferralsByCompanyGroupByResponseHandler attempts to convert a Druid response into a ExampleReferralByCompanyResponseObject.
 *
 * @package DruidFamiliar\ResponseHandler
 */
class ReferralsByCompanyGroupByResponseHandler implements IDruidQueryResponseHandler
{

    /**
     * Hook function to handle response from server. Called with a PHP array of the JSON response from Druid.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return ExampleReferralByCompanyResponseObject
     */
    public function handleResponse($response)
    {
        if ( empty( $response ) ) {
            throw new \Exception('Unknown data source');
        }

        $responseArray = array();

        foreach ( $response as $index => $chunk)
        {
            $timestamp = $chunk['timestamp'];
            $companyId = $chunk['event']['company_id'];
            $facilityId = $chunk['event']['facility_id'];
            $referrals = $chunk['event']['referral_count'];

            $responseObj = new \DruidFamiliar\ExampleResponseObjects\ExampleReferralByCompanyResponseObject(
                $companyId, $facilityId, $referrals, $timestamp
            );

            $responseArray[] = $responseObj;
        }

        return $responseArray;
    }

}