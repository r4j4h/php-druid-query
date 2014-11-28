<?php

namespace DruidFamiliar\QueryGenerator;

use DruidFamiliar;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use Exception;

/**
 * Class SegmentMetadataDruidQueryGenerator
 * Class SegmentMetadataDruidQueryGenerator generates Segment Metadata queries intended for use with Druid.
 *
 * TODO Handle optional bounds parameter
 *
 * @package DruidFamiliar\QueryGenerator
 * @author Jasmine Hegman
 * @version 1.0
 * @category WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class SegmentMetadataDruidQueryGenerator implements IDruidQueryGenerator
{
    /**
     * Take parameters and return a valid Druid Query.
     * @param IDruidQueryParameters $params
     *
     * @return string
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
            throw new Exception('Expected $params to be instanceof SegmentMetadataQueryParameters');
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