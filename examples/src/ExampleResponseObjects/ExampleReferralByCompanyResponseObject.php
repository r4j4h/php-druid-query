<?php

namespace DruidFamiliar\ExampleResponseObjects;

class ExampleReferralByCompanyResponseObject
{

    public $timestamp;

    public $companyId;

    public $facilityId;

    public $referrals;


    /**
     * @param mixed $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }


    /**
     * @param mixed $facilityId
     */
    public function setFacilityId($facilityId)
    {
        $this->facilityId = $facilityId;
    }

    /**
     * @return mixed
     */
    public function getFacilityId()
    {
        return $this->facilityId;
    }


    /**
     * @param mixed $referrals
     */
    public function setReferrals($referrals)
    {
        $this->referrals = $referrals;
    }

    /**
     * @return mixed
     */
    public function getReferrals()
    {
        return $this->referrals;
    }


    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

}