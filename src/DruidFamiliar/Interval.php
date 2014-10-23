<?php


namespace DruidFamiliar;

use DateTime;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Exception\UnexpectedTypeException;

/**
 * Class Interval represents Web ISO style date ranges for use in Druid queries.
 *
 * @package DruidFamiliar
 */
class Interval
{
    /**
     * ISO Time
     * @var DateTime
     */
    public $intervalStart;

    /**
     * ISO Time
     * @var DateTime
     */
    public $intervalEnd;


    function __construct($intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $this->setInterval($intervalStart, $intervalEnd);
    }


    public function setInterval($intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $this->setStart($intervalStart);
        $this->setEnd($intervalEnd);
    }

    public function setStart($intervalStart = "1970-01-01 01:30:00")
    {
        $startDateTime       = new DateTime($intervalStart);
        $this->intervalStart = $startDateTime;
    }

    public function setEnd($intervalEnd = "3030-01-01 01:30:00")
    {
        $endDateTime       = new DateTime($intervalEnd);
        $this->intervalEnd = $endDateTime;
    }


    function __toString()
    {
        return $this->getIntervalsString();
    }

    public function getIntervalsString()
    {
        // Missing params
        $missingParams = array();
        if(!isset($this->intervalStart))
        {
            $missingParams[] = 'intervalStart';
        }
        if(!isset($this->intervalEnd))
        {
            $missingParams[] = 'intervalEnd';
        }
        if(count($missingParams) > 0)
        {
            throw new MissingParametersException($missingParams);
        }

        // Invalid params
        if(!$this->intervalStart instanceof DateTime)
        {
            throw new UnexpectedTypeException($this->intervalStart, 'DateTime', 'For parameter intervalStart.');
        }
        if(!$this->intervalEnd instanceof DateTime)
        {
            throw new UnexpectedTypeException($this->intervalEnd, 'DateTime', 'For parameter intervalEnd.');
        }

        // Format
        return $this->intervalStart->format("Y-m-d\TH:i:s\Z") . '/' . $this->intervalEnd->format("Y-m-d\TH:i:s\Z");
    }

    public function getStart()
    {
        return $this->intervalStart->format("Y-m-d\TH:i:s\Z");
    }

    public function getEnd()
    {
        return $this->intervalEnd->format("Y-m-d\TH:i:s\Z");
    }

}