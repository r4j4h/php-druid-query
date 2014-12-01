<?php

namespace DruidFamiliar\QueryGenerator;

use DateTime;
use DruidFamiliar;
use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;

/**
 * Class SegmentMetadataDruidQueryGenerator generates Segment Metadata queries intended for use with Druid.
 *
 * TODO Handle optional bounds parameter
 *
 * @package DruidFamiliar\QueryGenerator
 */
class SegmentMetadataDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @param SegmentMetadataQueryParameters $params
     * @return string Query payload in JSON
     * @throws \DruidFamiliar\Exception\MissingParametersException
     */
    public function generateQuery(IDruidQueryParameters $params)
    {
        /**
         * @var SegmentMetadataQueryParameters $params
         */
        if ( !$params instanceof SegmentMetadataQueryParameters ) {
            throw new \Exception('Expected $params to be instanceof SegmentMetadataQueryParameters');
        }

        $params->validate();

        $responseObj = array(
            'queryType' => 'segmentMetadata',
            "dataSource" => $params->dataSource,
            "intervals" => $params->intervals->__toString()
        );

        $responseString = json_encode( $responseObj );

        return $responseString;
    }
}
