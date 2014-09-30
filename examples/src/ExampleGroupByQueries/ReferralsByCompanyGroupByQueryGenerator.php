<?php

namespace DruidFamiliar\ExampleGroupByQueries;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

class ReferralsByCompanyGroupByQueryGenerator implements IDruidQueryGenerator
{

    /**
     * As opposed to building the query body in PHP associative arrays, one could also template them.
     *
     * @var string
     */
    private $queryTemplate = <<<QUERYTEMPLATE
{
    "queryType": "groupBy",
    "dataSource": "{DATASOURCE}",
    "granularity": { "type": "period", "period": "P3M"},
    "dimensions": ["company_id", "facility_id", "referral_count"],
    "aggregations": [
        {"type": "count", "name": "total_entries"},
        {"type": "longSum", "name": "total_referral_countb", "fieldName": "referral_count"}
    ],
    "intervals": ["{STARTINTERVAL}/{ENDINTERVAL}"]
}
QUERYTEMPLATE;


    /**
     * Take parameters and return a valid Druid Query.
     *
     * @param ReferralsByCompanyGroupByQueryParameters $params
     * @return string Query payload in JSON
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        // @var ReferralsByCompanyGroupByQueryParameters $params
        $query = $this->queryTemplate;

        $query = str_replace('{DATASOURCE}',    $params->dataSource,    $query);
        $query = str_replace('{STARTINTERVAL}', $params->startInterval, $query);
        $query = str_replace('{ENDINTERVAL}',   $params->endInterval,   $query);

        return $query;
    }

}