<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatingHistory\StoreMatingHistoryRequest;
use App\Http\Requests\MatingHistory\UpdateMatingHistoryRequest;
use App\Http\Resources\MatingHistoryResource;
use App\Models\MatingHistory;
use Illuminate\Http\Request;

class MatingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $histories = MatingHistory::all();
            if($histories->isEmpty()){
                return $this->sendResponse([], 'No mating history records found');
            }
            \Log::info("Fetched " . $histories->count() . " mating history records.");
            return $this->sendResponse(
                MatingHistoryResource::collection($histories),
                'Successfully retrieved mating history records.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching mating history records: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving mating history records.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatingHistoryRequest $request)
    {
       try{
            $validated = $request->getData();
            $matingHistory = MatingHistory::create($validated);
            \Log::info("Created mating history record with ID: " . $matingHistory->id);
            return $this->sendResponse(
                new MatingHistoryResource($matingHistory),
                'Mating history record created successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error creating mating history record: " . $e->getMessage());
            return $this->sendError('An error occurred while creating the mating history record.', 500);
       } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $matingHistory = MatingHistory::findOrFail($id);
            \Log::info("Fetched mating history record with ID: " . $matingHistory->id);
            return $this->sendResponse(
                new MatingHistoryResource($matingHistory),
                'Successfully retrieved mating history record.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching mating history record: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving the mating history record.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatingHistoryRequest $request, string $id)
    {
        try{
            $matingHistory = MatingHistory::findOrFail($id);
            $validated = $request->getData();
            $matingHistory->update($validated);
            \Log::info("Updated mating history record with ID: " . $matingHistory->id);
            return $this->sendResponse(
                new MatingHistoryResource($matingHistory),
                'Mating history record updated successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error updating mating history record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the mating history record.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $matingHistory = MatingHistory::findOrFail($id);
            $matingHistory->delete();
            $matingHistory->deleted_by = auth()->user()->id;
            $matingHistory->save();
            \Log::info("Deleted mating history record with ID: " . $matingHistory->id);
            return $this->sendResponse(
                null,
                'Mating history record deleted successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error deleting mating history record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the mating history record.', 500);
        }
    }

    public function restore(string $id)
    {
        try{
            $matingHistory = MatingHistory::withTrashed()->findOrFail($id);
            $matingHistory->restore();
            $matingHistory->deleted_by = null;
            $matingHistory->save();
            \Log::info("Restored mating history record with ID: " . $matingHistory->id);
            return $this->sendResponse(
                new MatingHistoryResource($matingHistory),
                'Mating history record restored successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error restoring mating history record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the mating history record.', 500);
        }
    }
}
