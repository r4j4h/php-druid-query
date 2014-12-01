<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interval;

/**
 * Class SimpleGroupByQueryParameters represents parameter values for an indexing task for Druid.
 *
 * @package PhpDruidIngest
 */
class SimpleGroupByQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{

    /**
     * Query Type
     *
     * @var string
     */
    public $queryType = "groupBy";

    /**
     * @var Interval
     */
    public $intervals;


    /**
     * @var string
     */
    public $granularityType = 'uniform';


    /**
     * @var string
     */
    public $granularity = 'DAY';


    /**
     * DataSource Name
     *
     * @var string
     */
    public $dataSource;


    /**
     * Path for ingestion. Keep in mind the coordinator and historical nodes will need to be able to access this!
     *
     * Intended to be set through $this->setFilePath(...).
     *
     * @var string
     */
    public $baseDir;


    /**
     * Filename for ingestion. Keep in mind the coordinator and historical nodes will need to be able to access this!
     *
     * Intended to be set through $this->setFilePath(...).
     *
     * @var string
     */
    public $filePath;


    /**
     * Format of ingestion.
     *
     * @var string
     */
    public $format = 'json';


    /**
     * The data's time dimension key.
     *
     * @var string
     */
    public $timeDimension;


    /**
     * Array of strings representing the data's non-time dimensions' keys.
     *
     * @var array
     */
    public $dimensions;


    /**
     * Array of json encoded strings
     *
     * Intended to be set through $this->setAggregators(...).
     *
     * @var array
     */
    public $aggregators = array();


    /**
     * Array of json encoded strings
     *
     * Intended to be set through $this->setPostAggregators(...).
     *
     * @var array
     */
    public $postAggregators = array();


    public function setFilePath($path) {
        $fileInfo = pathinfo($path);
        $this->baseDir = $fileInfo['dirname'];
        $this->filePath = $fileInfo['basename'];
    }

    /**
     * Configure the aggregators for this request.
     *
     * @param $aggregatorsArray PHP Array of aggregators
     */
    public function setAggregators($aggregatorsArray)
    {
        $this->aggregators = array();

        foreach( $aggregatorsArray as $aggregator)
        {
            $this->aggregators[] = json_encode( $aggregator );
        }

    }

    /**
     * Configure the post aggregators for this request.
     *
     * @param $postAggregatorsArray PHP Array of post aggregators
     */
    public function setPostAggregators($postAggregatorsArray)
    {
        $this->postAggregators = array();

        foreach( $postAggregatorsArray as $postAggregator)
        {
            $this->postAggregators[] = json_encode( $postAggregator );
        }

    }

    /**
     * @throws MissingParametersException
     */
    public function validate()
    {
        $this->validateForMissingParameters();

        $this->validateForEmptyParameters();
    }

    /**
     * @throws MissingParametersException
     */
    protected function validateForMissingParameters()
    {
        // Validate missing params
        $missingParams = array();

        $requiredParams = array(
            'queryType',
            'dataSource',
            'intervals',
            'granularity',
            'dimensions',
            'aggregators',
            'postAggregators'
        );

        foreach ($requiredParams as $requiredParam) {
            if ( !isset( $this->$requiredParam ) ) {
                $missingParams[] = $requiredParam;
            }
        }

        if ( count($missingParams) > 0 ) {
            throw new \DruidFamiliar\Exception\MissingParametersException($missingParams);
        }
    }

    /**
     * @throws MissingParametersException
     */
    protected function validateForEmptyParameters()
    {
        // Validate empty params
        $emptyParams = array();

        $requiredNonEmptyParams = array(
            'queryType',
            'dataSource'
        );

        foreach ($requiredNonEmptyParams as $requiredNonEmptyParam) {
            if ( !isset( $this->$requiredNonEmptyParam ) ) {
                $emptyParams[] = $requiredNonEmptyParam;
            }
        }

        if ( count($emptyParams) > 0 ) {
            throw new \DruidFamiliar\Exception\MissingParametersException($emptyParams);
        }
    }

    /**
     * @return Interval
     */
    public function getIntervals()
    {
        return $this->intervals;
    }

    /**
     * @param Interval $intervals
     */
    public function setIntervals(Interval $intervals)
    {
        $this->intervals = $intervals;
    }

    /**
     * @param Interval $intervals
     */
    public function setIntervalByStartAndEnd($intervalStart, $intervalEnd)
    {
        $this->intervals = new Interval($intervalStart, $intervalEnd);
    }

}