<?php

namespace DruidFamiliar;

interface IDruidQueryExecutor
{

    public function executeQuery(IDruidQuery $query, $params);

}
