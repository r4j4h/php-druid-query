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
class DruidTime
{

    /**
     * ISO Time
     * @var DateTime
     */
    protected $time;

    public function __construct(DateTime $time)
    {
        $this->setTime($time);
    }

    public function __toString()
    {
        // Format
        return $this->formatTimeForDruid();
    }

    public function formatTimeForDruid()
    {
        // Missing params
        $missingParams = array();
        if ( !isset( $this->time ) )   { $missingParams[] = 'time'; }
        if ( count( $missingParams ) > 0 ) { throw new MissingParametersException($missingParams); }

        // Invalid params
        if ( !$this->time instanceof DateTime ) {
            throw new UnexpectedTypeException($this->time, 'DateTime', 'For parameter time.');
        }

        // Format
        return $this->time->format("Y-m-d\TH:i:s\Z");
    }

    public function setTime(DateTime $time)
    {
        $this->time = $time;
    }

    public function getTime()
    {
        return $this->time;
    }

}