<?php

namespace DruidFamiliar\QueryGenerator;

use DateTime;
use DruidFamiliar;
use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;

class SegmentMetadataDruidQueryGenerator implements IDruidQueryGenerator
{

    /**
     * Take parameters and return a valid Druid Query.
     *
     * @param SegmentMetadataQueryParameters $params
     * @return array Druid JSON POST Body in PHP Array form
     * @throws \DruidFamiliar\Exception\MissingParametersException
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        // @var SegmentMetadataQueryParameters $params
        return array(
            'queryType' => 'segmentMetadata',
            "dataSource" => $params->dataSource,
            "intervals" => $params->getIntervalsValue()
        );

    }

}