<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;

class TimeBoundaryDruidQueryGenerator implements IDruidQueryGenerator
{

    /**
     * Take parameters and return a valid Druid Query.
     *
     * @param TimeBoundaryQueryParameters $params
     * @return array Druid JSON POST Body in PHP Array form
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var TimeBoundaryQueryParameters $params
         */
        if ( !$params instanceof TimeBoundaryQueryParameters ) {
            throw new \Exception('Expected $params to be instanceof TimeBoundaryQueryParameters');
        }

        return array(
            'queryType' => 'timeBoundary',
            "dataSource" => $params->dataSource
        );

    }

}