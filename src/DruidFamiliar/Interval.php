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


    /**
     * @param string|DateTime|DruidTime $intervalStart
     * @param string|DateTime|DruidTime $intervalEnd
     */
    function __construct($intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $this->setInterval($intervalStart, $intervalEnd);
    }


    /**
     * @param string|DateTime|DruidTime $intervalStart
     * @param string|DateTime|DruidTime $intervalEnd
     */
    public function setInterval($intervalStart = "1970-01-01 01:30:00", $intervalEnd = "3030-01-01 01:30:00")
    {
        $this->setStart($intervalStart);
        $this->setEnd($intervalEnd);
    }

    /**
     * @param string|DateTime|DruidTime $intervalStart
     */
    public function setStart($intervalStart = "1970-01-01 01:30:00")
    {
        if ( is_string($intervalStart ) )
        {
            $intervalStart = new DateTime( $intervalStart );
        }

        if ( is_a($intervalStart, 'DateTime') )
        {
            $intervalStart = new DruidTime( $intervalStart );
        }

        if ( is_a($intervalStart, 'DruidFamiliar\DruidTime') )
        {
            $this->intervalStart = $intervalStart;
        }
        else
        {
            throw new RuntimeException('Encountered unexpected start time. Expected either string, DateTime, or DruidTime.');
        }
    }

    /**
     * @param string|DateTime|DruidTime $intervalEnd
     */
    public function setEnd($intervalEnd = "3030-01-01 01:30:00")
    {
        if ( is_string($intervalEnd ) )
        {
            $intervalEnd = new DateTime( $intervalEnd );
        }

        if ( is_a($intervalEnd, 'DateTime') )
        {
            $intervalEnd = new DruidTime( $intervalEnd );
        }

        if ( is_a($intervalEnd, 'DruidFamiliar\DruidTime') )
        {
            $this->intervalEnd = $intervalEnd;
        }
        else
        {
            throw new RuntimeException('Encountered unexpected end time. Expected either string, DateTime, or DruidTime.');
        }
    }


    /**
     * @return string
     * @throws MissingParametersException
     * @throws UnexpectedTypeException
     */
    function __toString()
    {
        return $this->getIntervalsString();
    }

    /**
     * @return string
     * @throws MissingParametersException
     * @throws UnexpectedTypeException
     */
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


    /**
     * @return DruidTime
     */
    public function getStart()
    {
        return $this->intervalStart;
    }

    /**
     * @return DruidTime
     */
    public function getEnd()
    {
        return $this->intervalEnd;
    }

}
