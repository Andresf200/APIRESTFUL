<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponse
{
    private function successResponse($data, $code): JsonResponse
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200): JsonResponse
    {
        if ($collection->isEmpty()){
            return $this->successResponse(['data' => $collection], 200);
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200): JsonResponse
    {
        $transformer = $instance->transformer;
        $instance = $this->transformData($instance, $transformer);
        return $this->successResponse($instance, $code);
    }

    public function showMessage($message, $code = 200): JsonResponse
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function filterData(Collection $collection, $transformer): Collection
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);
            if (isset($attribute, $value)){
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    public function sortData(Collection $collection, $transformer): Collection
    {
        if (request()->has('sort_by')){
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage(); // Primero resolvemos la pagina actual

        $perPage = 15;

        if (request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $page, $perPage)->values();
        // Slice fragmenta la colección en un inicio y en fin inciando desde cero y values para ordenar y evitar discontinuidad en los indíces.

        $paginate = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPage()
        ]);

        $paginate->appends(request()->all());

        return $paginate;
    }

    protected function transformData($data, $transformer): array
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();//recibimos los parametros enviados por url


            ksort($queryParams); // ordena los parametros
        $queryString = http_build_query($queryParams); //construimos el query string a partir del array ordenado

        $fullUrl = "{$url}?{$queryString}"; // construimos nuestra url completa

        return Cache::remember($fullUrl, 100, function () use ($data){
            return $data;
        });
    }
}
