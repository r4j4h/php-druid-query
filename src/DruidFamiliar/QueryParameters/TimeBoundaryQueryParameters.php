<?php

namespace DruidFamiliar\QueryParameters;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

class TimeBoundaryQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{
    /**
     * DataSource Name
     * @var string
     */
    public $dataSource;

    public function __construct($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @throws MissingParametersException
     */
    public function validate()
    {
        $missingParams = array();

        if(!isset($this->dataSource))
        {
            $missingParams[] = 'dataSource';
        }

        if(count($missingParams) > 0)
        {
            throw new MissingParametersException($missingParams);
        }

        return true;
    }
}