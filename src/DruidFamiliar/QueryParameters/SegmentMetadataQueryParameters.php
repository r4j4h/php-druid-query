<?php

namespace DruidFamiliar\QueryParameters;

use DateTime;
use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

class SegmentMetadataQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{
    /**
     * DataSource Name
     * @var string
     */
    public $dataSource;

    /**
     * ISO Time
     * @var string
     */
    public $intervalStart;

    /**
     * ISO Time
     * @var string
     */
    public $intervalEnd;


    function __construct($dataSource, $intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $startDateTime = new DateTime($intervalStart);
        $endDateTime = new DateTime($intervalEnd);

        $ISOstartDateTime = $startDateTime->format(DateTime::ISO8601);
        $ISOendDateTime = $endDateTime->format(DateTime::ISO8601);

        $this->dataSource = $dataSource;
        $this->intervalStart = $intervalStart;
        $this->intervalEnd = $intervalEnd;
        $this->intervals = $this->getIntervalsValue();
    }

    /**
     * @throws MissingParametersException
     */
    public function getIntervalsValue()
    {
        $missingParams = array();

        if ( !isset( $this->intervalStart ) ) {
            $missingParams[] = 'intervalStart';
        }
        if ( !isset( $this->intervalEnd ) ) {
            $missingParams[] = 'intervalEnd';
        }

        if ( count( $missingParams ) > 0 ) {
            throw new MissingParametersException($missingParams);
        }

        return $this->intervalStart . '/' . $this->intervalEnd;
    }


    /**
     * @throws MissingParametersException
     */
    public function validate()
    {
        $missingParams = array();

        if ( !isset( $this->dataSource ) ) {
            $missingParams[] = 'dataSource';
        }
        if ( !isset( $this->intervalStart ) ) {
            $missingParams[] = 'intervalStart';
        }
        if ( !isset( $this->intervalEnd ) ) {
            $missingParams[] = 'intervalEnd';
        }

        if ( count( $missingParams ) > 0 ) {
            throw new MissingParametersException($missingParams);
        }

        return true;
    }
}