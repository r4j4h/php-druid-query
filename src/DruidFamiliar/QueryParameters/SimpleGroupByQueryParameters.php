<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interval;

/**
 * Class SimpleGroupByQueryParameters
 * Represents parameter values for an indexing task for Druid.
 * @package   DruidFamiliar\QueryParameters
 * @author    Jasmine Hegman
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
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
     * @param $aggregatorsArray array PHP Array of aggregators
     */
    public function setAggregators($aggregatorsArray)
    {
        $this->aggregators = array();

        foreach( $aggregatorsArray as $key => $val)
        {
            $this->aggregators[] = json_encode( $val );
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

        foreach( $postAggregatorsArray as $key => $val)
        {
            $this->postAggregators[] = json_encode( $val );
        }

    }

    /**
     * @throws MissingParametersException
     */
    public function validate()
    {
        // Validate missing params
        $missingParams = array();

        if ( !isset( $this->queryType       ) ) { $missingParams[] = 'queryType';       }
        if ( !isset( $this->dataSource      ) ) { $missingParams[] = 'dataSource';      }
        if ( !isset( $this->intervals       ) ) { $missingParams[] = 'intervals';       }

        if ( !isset( $this->granularity     ) ) { $missingParams[] = 'granularity';     }
        if ( !isset( $this->dimensions      ) ) { $missingParams[] = 'dimensions';      }
        if ( !isset( $this->aggregators     ) ) { $missingParams[] = 'aggregators';     }
        if ( !isset( $this->postAggregators ) ) { $missingParams[] = 'postAggregators'; }

        if ( count($missingParams) > 0 ) {
            throw new MissingParametersException($missingParams);
        }



        // Validate empty params
        $emptyParams = array();

        if ( $this->queryType === '' ) { $emptyParams[] = 'queryType'; }
        if ( $this->dataSource === '' ) { $emptyParams[] = 'dataSource'; }

        if ( count($emptyParams) > 0 ) {
            throw new MissingParametersException($missingParams);
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
     * @param $intervalStart Interval
     * @param $intervalEnd Interval
     */
    public function setIntervalByStartAndEnd($intervalStart, $intervalEnd)
    {
        $this->intervals = new Interval($intervalStart, $intervalEnd);
    }

}