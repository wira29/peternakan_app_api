<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Goat\StoreGoatRequest;
use App\Http\Requests\Goat\UpdateGoatRequest;
use App\Models\Goat;
use Symfony\Component\HttpFoundation\Response;

class GoatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-goats');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all goats");
        return Goat::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoatRequest $request)
    {
        $validated = $request->validated();

        $goat = Goat::create($validated);
        \Log::info("Created new goat with ID: " . $goat->id);
        return response()->json($goat, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching goat with ID: " . $id);
        $goat = Goat::findOrFail($id);
        return response()->json($goat, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoatRequest $request, string $id)
    {
        $validated = $request->validated();
        $goat = Goat::findOrFail($id);
        $goat->update($validated);
        \Log::info("Updated goat with ID: " . $goat->id);
        return response()->json($goat, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $goat = Goat::findOrFail($id);
        $goat->delete();
        \Log::info("Deleted goat with ID: " . $goat->id);
        return response()->json('Goat deleted successfully', Response::HTTP_OK);
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $goat = Goat::withTrashed()->findOrFail($id);
        $goat->restore();
        \Log::info("Restored goat with ID: " . $goat->id);
        return response()->json('Goat restored successfully.', Response::HTTP_OK);
    }
}
