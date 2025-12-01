<?php

namespace App\Models;

use App\Enums\ImportStatus;
use App\Models\Import\Log;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'file_name',
        'total_records',
        'successful_records',
        'failed_records',
        'status',
        'created_at',
    ];

    protected $casts = [
        'status' => ImportStatus::class,
    ];

    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(Log::class, 'import_id');
    }
}
