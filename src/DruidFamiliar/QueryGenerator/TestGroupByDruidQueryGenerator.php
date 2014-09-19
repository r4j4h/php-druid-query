<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters;

class TestGroupByDruidQueryGenerator implements IDruidQueryGenerator
{

    /**
     * As opposed to building the query body in PHP associative arrays, one could also template them.
     *
     * TODO groupBy => "{INDEXTYPE}"
     * @var string
     */
    private $queryTemplate = <<<QUERYTEMPLATE
{
    "queryType": "{QUERYTYPE}",
    "dataSource": "{DATASOURCE}",
    "granularity": "{GRANULARITYSPEC.GRAN}",
    "dimensions": [ "{NON_TIME_DIMENSIONS}" ],
    "aggregations": [{AGGREGATORS}],
    "postAggregations": [{POSTAGGREGATORS}],
    "intervals": ["{STARTINTERVAL}/{ENDINTERVAL}"]
}
QUERYTEMPLATE;


    /**
     * Take parameters and return a valid Druid Query.
     *
     * @param array $params
     * @return string Druid JSON POST Body
     * @throws \DruidFamiliar\Exception\MissingParametersException
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var SimpleGroupByQueryParameters $params
         */
        if ( !$params instanceof SimpleGroupByQueryParameters ) {
            throw new \Exception('Expected $params to be instanceof SimpleGroupByQueryParameters');
        }

        $query = $this->queryTemplate;

        $query = str_replace('{QUERYTYPE}',             "groupBy",                                  $query);

        $query = str_replace('{DATASOURCE}',            $params->dataSource,                  $query);
        $query = str_replace('{STARTINTERVAL}',         $params->intervalStart,               $query);
        $query = str_replace('{ENDINTERVAL}',           $params->intervalEnd,                 $query);


        $query = str_replace('{GRANULARITYSPEC.GRAN}',  $params->granularity,                 $query);
        $query = str_replace('{NON_TIME_DIMENSIONS}',   join(",", $params->dimensions),       $query);
        $query = str_replace('{AGGREGATORS}',           join(",", $params->aggregators),      $query);
        $query = str_replace('{POSTAGGREGATORS}',       join(",", $params->postAggregators),  $query);

        return $query;
    }

}