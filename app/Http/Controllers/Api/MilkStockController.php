<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MilkStock\StoreMilkStockRequest;
use App\Http\Requests\MilkStock\UpdateMilkStockRequest;
use App\Http\Resources\MilkStockResource;
use App\Models\MilkStock;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

class MilkStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $stocks = MilkStock::all();
            if($stocks->isEmpty()){
                return $this->sendResponse([], 'No milk stock records found');
            }
            \Log::info("Fetched " . $stocks->count() . " milk stock records.");
            return $this->sendResponse(
                MilkStockResource::collection($stocks),
                'Successfully retrieved milk stock records.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching milk stock records: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving milk stock records.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMilkStockRequest $request)
    {
         try{
                $validated = $request->getData();
                $milkStock = MilkStock::create($validated);
                \Log::info("Created milk stock record with ID: " . $milkStock->id);
                return $this->sendResponse(
                 new MilkStockResource($milkStock),
                 'Milk stock record created successfully.'
                );
          }catch(\Exception $e){
                \Log::error("Error creating milk stock record: " . $e->getMessage());
                return $this->sendError('An error occurred while creating the milk stock record.', 500);
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            \Log::info("Fetched milk stock record with ID: " . $id);
            $milkStock = MilkStock::findOrFail($id);
            return $this->sendResponse(
                new MilkStockResource($milkStock),
                'Successfully retrieved milk stock record.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching milk stock record: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving the milk stock record.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMilkStockRequest $request, string $id)
    {
        try{
            $validated = $request->getData();
            $milkStock = MilkStock::findOrFail($id);
            $milkStock->update($validated);
            \Log::info("Validated data: " . json_encode($validated));
            \Log::info("Updated milk stock record with ID: " . $milkStock->id);
            return $this->sendResponse(
                new MilkStockResource($milkStock),
                'Milk stock record updated successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error updating milk stock record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the milk stock record.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $milkStock = MilkStock::findOrFail($id);
            $milkStock->delete();
            $milkStock->deleted_by = auth()->user()->id;
            $milkStock->save();
            \Log::info("Deleted milk stock record with ID: " . $milkStock->id);
            return $this->sendResponse(
                null,
                'Milk stock record deleted successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error deleting milk stock record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the milk stock record.', 500);
        }
    }

    public function restore(string $id)
    {
        try{
            $milkStock = MilkStock::withTrashed()->findOrFail($id);
            $milkStock->restore();
            $milkStock->deleted_by = null;
            $milkStock->save();
            \Log::info("Restored milk stock record with ID: " . $milkStock->id);
            return $this->sendResponse(
                new MilkStockResource($milkStock),
                'Milk stock record restored successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error restoring milk stock record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the milk stock record.', 500);
        }
    }

    public function getByLocation(string $location)
    {
        \Log::info("Fetching milk stock records by location: " . $location);
        try{
            $stocks = MilkStock::where('location', $location)->get();
            if($stocks->isEmpty()){
                return $this->sendResponse([], 'No milk stock records found');
            }
            \Log::info("Fetched " . $stocks->count() . " milk stock records.");
            return $this->sendResponse(
                MilkStockResource::collection($stocks),
                'Successfully retrieved milk stock records.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching milk stock records by location: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving milk stock records.', 500);
        }
    }
}
