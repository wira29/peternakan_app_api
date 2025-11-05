<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Http\Requests\Material\StoreMaterialRequest;
use App\Http\Requests\Material\UpdateMaterialRequest;
use Symfony\Component\HttpFoundation\Response;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-materials');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all materials");
        return Material::with('createdby', 'updatedby','deletedby')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        $validated = $request->validated();

        $material = Material::create($request->getData());
        \Log::info("Created new material with ID: " . $material->id);
        return response()->json($material, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching material with ID: " . $id);
        $material = Material::with('createdby', 'updatedby','deletedby')->findOrFail($id);
        return response()->json($material, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequest $request, string $id)
    {
        $material = Material::with('createdby', 'updatedby','deletedby')->findOrFail($id);
        $material->update($request->getData());
        \Log::info("Updated material with ID: " . $material->id);
        return response()->json($material, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        \Log::info("Deleted material with ID: " . $material->id);
        return response()->json('Material deleted successfully.', Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $material = Material::withTrashed()->findOrFail($id);
        $material->restore();
        \Log::info("Restored material with ID: " . $material->id);
        return response()->json('Material restored successfully.', Response::HTTP_OK);
    }
}
