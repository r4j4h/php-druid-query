<?php

namespace DruidFamiliar;

class TransformingTimeBoundaryDruidQuery implements IDruidQuery
{

    private $params = Array();

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

        if ( isset ( $response[0]['result'] ) ) {
            return $response[0]['result'];
        }

        if ( empty( $response ) ) {
            throw new \Exception('Unknown data source');
        }

        throw new \Exception('Unexpected response format');

    }



    /**
     * Get the currently configured query params
     *
     * @return mixed
     */
    public function getParams() {

        $this->validateParams($this->params);

        return $this->params;
    }

    public function validateParams(Array $params) {

        if ( !isset( $params['dataSource'] ) ) {
            throw new Exception\MissingParametersException('dataSource');
        }

    }

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