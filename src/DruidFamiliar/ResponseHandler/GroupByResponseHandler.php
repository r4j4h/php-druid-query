<?php

namespace DruidFamiliar\ResponseHandler;

use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\Response\GroupByResponse;
use Guzzle\Http\Message\Response;

/**
 * Class GroupByResponseHandler
 * @package   DruidFamiliar\ResponseHandler
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByResponseHandler implements IDruidQueryResponseHandler
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

        $responseObj = new GroupByResponse();
        $responseObj->setData($response);

        return $responseObj;
    }
}