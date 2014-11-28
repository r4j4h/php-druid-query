<?php

/*
 * This file is copied from the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DruidFamiliar\Exception;

use Exception;

class UnexpectedTypeException extends Exception
{
    public function __construct($value, $expectedType, $extraMessage = "", $previous = NULL)
    {
        $message = sprintf('Expected argument of type "%s", "%s" given.', $expectedType, is_object($value) ? get_class($value) : gettype($value));

        if($extraMessage)
        {
            $message .= " " . $extraMessage;
        }
        parent::__construct( $message, 0, $previous );
    }
}
