<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vaccine\StoreVaccineHistoryRequest;
use App\Http\Requests\Vaccine\UpdateVaccineHistoryRequest;
use App\Http\Resources\VaccineHistoryResource;
use App\Models\Goat;
use App\Models\VaccineHistory;
use Illuminate\Http\Request;

class VaccineHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-vaccine-records');
    }

    public function index()
    {
        $history = VaccineHistory::all();
        if ($history->isEmpty()) {
            return $this->sendResponse([], 'No vaccine history records found');
        }
        \Log::info("Fetched " . $history->count() . " vaccine history records.");
        return $this->sendResponse(
            VaccineHistoryResource::collection($history),
            'Successfully retrieved vaccine history records.'
        );
    }

    public function store(StoreVaccineHistoryRequest $request)
    {
        $validated = $request->getData();
        try {
            if(!empty($validated['cage_id'])) {
                foreach ($validated['cage_id'] as $cageId) {
                    $goats = Goat::where('cage_id', $cageId)->get();
                    foreach ($goats as $goat) {
                        $historyData = $validated;
                        $historyData['goat_code'] = $goat->code;
                        unset($historyData['cage_id']);
                        $vaccineHistory = VaccineHistory::create($historyData);
                        \Log::info("Created vaccine history record with ID: " . $vaccineHistory->id . " for Goat ID: " . $goat->id);
                    }
                }
                return $this->sendResponse(
                    VaccineHistoryResource::collection(VaccineHistory::whereIn('goat_code', Goat::whereIn('cage_id', $validated['cage_id'])->pluck('code'))->get()),
                    'Vaccine history records created successfully for all goats in the specified cages.'
                );
            }
            $vaccineHistory = VaccineHistory::create($validated);
            \Log::info("Created vaccine history record with ID: " . $vaccineHistory->id);
            return $this->sendResponse(
                new VaccineHistoryResource($vaccineHistory),
                'Vaccine history record created successfully.'
            );    
        } catch (\Exception $e) {
            \Log::error("Error creating vaccine history record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function show(string $id)
    {
        try {
            $vaccineHistory = VaccineHistory::findOrFail($id);
            if (! $vaccineHistory) {
                return $this->sendError('Vaccine history record not found', 404);
            }
            \Log::info("Fetched vaccine history record with ID: " . $id);
            return $this->sendResponse(
                new VaccineHistoryResource($vaccineHistory),
                'Successfully retrieved vaccine history record.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching vaccine history record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function update(UpdateVaccineHistoryRequest $request, string $id)
    {
        $validated = $request->getData();
        \Log::info("Updating vaccine history record with ID: " . $id . " with data: " . json_encode($validated));
        try {
            $vaccineHistory = VaccineHistory::findOrFail($id);
            $vaccineHistory->update($validated);
            \Log::info("Updated vaccine history record with ID: " . $vaccineHistory->id);
            return $this->sendResponse(
                new VaccineHistoryResource($vaccineHistory),
                'Vaccine history record updated successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error updating vaccine history record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $vaccineHistory = VaccineHistory::findOrFail($id);
            $vaccineHistory->deleted_by = auth()->user()->id;
            $vaccineHistory->delete();
            $vaccineHistory->save();
            \Log::info("Deleted vaccine history record with ID: " . $vaccineHistory->id);
            return $this->sendResponse(
                new VaccineHistoryResource($vaccineHistory),
                'Vaccine history record deleted successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error deleting vaccine history record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $vaccineHistory = VaccineHistory::withTrashed()->findOrFail($id);
            if ($vaccineHistory->trashed()) {
                $vaccineHistory->deleted_by = null;
                $vaccineHistory->restore();
                $vaccineHistory->save();
                \Log::info("Restored vaccine history record with ID: " . $vaccineHistory->id);
                return $this->sendResponse(
                    new VaccineHistoryResource($vaccineHistory),
                    'Vaccine history record restored successfully.'
                );
            } else {
                \Log::info("Vaccine history record with ID: " . $vaccineHistory->id . " is not deleted.");
                return $this->sendError(
                    'Vaccine history record is not deleted.', 400
                );
            }
        } catch (\Exception $e) {
            \Log::error("Error restoring vaccine history record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }
}
