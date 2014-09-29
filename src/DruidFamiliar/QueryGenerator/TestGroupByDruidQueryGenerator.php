<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters;

class TestGroupByDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @return mixed|string
     * @throws \DruidFamiliar\Exception\MissingParametersException
     * @throws \Exception
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var SimpleGroupByQueryParameters $params
         */
        if(!$params instanceof SimpleGroupByQueryParameters)
        {
            throw new \Exception('Expected $params to be instanceof SimpleGroupByQueryParameters');
        }

        $params->validate();

        // Assemble the query
        $queryKeys = array();

        // We always have these keys
        $queryKeys[] = '"queryType": "{QUERYTYPE}"';
        $queryKeys[] = '"dataSource": "{DATASOURCE}"';
        $queryKeys[] = '"granularity": "{GRANULARITYSPEC.GRAN}"';
        $queryKeys[] = '"dimensions": [ "{NON_TIME_DIMENSIONS}" ]';

        if(count($params->aggregators) > 0)
        {
            $queryKeys[] = '"aggregations": [{AGGREGATORS}]';
        }

        if(count($params->postAggregators) > 0)
        {
            $queryKeys[] = '"postAggregations": [{POSTAGGREGATORS}]';
        }

        $queryKeys[] = '"intervals": ["{STARTINTERVAL}/{ENDINTERVAL}"]';

        $query = "{" . join(",\n", $queryKeys) . "}";

        $query = str_replace('{QUERYTYPE}', $params->queryType, $query);

        $query = str_replace('{DATASOURCE}', $params->dataSource, $query);
        $query = str_replace('{STARTINTERVAL}', $params->intervalStart, $query);
        $query = str_replace('{ENDINTERVAL}', $params->intervalEnd, $query);

        $query = str_replace('{GRANULARITYSPEC.GRAN}', $params->granularity, $query);
        $query = str_replace('{NON_TIME_DIMENSIONS}', join(",", $params->dimensions), $query);
        $query = str_replace('{AGGREGATORS}', join(",", $params->aggregators), $query);
        $query = str_replace('{POSTAGGREGATORS}', join(",", $params->postAggregators), $query);

        return $query;
    }
}