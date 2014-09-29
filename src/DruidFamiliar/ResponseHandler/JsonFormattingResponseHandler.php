<?php

namespace DruidFamiliar\ResponseHandler;

use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use Guzzle\Http\Message\Response;

/**
 * Class JsonFormattingResponseHandler decodes a JSON response and returns the result.
 *
 * @package DruidFamiliar\ResponseHandler
 */
class JsonFormattingResponseHandler implements IDruidQueryResponseHandler
{
    /**
     * Hook function to handle response from server.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param Response $response
     *
     * @return mixed
     */
    public function handleResponse($response)
    {
        $response = $response->json();
        return $response;
    }
}