<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class PaginationResourceCollection extends ResourceCollection
{
    private array $pagination;

    public function __construct($resource, $class, private array $data = [], private array $headers = [], $callback = null)
    {
        if ($resource instanceof \Illuminate\Database\Eloquent\Collection || $resource instanceof \Illuminate\Support\Collection) {
            $this->pagination = Arr::only($data, ['current_page', 'total_pages', 'total', 'page_count']);

            if ($callback) {
                $callback($resource);
            }

        } else {
            $this->pagination = [
                'current_page' => $data['current_page'] ?? $resource->currentPage(),
                'total_pages' => (int)($data['total_pages'] ?? $resource->lastPage()),
                'total' => $data['total'] ?? $resource->total(),
                'per_page' => $data['page_count'] ?? $resource->perPage()
            ];

            if ($callback) {
                $callback($resource);
            }

            $resource = $resource->items();
        }

        $this->data = Arr::except($data, ['current_page', 'total_pages', 'total', 'page_count']);


        parent::__construct($class::collection($resource));
    }

    public function toArray($request)
    {
        return [
                'items' => $this->collection,
                'pagination' => $this->pagination
            ] + $this->data;
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders($this->headers);
    }
}
