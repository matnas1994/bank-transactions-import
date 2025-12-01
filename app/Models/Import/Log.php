<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'import_logs';
    protected $guarded = [];

    public $timestamps = false;
}
