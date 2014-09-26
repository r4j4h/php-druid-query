<?php

namespace DruidFamiliar\Exception;

use Exception;

/**
 * Class EmptyParametersException
 * @package   DruidFamiliar\Exception
 * @author    Jasmine Hegman
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class EmptyParametersException extends Exception
{
    /**
     * Array of missing parameter keys
     *
     * @var array
     */
    public $emptyParameters = array();

    /**
     * Class constructor
     * @param array      $emptyParameters Array of strings representing the missing parameters
     * @param string     $message           [optional] Override the default exception message to throw.
     * @param \Exception $previous          [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(array $emptyParameters, $message = NULL, Exception $previous = NULL)
    {
        $this->missingParameters = $emptyParameters;
        parent::__construct("Empty parameters: " . join(", ", $this->emptyParameters), count($this->emptyParameters), $previous);
    }
}