<?php

namespace DruidFamiliar;

interface IDruidQueryExecutor
{

    public function executeQuery(IDruidQuery $query, $params);

    public function handleResponse($response = Array()) {


    }
