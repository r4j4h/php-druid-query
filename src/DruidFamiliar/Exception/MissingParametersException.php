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
     * Class constructor
     *
     * @param array      $missingParameters Array of strings representing the missing parameters
     * @param string     $message           [optional] Override the default exception message to throw.
     * @param \Exception $previous          [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(array $missingParameters, $message = NULL, Exception $previous = NULL)
    {
        $this->missingParameters = $missingParameters;
        parent::__construct("Missing parameters: " . join(", ", $this->missingParameters), count($this->missingParameters), $previous);
    }
}