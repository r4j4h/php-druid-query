<?php

namespace DruidFamiliar\Exception;

class MissingParametersException extends \Exception {

    /**
     * Array of missing parameter keys
     *
     * @var array
     */
    public $missingParameters = array();

    /**
     * @param array $missingParameters Array of strings representing the missing parameters
     * @param string $message [optional] Override the default exception message to throw.
     * @param \Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(array $missingParameters, $message = null, \Exception $previous = null) {
        $this->missingParameters = $missingParameters;
        parent::__construct("Missing parameters: " . join(", ", $this->missingParameters), count($this->missingParameters), $previous);
    }

};
