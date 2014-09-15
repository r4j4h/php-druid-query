<?php

namespace DruidFamiliar\ExampleGroupByQueries;

class ReferralsByCompanyGroupByWithResponseObject extends ReferralsByCompanyGroupBy
{

    /**
     * Hook function to handle response from server.
     *
     * This hook must return the response, whether changed or not, so that the rest of the system can continue with it.
     *
     * @param array $response
     * @return array of ExampleReferralByCompanyResponseObject objects
     */
    public function handleResponse($response = Array())
    {
        $responseArray = array();

        foreach ( $response as $index => $chunk)
        {
            $timestamp = $chunk['timestamp'];
            $companyId = $chunk['event']['company_id'];
            $facilityId = $chunk['event']['facility_id'];
            $referrals = $chunk['event']['referral_count'];

            $dto = new \DruidFamiliar\ExampleResponseObjects\ExampleReferralByCompanyResponseObject();
            $dto->setCompanyId( $companyId );
            $dto->setFacilityId( $facilityId );
            $dto->setReferrals( $referrals );
            $dto->setTimestamp( $timestamp );

            $responseArray[] = $dto;
        }

        return $responseArray;
    }


}