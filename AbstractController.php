<?php


namespace App\Http\Controllers;

use Illuminate\Http\File;
use BadMethodCallException;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
     * @var string
     */
    protected $disk;

    /**
     * @var string
     */
    protected $folder;

    /**
     * AbstractController constructor.
     * @param  $model
     * @param  $storeRequest
     * @param  $updateRequest
     * @param  string|null  $disk
     * @param  string|null  $folder
     */
    public function __construct(
        $model,
        $storeRequest,
        $updateRequest,
        string $disk = 'public',
        string $folder = 'images'
    ) {
        $this->model = $model;
        $this->storeRequest = $storeRequest;
        $this->updateRequest = $updateRequest;
        $this->disk = $disk;
        $this->folder = $folder;
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
        //resolving store request
        $request = resolve($this->storeRequest);

        //extract request data
        $inputs = $request->all();

        //iterate over model files and save
        foreach ($this->getFileAttrs() as $column) {
            $this->saveFile($inputs, $request, $column);
        }

        //create model and response
        return response()->json([
            'data' => $this->model::create($inputs)
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
        //find the model
        $modelObject = $this->model::findOrFail($slug);

        //resolving update request
        $request = resolve($this->updateRequest);

        //extract request data
        $inputs = $request->all();

        //iterate over model files and save
        foreach ($this->getFileAttrs() as $column) {
            $this->saveFile($inputs, $request, $column);
        }

        //update model
        $modelObject->update($inputs);

        //return response
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
        //find the model then delete
        $this->model::findOrFail($slug)->delete();

        //return response
        return response()->json([
            'data' => "record deleted successfully"
        ]);
    }

    /*
     * get the files attributes from model
     * this consider you already define getFileAttrs() in your model
     */
    private function getFileAttrs()
    {
        try {
            return $this->model::getFileAttrs();
        } catch (BadMethodCallException $exception) {
            return [];
        }
    }

    /*
     * check the file in request then save it
     */
    private function saveFile(array &$inputs, $request, string $fileName)
    {
        if (!$request->file($fileName)) {
            return;
        }
        $path = $this->saveFileToStorage($this->disk, $this->folder, $request->file($fileName));
        $inputs[$fileName] = $path;
    }

    /*
    * save file in storage depending on disk and folder
    * return path of the file
    */
    private function saveFileToStorage($disk, $folder, $file): string
    {
        $path = Storage::disk($disk)->putFile($folder, new File($file));
        return '/storage/' . $path;
    }
}
