<?php

namespace DruidFamiliar\Interfaces;

/**
 * Interface IDruidQueryResponseHandler handles reception of Druid's response to an executed Druid query.
 *
 * @package DruidFamiliar\Interfaces
 */
interface IDruidQueryResponseHandler
{

    /**
     * Hook function to handle response from server.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return mixed
     */
    public function handleResponse( $response );

}