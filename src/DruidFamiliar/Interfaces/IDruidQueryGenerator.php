<?php

namespace DruidFamiliar\Interfaces;

/**
 * Interface IDruidQueryGenerator takes parameters and returns a JSON string ready for submission to Druid.
 *
 * @package DruidFamiliar\Interfaces
 */
interface IDruidQueryGenerator
{

    /**
     * Generate a Druid query's JSON POST body
     *
     * @param IDruidQueryParameters $params
     *
     * @return string Query payload in JSON
     */
    public function generateQuery(IDruidQueryParameters $params);
}