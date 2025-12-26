<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vaccine\StoreVaccineRequest;
use App\Http\Requests\Vaccine\UpdateVaccineRequest;
use App\Http\Resources\VaccineResource;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-vaccine-records');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vaccines = Vaccine::all();
            \Log::info("Fetched " . $vaccines->count() . " vaccine records.");
            if ($vaccines->isEmpty()) {
                return $this->sendResponse([], 'No vaccine records found');
            }
            return $this->sendResponse(
                VaccineResource::collection($vaccines),
                'Successfully retrieved vaccine records.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching vaccines: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccineRequest $request)
    {
        $validated = $request->getData();
        try {
            $vaccine = Vaccine::create($validated);
            \Log::info("Created vaccine record with ID: " . $vaccine->id);
            return $this->sendResponse(
                new VaccineResource($vaccine),
                'Vaccine record created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error creating vaccine record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $vaccine = Vaccine::findOrFail($id);
            \Log::info("Fetched vaccine record with ID: " . $id);
            return $this->sendResponse(
                new VaccineResource($vaccine),
                'Successfully retrieved vaccine record.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching vaccine with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVaccineRequest $request, string $id)
    {
        try {
            $validated = $request->getData();
            $vaccine = Vaccine::findOrFail($id);
            $vaccine->update($validated);
            \Log::info("Updated vaccine record with ID: " . $vaccine->id);
            return $this->sendResponse(
                new VaccineResource($vaccine),
                'Vaccine record updated successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error updating vaccine with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       try {
            $vaccine = Vaccine::findOrFail($id);
            $vaccine->deleted_by = auth()->user()->id;
            $vaccine->save();
            $vaccine->delete();
            \Log::info("Deleted vaccine record with ID: " . $vaccine->id);
            return $this->sendResponse(
                new VaccineResource($vaccine),
                'Vaccine record deleted successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error deleting vaccine with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function restore(string $id)
    {
        try {
            $vaccine = Vaccine::withTrashed()->findOrFail($id);
            $vaccine->restore();
            \Log::info("Restored vaccine record with ID: " . $vaccine->id);
            return $this->sendResponse(
                new VaccineResource($vaccine),
                'Vaccine record restored successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error restoring vaccine with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }
}
