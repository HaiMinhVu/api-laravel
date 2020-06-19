<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use Illuminate\Http\Request;
use App\Http\Resources\Crud\FileListItem;
use App\Http\Requests\FileStoreRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('ids')) {
            return $this->showByIds($request->ids);
        } else {
            return $this->showPaginatedData($request);
        }
    }

    private function showPaginatedData(Request $request)
    {
        $limit = $request->has('limit') ? min($request->limit, 100) : 10;
        $query = FileManager::existsOnS3()->orderBy('ID', 'desc');

        if($request->has('type')) {
            $query->byType($request->type);
        }

        if($request->has('search')) {
            $query->fuzzyMatch($request->search);
        }

        if($request->has('brand')) {
            $query->byBrand($request->brand);
        }

        $results = $query->paginate($limit);

        $items = $results->map(function($item){
            return new FileListItem($item, 200);
        });

        return response()->json([
            'pages' => $results->lastPage(),
            'current_page' => $results->currentPage(),
            'total' => $results->total(),
            'data' => $items
        ]);
    }

    private function showByIds(array $ids)
    {
        $results = FileManager::whereIn('ID', $ids)->get();
        $items = $results->map(function($item){
            return new FileListItem($item, 200);
        });
        return response()->json(['data' => $items]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return FileManager::handleNewUpload($request->file, $request->type, $request->brand);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FileManager  $fileManager
     * @return \Illuminate\Http\Response
     */
    public function show($fileId)
    {
        $file = FileManager::existsOnS3()->find($fileId);
        $item = ($file) ? new FileListItem($file, 200) : null;
        return response()->json(['data' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FileManager  $fileManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FileManager $file)
    {
        array_map(function($key) use ($request, $file) {
            if($request->has($key)) {
                $file->$key = $request->$key;
            }
        }, ['description', 'display_name']);

        if($file->isDirty()) {
            $file->save();
        }
        return $file;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FileManager  $fileManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileManager $fileManager)
    {
        //
    }
}
