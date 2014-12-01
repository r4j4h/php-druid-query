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
     * @var DruidTime
     */
    public $intervalStart;

    /**
     * ISO Time
     * @var DruidTime
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
        $startDruidTime = new DruidTime( new DateTime($intervalStart) );
        $this->intervalStart = $startDruidTime;
    }

    public function setEnd($intervalEnd = "3030-01-01 01:30:00")
    {
        $endDruidTime =  new DruidTime( new DateTime($intervalEnd) );
        $this->intervalEnd = $endDruidTime;
    }


    function __toString()
    {
        return $this->getIntervalsString();
    }

    public function getIntervalsString()
    {
        // Missing params
        $missingParams = array();
        if ( !isset( $this->intervalStart ) )   { $missingParams[] = 'intervalStart'; }
        if ( !isset( $this->intervalEnd ) )     { $missingParams[] = 'intervalEnd'; }
        if ( count( $missingParams ) > 0 ) { throw new MissingParametersException($missingParams); }

        // Invalid params
        if ( !$this->intervalStart instanceof DruidTime ) {
            throw new UnexpectedTypeException($this->intervalStart, 'DruidTime', 'For parameter intervalStart.');
        }
        if ( !$this->intervalEnd instanceof DruidTime ) {
            throw new UnexpectedTypeException($this->intervalEnd, 'DruidTime', 'For parameter intervalEnd.');
        }

        // Format
        return $this->intervalStart . '/' . $this->intervalEnd;
    }

    public function getStart()
    {
        return $this->intervalStart;
    }

    public function getEnd()
    {
        return $this->intervalEnd;
    }

}
