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
     * Get the represented JSON Druid Query POST object in PHP array form corresponding with json_(en/de)code()
     * @return mixed
     */
    public function getQuery();

    /**
     * Set the query from a JSON Druid Query POST object in PHP array form corresponding with json_(en/de)code()
     *
     * @param {Array} $query
     * @return void
     */
    public function setQuery($query);


    /**
     * Get the currently configured query params
     *
     * @return mixed
     */
    public function getParams();

    /**
     * Set the query params
     *
     * @param {Array} $query
     * @return void
     */
    public function setParams($query);

}
