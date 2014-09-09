<?php

namespace DruidFamiliar;

interface IDruidQuery
{

    /**
     * Execute the query represented by this object, getting the result in PHP array form
     *
     * @return mixed
     * @throws
     */
    public function execute(IDruidConnection $conn, $params = Array());

    /**
     * Generate a JSON Druid Query POST object in PHP array form
     *
     * @param array $response
     * @return mixed
     */
    public function generateQuery();

    /**
     * Hook function to handle response from server.
     *
     * @param array $response
     * @return mixed
     */
    public function handleResponse($response = Array());


    /**
     * Get the currently configured query params
     *
     * @return mixed
     */
    public function getParams();

    /**
     * Validate the given parameter values
     *
     * @return mixed
     */
    public function validateParams(Array $params);

    /**
     * Set the query params
     *
     * @param {Array} $query
     * @return void
     */
    public function setParams(Array $query);

}
