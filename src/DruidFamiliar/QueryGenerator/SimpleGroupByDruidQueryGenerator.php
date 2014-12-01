<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters;

class SimpleGroupByDruidQueryGenerator implements IDruidQueryGenerator
{

    /**
     * As opposed to building the query body in PHP associative arrays, one could also template them.
     *
     * @var string
     */
    private $queryTemplate = <<<QUERYTEMPLATE
{
    "queryType": "{QUERYTYPE}",
    "dataSource": "{DATASOURCE}",
    "granularity": "{GRANULARITYSPEC.GRAN}",
    "dimensions": [ "{NON_TIME_DIMENSIONS}" ],
    "filter": [{FILTERS}],
    "aggregations": [{AGGREGATORS}],
    "postAggregations": [{POSTAGGREGATORS}],
    "intervals": ["{INTERVALS}"]
}
QUERYTEMPLATE;


    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @param array $params
     * @return string Druid JSON POST Body
     * @throws \DruidFamiliar\Exception\MissingParametersException
     * @throws \Exception
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var SimpleGroupByQueryParameters $params
         */
        if ( !$params instanceof SimpleGroupByQueryParameters ) {
            throw new Exception('Expected $params to be instanceof SimpleGroupByQueryParameters');
        }

        $params->validate();

        $query = $this->queryTemplate;

        // Assemble the query instead
        $queryKeys = array();

        // We always have these keys
        $queryKeys[] =  '"queryType": "{QUERYTYPE}"';
        $queryKeys[] =  '"dataSource": "{DATASOURCE}"';

        if ( is_array( $params->granularity ) && count( $params->granularity ) > 0 ) {
            $queryKeys[] =  '"granularity": {GRANULARITYSPEC.GRAN}';
        } else {
            $queryKeys[] = '"granularity": "{GRANULARITYSPEC.GRAN}"';
        }

        $queryKeys[] =  '"dimensions": [ "{NON_TIME_DIMENSIONS}" ]';

        if ( count( $params->filters ) > 0 ) {
            $queryKeys[] =  '"filter": {FILTERS}';
        }

        if ( count( $params->aggregators ) > 0 ) {
            $queryKeys[] =  '"aggregations": [{AGGREGATORS}]';
        }

        if ( count( $params->postAggregators ) > 0 ) {
            $queryKeys[] =  '"postAggregations": [{POSTAGGREGATORS}]';
        }

        $queryKeys[] =  '"intervals": ["{INTERVALS}"]';

        $query = "{" . join(",\n", $queryKeys) . "}";


        $query = str_replace('{QUERYTYPE}',             $params->queryType,                   $query);

        $query = str_replace('{DATASOURCE}',            $params->dataSource,                  $query);
        $query = str_replace('{INTERVALS}',             $params->intervals,                   $query);


        if ( is_array( $params->granularity ) && count( $params->granularity ) > 0 ) {
            $query = str_replace('{GRANULARITYSPEC.GRAN}', json_encode( $params->granularity ), $query);
        } else {
            $query = str_replace('{GRANULARITYSPEC.GRAN}',  $params->granularity,               $query);
        }

        $query = str_replace('{NON_TIME_DIMENSIONS}',   join('","', $params->dimensions),     $query);
        $query = str_replace('{FILTERS}',               join(",", $params->filters),          $query);
        $query = str_replace('{AGGREGATORS}',           join(",", $params->aggregators),      $query);
        $query = str_replace('{POSTAGGREGATORS}',       join(",", $params->postAggregators),  $query);

        return $query;
    }

}
