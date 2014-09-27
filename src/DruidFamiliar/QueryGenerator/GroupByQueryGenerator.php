<?php

namespace DruidFamiliar\QueryGenerator;

use Exception;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\QueryParameters\GroupByQueryParameters;

/**
 * Class GroupByQueryGenerator
 * @package   DruidFamiliar\QueryGenerator
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Generate a Druid query's JSON POST body
     *
     * @param IDruidQueryParameters $params
     *
     * @return string|void
     * @throws Exception
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        if(!$params instanceof GroupByQueryParameters)
        {
            throw new Exception('Expected $params to be instanceof SimpleGroupByQueryParameters');
        }

        $params->validate();
        $query = $params->getJSONString();
        return $query;
    }
}