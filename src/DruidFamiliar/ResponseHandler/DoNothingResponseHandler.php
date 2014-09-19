<?php

namespace DruidFamiliar\ResponseHandler;

use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;

/**
 * Class DoNothingResponseHandler simply returns a response unchanged.
 *
 * @package DruidFamiliar\ResponseHandler
 */
class DoNothingResponseHandler implements IDruidQueryResponseHandler
{

    /**
     * Hook function to handle response from server.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return mixed
     */
    public function handleResponse($response)
    {
        return $response;
    }
}