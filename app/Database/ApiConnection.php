<?php

namespace App\Database;

use Illuminate\Database\Connection;
use App\Database\Query\ApiBuilder;

class ApiConnection extends Connection
{
    public function query()
    {
        return new ApiBuilder(
            $this,
            $this->getQueryGrammar(),
            $this->getPostProcessor()
        );
    }

    public function getDriverName()
    {
        return 'api';
    }
}
