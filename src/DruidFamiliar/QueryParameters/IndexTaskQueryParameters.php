<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

/**
 * Class IndexTaskParameters represents parameter values for an indexing task for Druid.
 *
 * @package PhpDruidIngest
 */
class IndexTaskQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{

    /**
     * ISO Time String of Batch Ingestion Window Start Time
     *
     * @var string
     */
    public $intervalStart;


    /**
     * ISO Time String of Batch Ingestion Window End Time
     *
     * @var string
     */
    public $intervalEnd;


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

        foreach( $aggregatorsArray as $key => $val)
        {
            $this->aggregators[] = json_encode( $val );
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
        // TODO: Implement validate() method.
    }
}