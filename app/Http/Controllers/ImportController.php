<?php

namespace App\Http\Controllers;

use App\Contacts\ImportContact;
use App\Http\Requests\ImportRequest;
use App\Http\Resources\ImportListResource;
use App\Http\Resources\ImportResource;
use App\Http\Resources\PaginationResourceCollection;
use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(public ImportContact $importContact) {}

    public function index(Request $request)
    {
        $imports = Import::query()
            ->orderByDesc('created_at')
            ->paginate(10);

        return new PaginationResourceCollection($imports, ImportListResource::class);
    }

    public function store(ImportRequest $request)
    {
        $file = $request->file('file');

        $import = $this->importContact->processFile($file);

        return response()->json(ImportListResource::make($import), 201);
    }

    public function show(Import $import)
    {
        $import->load('logs'); // eager load logów

        return new ImportResource($import);
    }
}
