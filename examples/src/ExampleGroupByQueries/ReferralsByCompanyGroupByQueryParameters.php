<?php

namespace DruidFamiliar\ExampleGroupByQueries;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Interfaces\IDruidQueryParameters;

class ReferralsByCompanyGroupByQueryParameters extends AbstractTaskParameters implements IDruidQueryParameters
{
    /**
     * DataSource Name
     * @var string
     */
    public $dataSource;

    /**
     * ISO Time of Window Start Time
     *
     * @var string
     */
    public $startInterval;

    /**
     * ISO Time of Window End Time
     *
     * @var string
     */
    public $endInterval;

    function __construct($dataSource, $startInterval, $endInterval)
    {
        $this->dataSource = $dataSource;
        $this->startInterval = $startInterval;
        $this->endInterval = $endInterval;
    }

    /**
     * @throws MissingParametersException
     */
    public function validate()
    {
        $missingParams = array();

        if ( !isset( $this->dataSource ) ) {
            $missingParams[] = 'dataSource';
        }
        if ( !isset( $this->startInterval ) ) {
            $missingParams[] = 'startInterval';
        }
        if ( !isset( $this->endInterval ) ) {
            $missingParams[] = 'endInterval';
        }

        if ( count( $missingParams ) > 0 ) {
            throw new MissingParametersException($missingParams);
        }

        return true;
    }
}