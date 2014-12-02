<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\DruidTime;
use DruidFamiliar\Interval;

/**
 * Class SimpleGroupByQueryParameters represents parameter values for a typical group by Druid query.
 *
 * @package DruidFamiliar
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
     * Intended to be set through $this->setFilters(...).
     *
     * @var array
     */
    public $filters = array();

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
     * @param $aggregatorsArray array PHP Array of aggregators
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
     * Configure the filters for this request.
     *
     * @param $filtersArray PHP Array of aggregators
     */
    public function setFilters($filtersArray)
    {
        $this->filters = array();

        foreach( $filtersArray as $filter)
        {
            $this->filters[] = json_encode( $filter );
        }

    }

    /**
     * Configure the post aggregators for this request.
     *
     * @param $postAggregatorsArray array PHP Array of post aggregators
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
            'filters',
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
     * @param string|\DateTime|DruidTime $intervalStart
     * @param string|\DateTime|DruidTime $intervalEnd
     */
    public function setIntervalByStartAndEnd($intervalStart, $intervalEnd)
    {
        $this->setIntervals(new Interval($intervalStart, $intervalEnd));
    }

    /**
     * Adjusts the datetime to make the interval "exclusive" of the datetime.
     * e.g., given
     * startDateTime=2014-06-18T12:30:01Z and
     * endDateTime=2014-06-18T01:00:00Z
     * return and Interval containing
     * startDateTime=2014-06-18T12:30:00Z and
     * endDateTime=2014-06-18T01:00:01Z
     *
     * @param $startDateTime
     * @param $endDateTime
     * @return void
     */
    public function setIntervalForQueryingUsingExclusiveTimes($startDateTime, $endDateTime)
    {
        $adjustedEndDateTime = new \DateTime($endDateTime);
        $adjustedEndDateTime->add(new \DateInterval('PT1S'));

        $this->setIntervals(new Interval($startDateTime, $adjustedEndDateTime));
    }
}
