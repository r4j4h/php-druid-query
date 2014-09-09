<?php

namespace DruidFamiliar;

use DateTime;

class SegmentMetadataDruidQuery implements IDruidQuery
{

    private $params = Array();

    public function __construct($dataSource, $start = "1970-01-01 01:30:00", $end = "3030-01-01 01:30:00") {

        $startDateTime = new DateTime($start);
        $endDateTime = new DateTime($end);

        $ISOstartDateTime = $startDateTime->format(DateTime::ISO8601);
        $ISOendDateTime = $endDateTime->format(DateTime::ISO8601);

        $this->setParams(
            array(
                'dataSource' => $dataSource,
                'intervals' => $ISOstartDateTime . '/' . $ISOendDateTime,
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
            'queryType' => 'segmentMetadata',
            "dataSource" => $this->params['dataSource'],
            "intervals" => $this->params['intervals']
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