<?php
namespace DruidFamiliar\Interfaces;

use DruidFamiliar\Exception\MissingParametersException;

/**
 * Interface IDruidQueryParameters represents the available parameters for a Druid query.
 *
 * @package DruidFamiliar\Interfaces
 */
interface IDruidQueryParameters
{

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @throws MissingParametersException
     */
    public function validate();

}