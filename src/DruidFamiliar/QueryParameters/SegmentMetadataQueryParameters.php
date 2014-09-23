<?php

namespace DruidFamiliar\QueryParameters;

use DateTime;
use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Exception\UnexpectedTypeException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interval;

class SegmentMetadataQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{
    /**
     * DataSource Name
     * @var string
     */
    public $dataSource;

    /**
     * @var Interval
     */
    public $intervals;


    function __construct($dataSource, $intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $this->dataSource = $dataSource;
        $this->intervals = new Interval($intervalStart, $intervalEnd);
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
        if ( !isset( $this->intervals ) ) {
            $missingParams[] = 'interval';
        }

        if ( count( $missingParams ) > 0 ) {
            throw new MissingParametersException($missingParams);
        }

        if ( !$this->intervals instanceof Interval ) {
            throw new UnexpectedTypeException($this->intervals, 'DruidFamiliar\Interval', 'For parameter intervals.');
        }

        return true;
    }
}