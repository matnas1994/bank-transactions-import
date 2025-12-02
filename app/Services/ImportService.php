<?php

namespace App\Services;

use App\Contacts\ImportContact;
use App\Enums\ImportStatus;
use App\Models\Import;
use App\Repositories\ImportRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportService implements ImportContact
{
    public function __construct(protected TransactionRepositoryInterface $transactionRepository, protected ImportRepositoryInterface $importRepository) {}

    public function processFile(UploadedFile $file): Import
    {
        $fileName = $file->getClientOriginalName();
        $parser = ParserFactory::make($file);
        $rows = $parser->parse();
        $totalRecords = count($rows);

        [$transactions, $successfulRecords, $failedRecords, $errors] = $this->processRows($rows);

        $status = $this->determineStatus($totalRecords, $successfulRecords);

        try {
            DB::beginTransaction();

            $import = $this->importRepository->createImport([
                'file_name' => $fileName,
                'total_records' => $totalRecords,
                'successful_records' => $successfulRecords,
                'failed_records' => $failedRecords,
                'status' => $status,
                'created_at' => now(),
            ], $errors);

            $this->transactionRepository->insert($transactions);

            DB::commit();
        }catch (\Throwable $ex) {
            DB::rollBack();
            throw $ex;
        }

        return $import;
    }

    protected function determineStatus(int $totalRecords, int $successfulRecords): ImportStatus
    {
        return match (true) {
            $successfulRecords === $totalRecords => ImportStatus::SUCCESS,
            $successfulRecords > 0 => ImportStatus::PARTIAL,
            default => ImportStatus::FAILED,
        };
    }

    protected function processRows(array $rows): array
    {
        $successfulRecords = 0;
        $failedRecords = 0;
        $errors = [];
        $transactions = [];

        foreach ($rows as $data) {
            $log = $this->validateRow($data);

            if ($log) {
                $failedRecords++;
                $errors[] = $log;
            } else {
                $successfulRecords++;
                $transactions[] = [
                    'transaction_id' => $data['transaction_id'],
                    'account_number' => $data['account_number'],
                    'transaction_date' => $data['transaction_date'],
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                ];
            }
        }

        return [$transactions, $successfulRecords, $failedRecords, $errors];
    }

    protected function validateRow(array $data): ?array
    {
        $validator = Validator::make($data,
            [
                'account_number' => ['required', 'regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{16,30}$/'],
                'amount' => 'required|numeric|gt:0',
                'currency' => 'required|string|size:3',
                'transaction_id' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return [
                'transaction_id' => $data['transaction_id'] ?? null,
                'error_message' => implode('; ', $validator->errors()->all()),
            ];
        }

        return null;
    }
}
