<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
    public function insert(array $transactions): void;
}
