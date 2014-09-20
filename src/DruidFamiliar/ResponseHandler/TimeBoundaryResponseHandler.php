<?php

namespace DruidFamiliar\ResponseHandler;

use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\Response\TimeBoundaryResponse;

/**
 * Class TimeBoundaryResponseHandler attempts to convert a Druid response into a TimeBoundaryResponse.
 *
 * @package DruidFamiliar\ResponseHandler
 */
class TimeBoundaryResponseHandler implements IDruidQueryResponseHandler
{

    /**
     * Hook function to handle response from server. Called with a PHP array of the JSON response from Druid.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return TimeBoundaryResponse
     */
    public function handleResponse($response)
    {
        if ( empty( $response ) ) {
            throw new \Exception('Unknown data source.');
        }

        if ( !isset ( $response[0]['result'] ) ) {
            throw new \Exception('Unexpected response format.');
        }
        if ( !isset ( $response[0]['result']['minTime'] ) ) {
            throw new \Exception('Unexpected response format - response did not include minTime.');
        }
        if ( !isset ( $response[0]['result']['maxTime'] ) ) {
            throw new \Exception('Unexpected response format - response did not include maxTime.');
        }

        $responseObj = new TimeBoundaryResponse();

        $responseObj->minTime = $response[0]['result']['minTime'];
        $responseObj->maxTime = $response[0]['result']['maxTime'];

        return $responseObj;
    }

}