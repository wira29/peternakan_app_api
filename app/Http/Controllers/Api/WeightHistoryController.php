<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeightHistory\StoreWeightHistoryRequest;
use App\Http\Requests\WeightHistory\UpdateWeightHistoryRequest;
use App\Http\Resources\WeightHistoryResource;
use App\Models\WeightHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

class WeightHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $history = WeightHistory::all();
            if ($history->isEmpty()) {
                return $this->sendResponse([], 'No weight history records found');
            }
            \Log::info("Fetched " . $history->count() . " weight history records.");
            return $this->sendResponse(
                WeightHistoryResource::collection($history),
                'Successfully retrieved weight history records.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching weight history records: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving weight history records.', [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWeightHistoryRequest $request)
    {
        try {
            $validated = $request->getData();
            $weightHistory = WeightHistory::create($validated);
            \Log::info("Created weight history record with ID: " . $weightHistory->id);
            return $this->sendResponse(
                new WeightHistoryResource($weightHistory),
                'Weight history record created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error creating weight history record: " . $e->getMessage());
            return $this->sendError('An error occurred while creating the weight history record.', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $weightHistory = WeightHistory::findOrFail($id);
            \Log::info("Fetched weight history record with ID: " . $weightHistory->id);
            return $this->sendResponse(
                new WeightHistoryResource($weightHistory),
                'Successfully retrieved weight history record.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning("Weight history record not found with ID: " . $id);
            return $this->sendError('Weight history record not found.', [], 404);
        } catch (\Exception $e) {
            \Log::error("Error fetching weight history record: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving the weight history record.', [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWeightHistoryRequest $request, string $id)
    {
        try {
            $weightHistory = WeightHistory::findOrFail($id);
            $weightHistory->update($request->all());
            \Log::info("Updated weight history record with ID: " . $weightHistory->id);
            return $this->sendResponse(
                new WeightHistoryResource($weightHistory),
                'Weight history record updated successfully.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning("Weight history record not found with ID: " . $id);
            return $this->sendError('Weight history record not found.', [], 404);
        } catch (\Exception $e) {
            \Log::error("Error updating weight history record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the weight history record.', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $weightHistory = WeightHistory::findOrFail($id);
            $weightHistory->delete();
            $weightHistory->deleted_by = auth()->user()->id;
            $weightHistory->save();
            \Log::info("Deleted weight history record with ID: " . $weightHistory->id);
            return $this->sendResponse(
                null,
                'Weight history record deleted successfully.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning("Weight history record not found with ID: " . $id);
            return $this->sendError('Weight history record not found.', [], 404);
        } catch (\Exception $e) {
            \Log::error("Error deleting weight history record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the weight history record.', [], 500);
        }
    }
    /**
     * Restore the specified resource from soft deletion.
     */    public function restore(string $id)
    {
        try {
            $weightHistory = WeightHistory::withTrashed()->findOrFail($id);
            $weightHistory->restore();
            $weightHistory->deleted_by = null;
            $weightHistory->save();
            \Log::info("Restored weight history record with ID: " . $weightHistory->id);
            return $this->sendResponse(
                new WeightHistoryResource($weightHistory),
                'Weight history record restored successfully.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning("Weight history record not found with ID: " . $id);
            return $this->sendError('Weight history record not found.', [], 404);
        } catch (\Exception $e) {
            \Log::error("Error restoring weight history record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the weight history record.', [], 500);
        }
    }
}
