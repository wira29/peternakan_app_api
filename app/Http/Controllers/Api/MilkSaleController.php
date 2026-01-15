<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MilkSale\StoreMilkSaleRequest;
use App\Http\Requests\MilkSale\UpdateMilkSaleRequest;
use App\Http\Resources\MilkSaleResource;
use App\Models\MilkSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\HttpCache\Store;

class MilkSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $milkSales = MilkSale::all();
            \Log::info("Fetched " . $milkSales->count() . " milk sale records.");
            if ($milkSales->isEmpty()) {
                return $this->sendResponse([], 'No milk sale records found');
            }
            return $this->sendResponse(
                MilkSale::collection($milkSales),
                'Successfully retrieved milk sale records.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching milk sales: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMilkSaleRequest $request)
    {
        try {
            $validated = $request->getData();
            DB::beginTransaction();
            $milkSale = MilkSale::create($validated);
            $milkSale->reduceMilkStock();
            DB::commit();
            \Log::info("Created milk sale record with ID: " . $milkSale->id);
            return $this->sendResponse(
                new MilkSaleResource($milkSale),
                'Milk sale record created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error creating milk sale record: " . $e->getMessage());
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
            \Log::info("Fetched milk sale record with ID: " . $id);
            $milkSale = MilkSale::findOrFail($id);
            return $this->sendResponse(
                new MilkSaleResource($milkSale),
                'Successfully retrieved milk sale record.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching milk sale record: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMilkSaleRequest $request, MilkSale $milkSale)
    {
        try{
            $validated = $request->getData();
            $milkSale = MilkSale::findOrFail($milkSale->id);
            $oldQty = $milkSale->qty;
            $newQty = $validated['qty'] ?? $oldQty;

            DB::beginTransaction();
            $milkSale->update($validated);
            if($newQty != $oldQty){
                $milkSale->adjustMilkStock($oldQty, $newQty);
            }
            DB::commit();
            
            \Log::info("Updated milk sale record with ID: " . $milkSale->id);
            return $this->sendResponse(
                MilkSale::collection($milkSale),
                'Milk sale record updated successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error updating milk sale record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the milk sale record.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MilkSale $milkSale)
    {
        try{
            $milkSale = MilkSale::findOrFail($milkSale->id);
            DB::beginTransaction();
            $milkSale->restoreMilkStock();
            $milkSale->delete();
            $milkSale->deletedBy()->associate(auth()->user());
            DB::commit();
            \Log::info("Deleted milk sale record with ID: " . $milkSale->id);
            return $this->sendResponse(
                new MilkSaleResource($milkSale),
                'Milk sale record deleted successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error deleting milk sale record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the milk sale record.', 500);
        }
        
    }

    public function restore(string $id)
    {
        try{
            $milkSale = MilkSale::withTrashed()->findOrFail($id);
            DB::beginTransaction();
            $milkSale->restore();
            $milkSale->deletedBy()->dissociate();
            DB::commit();
            \Log::info("Restored milk sale record with ID: " . $milkSale->id);
            return $this->sendResponse(
                new MilkSaleResource($milkSale),
                'Milk sale record restored successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error restoring milk sale record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the milk sale record.', 500);
        }
    }
}
