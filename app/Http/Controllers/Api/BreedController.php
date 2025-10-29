<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-breeds');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all breeds");
        return Breed::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBreedRequest $request)
    {
        $validated = $request->validated();

        $breed = Breed::create($validated);
        \Log::info("Created new breed with ID: " . $breed->id);
        return response()->json($breed, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching breed with ID: " . $id);
        $breed = Breed::findOrFail($id);
        return response()->json($breed, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $breed = Breed::findOrFail($id);
        $breed->update($request->getData());
        \Log::info("Updated breed with ID: " . $breed->id);
        return response()->json($breed, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $breed = Breed::findOrFail($id);
        $breed->delete();
        \Log::info("Deleted breed with ID: " . $id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function restore(string $id)
    {
        $breed = Breed::withTrashed()->findOrFail($id);
        $breed->restore();
        \Log::info("Restored breed with ID: " . $breed->id);
        return response()->json('Breed restored successfully.', Response::HTTP_OK);
    }
}
