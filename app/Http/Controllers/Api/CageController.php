<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cage;
use App\Http\Requests\Cage\StoreCageRequest;
use App\Http\Requests\Cage\UpdateCageRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-cages');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all cages");
        return Cage::with('createdby', 'updatedby','deletedby')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCageRequest $request)
    {
        $validated = $request->validated();

        $cage = Cage::create($validated);
        \Log::info("Created new cage with ID: " . $cage->id);
        return response()->json($cage, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching cage with ID: " . $id);
        $cage = Cage::with('createdby', 'updatedby','deletedby')->findOrFail($id);
        return response()->json($cage, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCageRequest $request, string $id)
    {
        try {
            $validatedData = $request->getData();
            $cage = Cage::findOrFail($id);
            $cage->fill($validatedData);

            if (!$cage->isDirty()) {
                Log::info("No changes detected for cage with ID: " . $id);
                
                return response()->json([
                    'message' => 'No changes detected. The original data is returned.',
                    'data'    => $cage 
                ], Response::HTTP_OK);
            }

            $cage->save();
            Log::info("Updated cage with ID: " . $cage->id);

            return response()->json([
                'message' => 'Cage updated successfully.',
                'data'    => $cage 
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error("Error updating cage with ID $id: " . $e->getMessage());
            
            return response()->json([
                'message' => 'An error occurred while updating the cage.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cage = Cage::withTrashed()->findOrFail($id);
        $cage->restore();
        \Log::info("Restored cage with ID: " . $cage->id);
        return response()->json('Cage restored successfully.', Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $cage = Cage::withTrashed()->findOrFail($id);
        $cage->restore();
        \Log::info("Restored cage with ID: " . $cage->id);
        return response()->json('Cage restored successfully.', Response::HTTP_OK);
    }
}
