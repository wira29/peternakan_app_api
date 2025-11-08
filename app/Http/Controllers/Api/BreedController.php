<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Breed;
use App\Http\Requests\Breed\StoreBreedRequest;
use App\Http\Requests\Breed\UpdateBreedRequest;
use Symfony\Component\HttpFoundation\Response;

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
        $breed = Breed::with('createdby', 'updatedby','deletedby')->findOrFail($id);
        return response()->json($breed, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBreedRequest $request, string $id)
    {
        try {
            $validatedData = $request->getData();
            \Log::info("Data to update for breed ID $id: " . json_encode($validatedData));
            $breed = Breed::findOrFail($id);
            $breed->fill($validatedData);

            if (!$breed->isDirty()) {
                \Log::info("No changes detected for breed with ID: " . $id);

                return response()->json([
                    'message' => 'No changes detected. The original data is returned.',
                    'data'    => $breed
                ], Response::HTTP_OK);
            }

            $breed->save();
            \Log::info("Updated breed with ID: " . $breed->id);

            return response()->json([
                'message' => 'Breed updated successfully.',
                'data'    => $breed
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            $breed->save();
            \Log::error("Error updating breed with ID $id: " . $e->getMessage());

            return response()->json([
                'message' => 'Breed updated successfully.',
                'data'    => $breed
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Error updating breed with ID $id: " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the breed.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $breed = Breed::findOrFail($id);
        $breed->delete();
        \Log::info("Deleted breed with ID: " . $id);
        return response()->json('Breed deleted successfully.', Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $breed = Breed::withTrashed()->findOrFail($id);
        $breed->restore();
        \Log::info("Restored breed with ID: " . $breed->id);
        return response()->json('Breed restored successfully.', Response::HTTP_OK);
    }
}
