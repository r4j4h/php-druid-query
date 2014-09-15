<?php

namespace DruidFamiliar\ExampleResponseObjects;

class ExampleReferralByCompanyResponseObject
{

    public $timestamp;

    public $companyId;

    public $facilityId;

    public $referrals;

    function __construct($companyId, $facilityId, $referrals, $timestamp)
    {
        $this->companyId = $companyId;
        $this->facilityId = $facilityId;
        $this->referrals = $referrals;
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return mixed
     */
    public function getFacilityId()
    {
        return $this->facilityId;
    }

    /**
     * @return mixed
     */
    public function getReferrals()
    {
        return $this->referrals;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

}