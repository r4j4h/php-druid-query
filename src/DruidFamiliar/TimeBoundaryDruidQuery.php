<?php

namespace DruidFamiliar;

class TimeBoundaryDruidQuery implements IDruidQuery
{

    private $params = Array();
    private $query;

    public function __construct($dataSource) {
        $this->setParams(
            array(
                'dataSource' => $dataSource
            )
        );
    }

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
     * Take parameters and return a valid Druid Query.
     *
     * @param array $params
     * @return array Druid JSON POST Body in PHP Array form
     * @throws Exception\MissingParametersException
     */
    public function generateQuery()
    {

        return array(
            'queryType' => 'timeBoundary',
            "dataSource" => $this->params['dataSource']
        );

    }

    /**
     * Hook function to handle response from server.
     *
     * @param array $response
     * @return mixed
     */
    public function handleResponse($response = Array()) {

        return $response;

    }



    /**
     * Get the represented JSON Druid Query POST object in PHP array form corresponding with json_(en/de)code()
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Set the query from a JSON Druid Query POST object in PHP array form corresponding with json_(en/de)code()
     *
     * @param {Array} $query
     * @return void
     */
    public function setQuery($query) {
        $this->query = $query;
    }

    /**
     * Get the currently configured query params
     *
     * @return mixed
     */
    public function getParams() {

        if ( !isset( $params['dataSource'] ) ) {
            throw new Exception\MissingParametersException('dataSource');
        }

        return $this->params;
    }

    /**
     * Set the query params
     *
     * @param {Array} $query
     * @return void
     */
    public function setParams($params) {

        if ( !isset( $params['dataSource'] ) ) {
            throw new Exception\MissingParametersException('dataSource');
        }

        $this->params = $params;
    }
}