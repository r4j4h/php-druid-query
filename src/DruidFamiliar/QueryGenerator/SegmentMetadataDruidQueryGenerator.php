<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;

class SegmentMetadataDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @return array|string
     * @throws DruidFamiliar\Exception\MissingParametersException
     * @throws DruidFamiliar\Exception\UnexpectedTypeException
     * @throws \Exception
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var SegmentMetadataQueryParameters $params
         */
        if(!$params instanceof SegmentMetadataQueryParameters)
        {
            throw new \Exception('Expected $params to be instanceof SegmentMetadataQueryParameters');
        }

        $params->validate();

        return array('queryType' => 'segmentMetadata', "dataSource" => $params->dataSource, "intervals" => $params->intervals);

    }
}