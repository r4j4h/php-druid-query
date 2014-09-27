<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\EmptyParametersException;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use stdClass;

/**
 * Class GroupByQueryParameters
 * @package   DruidFamiliar\QueryParameters
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{
    /**
     * This String should always be "groupBy"; this is the first thing Druid looks at to figure out how to interpret the query
     * @access protected
     * @var string
     */
    protected $queryType;
    /**
     * A String defining the data source to query, very similar to a table in a relational database, or a DataSource structure.
     * @access protected
     * @var string
     */
    protected $dataSource;
    /**
     * A JSON list of dimensions to do the groupBy over
     * @access protected
     * @var array
     */
    protected $dimensions;
    /**
     * Provides the functionality to sort and limit the set of results from a groupBy query
     * @see    http://druid.io/docs/0.6.154/LimitSpec.html
     * @access protected
     * @var stdClass
     */
    protected $limitSpec;
    /**
     * A JSON string identifying which rows from a groupBy query should be returned, by specifying conditions on aggregated values.
     * @access protected
     * @var stdClass
     */
    protected $having;
    /**
     * Defines the granularity of the query
     * @access protected
     * @var string
     */
    protected $granularity;
    /**
     * A filter is a JSON string indicating which rows of data should be included in the computation for a query.
     * @access protected
     * @var stdClass
     */
    protected $filter;
    /**
     * Specifications of processing over metrics available in Druid
     * @access protected
     * @var array
     */
    protected $aggregations;
    /**
     * Specifications of processing that should happen on aggregated values as they come out of Druid
     * @access protected
     * @var array
     */
    protected $postAggregations;
    /**
     * A JSON string representing ISO-8601 Intervals. This defines the time ranges to run the query over
     * @access protected
     * @var array
     */
    protected $intervals;
    /**
     * An additional JSON Object which can be used to specify certain flags.
     * @access protected
     * @var array
     */
    protected $context;
    /**
     * List of all parameters. Needed to create the JSON string
     * @access protected
     * @var array
     */
    protected $allParameters = array('queryType', 'dataSource', 'dimensions', 'limitSpecs', 'having', 'granularity', 'filter', 'aggregations', 'postAggregations', 'intervals', 'context');
    /**
     * Array of required params
     * @access protected
     * @var array
     */
    protected $requiredParams = array('queryType', 'dataSource', 'dimensions', 'granularity', 'aggregations', 'intervals');
    /**
     * Stores the missing parameters
     * @access protected
     * @var array
     */
    protected $missingParameters = array();
    /**
     * Stores any empty parameter
     * @access protected
     * @var array
     */
    protected $emptyParameters = array();

    /**
     * Class initializer
     */
    protected function initialize()
    {
        $this->queryType = 'groupBy';
    }

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Validates the query has the necessary parameters
     *
     * @throws MissingParametersException
     */
    public function validate()
    {
        $flag = true;
        foreach($this->requiredParams as $param)
        {
            if(!isset($this->$param))
            {
                $this->missingParameters[] = $param;
                $flag                      = false;
            }
            else
            {
                $val = $this->$param;
                if(!is_array($val)){
                    $val = trim($val);
                }
                if(empty($val))
                {
                    $this->emptyParameters[] = $param;
                    $flag                    = false;
                }
            }
        }
        if(count($this->missingParameters) > 0)
        {
            throw new MissingParametersException($this->missingParameters);
        }
        if(count($this->emptyParameters) > 0)
        {
            throw new EmptyParametersException($this->emptyParameters);
        }
        return $flag;
    }

    /**
     * Converts the current object into a JSON representation to be used as a query
     * @return mixed|string
     */
    public function getJSONString(){
        $retString = '{[DATA]}';
        $buffStringArray = array();
        foreach($this->allParameters as $param) {
            if(isset($this->$param)) {
                $buffStringArray[] = "\"{$param}\":" . json_encode($this->$param);
            }
        }
        $buffString = implode(',',$buffStringArray);
        $retString = str_replace('[DATA]',$buffString,$retString);
        return $retString;
    }

    /**
     * Returns the aggregations
     *
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Sets the aggregations
     *
     * @param array $aggregations
     *
     * @return $this
     */
    public function setAggregations(array $aggregations)
    {
        foreach($aggregations as $aggregator)
        {
            $this->addAggregator($aggregator);
        }
        return $this;
    }

    /**
     * Adds a new aggregator to the aggregators array
     *
     * @param stdClass $aggregator
     *
     * @return $this
     */
    public function addAggregator($aggregator)
    {
        if(is_object($aggregator))
        {
            $this->aggregations[] = $aggregator;
        }
        return $this;
    }

    /**
     * Returns the context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Sets the context
     *
     * @param array $contexts
     *
     * @return $this
     */
    public function setContext(array $contexts)
    {
        foreach($contexts as $context)
        {
            $this->addContext($context);
        }
        return $this;
    }

    /**
     * Adds a new context to the contexts array
     *
     * @param string $context
     *
     * @return $this
     */
    public function addContext($context)
    {
        $this->context[] = $context;
        return $this;
    }

    /**
     * Returns the dataSource
     *
     * @return string
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * Sets the dataSource
     *
     * @param string $dataSource
     *
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * Returns the dimensions
     *
     * @return array
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Sets the dimensions
     *
     * @param array $dimensions
     *
     * @return $this
     */
    public function setDimensions(array $dimensions)
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    /**
     * Adds a new dimension to the dimensions array
     *
     * @param string $dimension
     *
     * @return $this
     */
    public function addDimension($dimension)
    {
        $this->dimensions[] = $dimension;
        return $this;
    }

    /**
     * Returns the filter
     *
     * @return stdClass
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Sets the filter
     *
     * @param stdClass $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        if(is_object($filter))
        {
            $this->filter = $filter;
        }
        return $this;
    }

    /**
     * Returns the granularity
     *
     * @return string
     */
    public function getGranularity()
    {
        return $this->granularity;
    }

    /**
     * Sets the granularity
     *
     * @param string $granularity
     *
     * @return $this
     */
    public function setGranularity($granularity)
    {
        $this->granularity = $granularity;
        return $this;
    }

    /**
     * Returns the having
     *
     * @return stdClass
     */
    public function getHaving()
    {
        return $this->having;
    }

    /**
     * Sets the having
     *
     * @param stdClass $having
     *
     * @return $this
     */
    public function setHaving($having)
    {
        if(is_object($having))
        {
            $this->having = $having;
        }
        return $this;
    }

    /**
     * Returns the intervals
     *
     * @return array
     */
    public function getIntervals()
    {
        return $this->intervals;
    }

    /**
     * Sets the intervals
     *
     * @param array $intervals
     *
     * @return $this
     */
    public function setIntervals(array $intervals)
    {
        foreach($intervals as $interval)
        {
            $this->addInterval($interval);
        }
        return $this;
    }

    /**
     * Adds a new interval to the intervals array
     *
     * @param string $interval
     *
     * @return $this
     */
    public function addInterval($interval)
    {
        $this->intervals[] = $interval;
        return $this;
    }

    /**
     * Returns the limitSpec
     *
     * @return stdClass
     */
    public function getLimitSpec()
    {
        return $this->limitSpec;
    }

    /**
     * Sets the limitSpec
     *
     * @param stdClass $limitSpec
     *
     * @return $this
     */
    public function setLimitSpec($limitSpec)
    {
        $this->limitSpec = $limitSpec;
        return $this;
    }

    /**
     * Returns the postAggregations
     *
     * @return array
     */
    public function getPostAggregations()
    {
        return $this->postAggregations;
    }

    /**
     * Sets the postAggregations
     *
     * @param array $postAggregations
     *
     * @return $this
     */
    public function setPostAggregations(array $postAggregations)
    {
        foreach($postAggregations as $aggregator)
        {
            $this->addPostAggregator($aggregator);
        }
        return $this;
    }

    /**
     * Adds a new postaggregator to the postaggregators array
     *
     * @param stdClass $aggregator
     *
     * @return $this
     */
    public function addPostAggregator($aggregator)
    {
        if(is_object($aggregator))
        {
            $this->postAggregations[] = $aggregator;
        }
        return $this;
    }
}