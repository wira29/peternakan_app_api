<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cage;
use App\Http\Requests\Cage\StoreCageRequest;
use App\Http\Requests\Cage\UpdateCageRequest;
use App\Http\Resources\CageResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\Translation\t;

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
        $cages = Cage::with('createdby', 'updatedby','deletedby')->get();
        if ($cages->isEmpty()) {
            return $this->sendResponse(
                [], 
                'No cage transactions data found'
            );
        }
        return $this->sendResponse(
            CageResource::collection($cages),
            'Successfully get cage transactions data'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCageRequest $request)
    {
        $validated = $request->getData();
        \Log::info('Data Request Create Feed Sale: ' .json_encode($validated));
        
        try {
            $cage = Cage::create($validated);
        } catch (\Throwable $th) {
            \Log::error("Failed to create feed sale: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                 $th->getCode()
            );
        }

        \Log::info("Created new cage with ID: " . $cage->id);
        
        return $this->sendResponse(
            new CageResource($cage),
            'Cage created successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try
        {
            \Log::info('Retrieving cage with ID: ' . $id);
            $cage = Cage::findOrFail($id);
            return $this->sendResponse(new CageResource($cage), 'Cage retrieved successfully');
        }
        catch (\Exception $e)
        {
            \Log::error('Error retrieving cage: ' . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
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
                
                return $this->sendResponse(
                    new CageResource($cage),
                    'No changes detected. The original data is returned.'
                );
            }

            $cage->save();
            Log::info("Updated cage with ID: " . $cage->id);

            return $this->sendResponse(
                new CageResource($cage),
                'Cage updated successfully.'
            );

        } catch (\Exception $e) {
            Log::error("Error updating cage with ID $id: " . $e->getMessage());
            
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $cage = Cage::findOrFail($id);
            $cage->deleted_by = auth()->user()->id;
            $cage->save();
            $cage->delete();
            \Log::info("Deleted cage with ID: " . $cage->id);
            return $this->sendResponse(new CageResource($cage), 'Cage deleted successfully');
        } catch (\Exception $e) {
            \Log::error("Error deleting cage with ID $id: " . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function restore(string $id)
    {
        try {
            $cage = Cage::withTrashed()->findOrFail($id);
            $cage->restore();
            \Log::info("Restored cage with ID: " . $cage->id);
            return $this->sendResponse(new CageResource($cage), 'Cage restored successfully.');
        } catch (\Exception $e) {
            \Log::error("Error restoring cage with ID $id: " . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
