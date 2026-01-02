<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleGoat\StoreSaleGoatRequest;
use App\Http\Requests\SaleGoat\UpdateSaleGoatRequest;
use App\Http\Resources\SaleGoatResource;
use App\Models\SaleGoat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\HttpCache\Store;

class SaleGoatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $saleGoats = SaleGoat::all();
            if($saleGoats->isEmpty()){
                return $this->sendResponse([], 'No sale goat records found');
            }
            \Log::info("Fetched " . $saleGoats->count() . " sale goat records.");
            return $this->sendResponse(
                SaleGoatResource::collection($saleGoats),
                'Successfully retrieved sale goat records.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching sale goat records: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving sale goat records.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleGoatRequest $request)
    {
        try{
            $validated = $request->getData();
            DB::beginTransaction();
            $saleGoat = SaleGoat::create($validated);
            $saleGoat->updateStatusGoatToSold();
            DB::commit();
            \Log::info("Created sale goat record with ID: " . $saleGoat->id);
            return $this->sendResponse(
                new SaleGoatResource($saleGoat),
                'Sale goat record created successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error creating sale goat record: " . $e->getMessage());
            return $this->sendError('An error occurred while creating the sale goat record.', 500);
        }
    }
    /** 
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $saleGoat = SaleGoat::findOrFail($id);
            \Log::info("Fetched sale goat record with ID: " . $saleGoat->id);
            return $this->sendResponse(
                new SaleGoatResource($saleGoat),
                'Successfully retrieved sale goat record.'
            );
        }catch(\Exception $e){
            \Log::error("Error fetching sale goat record: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving the sale goat record.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleGoatRequest $request, string $id)
    {
        try{
            $validated = $request->getData();
            DB::beginTransaction();
            $saleGoat = SaleGoat::findOrFail($id);
            $saleGoat->update($validated);
            DB::commit();
            \Log::info("Updated sale goat record with ID: " . $saleGoat->id);
            return $this->sendResponse(
                new SaleGoatResource($saleGoat),
                'Sale goat record updated successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error updating sale goat record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the sale goat record.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $saleGoat = SaleGoat::findOrFail($id);
            DB::beginTransaction();
            $saleGoat->delete();
            $saleGoat->deleted_by = auth()->user()->id;
            $saleGoat->save();
            $saleGoat->revertStatusGoatToAvailable();
            DB::commit();
            \Log::info("Deleted sale goat record with ID: " . $saleGoat->id);
            return $this->sendResponse([], 'Sale goat record deleted successfully.');
        }catch(\Exception $e){
            \Log::error("Error deleting sale goat record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the sale goat record.', 500);
        }
    }

    public function restore(string $id)
    {
        try{
            $saleGoat = SaleGoat::withTrashed()->findOrFail($id);
            DB::beginTransaction();
            $saleGoat->restore();
            $saleGoat->deleted_by = null;
            $saleGoat->save();
            $saleGoat->updateStatusGoatToSold();
            DB::commit();
            \Log::info("Restored sale goat record with ID: " . $saleGoat->id);
            return $this->sendResponse(
                new SaleGoatResource($saleGoat),
                'Sale goat record restored successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error restoring sale goat record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the sale goat record.', 500);
        }
    }
}
