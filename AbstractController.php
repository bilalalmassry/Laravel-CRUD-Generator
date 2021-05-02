<?php


namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class AbstractController extends Controller
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var FormRequest
     */
    protected $storeRequest;

    /**
     * @var FormRequest
     */
    protected $updateRequest;

    /**
     * AbstractController constructor.
     * @param  $model
     * @param  $storeRequest
     * @param  $updateRequest
     */
    public function __construct($model, $storeRequest, $updateRequest)
    {
        $this->model = $model;
        $this->storeRequest = $storeRequest;
        $this->updateRequest = $updateRequest;
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->model::all(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return response()->json([
            'data' => $this->model::create(resolve($this->storeRequest)->all())
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function show($slug): JsonResponse
    {
        return response()->json([
            'data' => resolve($this->model)->findOrFail($slug)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function update($slug): JsonResponse
    {
        $modelObject = $this->model::findOrFail($slug);
        $modelObject->update(resolve($this->updateRequest)->all());
        return response()->json([
            'data' => $modelObject
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function destroy($slug): JsonResponse
    {
        $this->model::findOrFail($slug)->delete();
        return response()->json([
            'data' => "record deleted successfully"
        ]);
    }

}
