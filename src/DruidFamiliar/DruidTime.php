<?php


namespace DruidFamiliar;
use DateTime;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Exception\UnexpectedTypeException;
use RuntimeException;


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

    /**
     * @param string|DateTime $time
     */
    public function __construct($time)
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

    /**
     * @param string|DateTime $time
     * @throws RuntimeException
     */
    public function setTime($time)
    {

        if ( is_string($time ) )
        {
            $time = new DateTime( $time );
        }

        if ( is_a($time, 'DateTime') )
        {
            $this->time = $time;
        }
        else if ( is_a($time, 'DruidFamiliar\DruidTime') )
        {
            /** @var \DruidFamiliar\DruidTime $time */
            $this->time = $time->getTime();
        }
        else
        {
            throw new RuntimeException('Encountered unexpected time. Expected either string or DateTime.');
        }

    }

    public function getTime()
    {
        return $this->time;
    }

}