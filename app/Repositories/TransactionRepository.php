<?php

namespace App\Repositories;

use App\Models\Import;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function insert(array $transactions): void
    {
        if (empty($transactions)) {
            return;
        }

        Transaction::insert($transactions);
    }
}
