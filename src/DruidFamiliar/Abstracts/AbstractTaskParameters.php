<?php
namespace DruidFamiliar\Abstracts;

use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

/**
 * Represents parameters used in generation of Druid POST bodies for indexing tasks and querying tasks.
 *
 * Class AbstractTaskParameters
 * @package DruidFamiliar
 */
abstract class AbstractTaskParameters implements IDruidQueryParameters
{

    /**
     * @return bool
     */
    public function isValid()
    {
        try
        {
            $this->validate();
        }
        catch(MissingParametersException $e)
        {
            return false;
        }

        return true;
    }

}
