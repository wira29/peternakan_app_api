<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Breed;
use App\Http\Requests\Breed\StoreBreedRequest;
use App\Http\Requests\Breed\UpdateBreedRequest;
use App\Http\Resources\BreedResource;
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
        try {
            $breeds = Breed::with('createdby', 'updatedby', 'deletedby')->get();
        } catch (\Exception $e) {
            \Log::error("Error fetching breeds: " . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
        \Log::info("Fetching all breeds");
        return $this->sendResponse(BreedResource::collection($breeds), 'Breeds retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBreedRequest $request)
    {
        $validated = $request->getData();
        try {
            \Log::info("Data to create breed: " . json_encode($validated));
            $breed = Breed::create($validated);
        } catch (\Exception $e) {
            \Log::error("Error creating breed: " . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
        \Log::info("Created new breed with ID: " . $breed->id);
        return $this->sendResponse(new BreedResource($breed), 'Breed created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching breed with ID: " . $id);
        try {
            $breed = Breed::with('createdby', 'updatedby', 'deletedby')->findOrFail($id);
        } catch (\Exception $e) {
            \Log::error("Error fetching breed: " . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }

        return $this->sendResponse(new BreedResource($breed), 'Breed retrieved successfully');
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

                return $this->sendResponse(
                    new BreedResource($breed),
                    'No changes detected. The original data is returned.'
                );
            }

            $breed->save();
            \Log::info("Updated breed with ID: " . $breed->id);

            return $this->sendResponse(
                new BreedResource($breed),
                'Breed updated successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error updating breed with ID $id: " . $e->getMessage());

           return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $breed = Breed::findOrFail($id);
            $breed->delete();
            \Log::info("Data to delete for breed ID $id: " . json_encode($breed));
        } catch (\Throwable $th) {
            \Log::error("Error deleting breed with ID $id: " . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
        return $this->sendResponse(new BreedResource($breed), 'Breed deleted successfully');
    }

    public function restore(string $id)
    {
        try {
            $breed = Breed::onlyTrashed()->findOrFail($id);
            $breed->restore();
            \Log::info("Restored breed with ID: " . $breed->id);
        } catch (\Throwable $th) {
            \Log::error("Error restoring breed with ID $id: " . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
        return $this->sendResponse(new BreedResource($breed), 'Breed restored successfully');
    }
}
