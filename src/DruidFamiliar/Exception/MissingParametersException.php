<?php

namespace DruidFamiliar\Exception;

use Exception;

/**
 * Class MissingParametersException
 * @package   DruidFamiliar\Exception
 * @author    Jasmine Hegman
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class MissingParametersException extends Exception
{
    /**
     * Array of missing parameter keys
     *
     * @var array
     */
    public $missingParameters = array();

    /**
     * @param array $missingParameters Array of strings representing the missing parameters
     * @param \Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(array $missingParameters, \Exception $previous = null) {
        $this->missingParameters = $missingParameters;
        parent::__construct("Missing parameters: " . join(", ", $this->missingParameters), count($this->missingParameters), $previous);
    }
}
