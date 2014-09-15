<?php

namespace DruidFamiliar;

abstract class BasicDruidQuery implements IDruidQuery
{

    private $params = Array();


    /**
     * Execute the query represented by this object, getting the result in PHP array form
     *
     * @return mixed
     * @throws
     */
    public function execute(IDruidConnection $connection, $params = Array())
    {

        return $this->generateQuery();

    }

    /**
     * Take parameters and return a valid Druid Query in PHP Associative Array form.
     *
     * @param array $params
     * @return array Druid JSON POST Body in PHP Array form
     * @throws Exception\MissingParametersException
     */
    abstract public function generateQuery();


    /**
     * Hook function to handle response from server.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return mixed
     */
    abstract public function handleResponse($response = Array());


    /**
     * Get the currently configured query params
     *
     * @return mixed
     */
    public function getParams() {

        $this->validateParams($this->params);

        return $this->params;
    }

    /**
     * @param array $params
     * @return mixed|void
     * @throws Exception\MissingParametersException
     */
    abstract public function validateParams(Array $params);

    /**
     * Set the query params
     *
     * @param {Array} $query
     * @return void
     */
    public function setParams(Array $params) {

        $this->validateParams($params);

        $this->params = $params;
    }
}