<?php

namespace App\Repositories;

use App\Models\Import;
use Illuminate\Support\Facades\DB;

class ImportRepository implements ImportRepositoryInterface
{
    public function createImport(array $importData, array $logs = []): Import
    {
        return DB::transaction(function () use ($importData, $logs) {
            $import = Import::create($importData);

            if (!empty($logs)) {
                $import->logs()->createMany($logs);
            }

            return $import->fresh('logs');
        });
    }
}
