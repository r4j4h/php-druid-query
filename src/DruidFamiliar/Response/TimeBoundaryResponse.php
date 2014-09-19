<?php

namespace DruidFamiliar\Response;


class TimeBoundaryResponse
{

    /**
     * ISO Time of beginning of time boundary.
     * @var string
     */
    public $minTime;

    /**
     * ISO Time of end of time boundary.
     * @var string
     */
    public $maxTime;

}