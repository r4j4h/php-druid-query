<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;
use Exception;

/**
 * Class TimeBoundaryDruidQueryGenerator
 * @package   DruidFamiliar\QueryGenerator
 * @author    Jsamine Hegman
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class TimeBoundaryDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @return string
     * @throws Exception
     * @throws MissingParametersException
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var TimeBoundaryQueryParameters $params
         */
        if(!$params instanceof TimeBoundaryQueryParameters)
        {
            throw new Exception('Expected $params to be instanceof TimeBoundaryQueryParameters');
        }

        $params->validate();

        $responseObj = array(
            'queryType' => 'timeBoundary',
            "dataSource" => $params->dataSource
        );

        $responseString = json_encode( $responseObj );

        return $responseString;
    }
}