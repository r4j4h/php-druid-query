<?php
namespace DruidFamiliar\Interfaces;

/**
 * Interface IDruidQueryExecutor executes a given Query via a QueryGenerator paired with parameters.
 *
 * @package DruidFamiliar\Interfaces
 */
interface IDruidQueryExecutor
{
    /**
     * Execute a Druid query using the provided query generator, parameters, and response payload handler.
     *
     * See DruidFamiliar\ResponseHandler\DoNothingResponseHandler.
     *
     * @param IDruidQueryGenerator $queryGenerator
     * @param IDruidQueryParameters $params
     * @param IDruidQueryResponseHandler $responseHandler
     * @return mixed
     */
    public function executeQuery(IDruidQueryGenerator $queryGenerator, IDruidQueryParameters $params, IDruidQueryResponseHandler $responseHandler);
}