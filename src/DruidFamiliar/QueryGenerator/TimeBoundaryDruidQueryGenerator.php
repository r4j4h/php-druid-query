<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;
use Exception;

class TimeBoundaryDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @param TimeBoundaryQueryParameters $params
     * @return string Query payload in JSON
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var TimeBoundaryQueryParameters $params
         */
        if ( !$params instanceof TimeBoundaryQueryParameters ) {
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
