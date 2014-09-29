<?php

namespace DruidFamiliar\Response;

/**
 * Class TimeBoundaryResponse
 * @package   DruidFamiliar\Response
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
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