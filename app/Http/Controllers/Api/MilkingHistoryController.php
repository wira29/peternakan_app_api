<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MilkHistory\StoreMilkHistoryRequest;
use App\Http\Requests\MilkHistory\UpdateMilkHistoryRequest;
use App\Http\Resources\MilkHistoryResource;
use App\Http\Resources\MilkingHistoryResource;
use App\Models\MilkingHistory;
use App\Models\MilkStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\HttpCache\Store;

class MilkingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $histories = MilkingHistory::all();
            if ($histories->isEmpty()) {
                return $this->sendResponse([], 'No milking history records found');
            }
            
            \Log::info("Fetched " . $histories->count() . " milking history records.");
            return $this->sendResponse(
                MilkingHistoryResource::collection($histories),
                'Successfully retrieved milking history records.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching milking history records: " . $e->getMessage());
            return $this->sendError($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMilkHistoryRequest $request)
    {
        try {
            $validated = $request->getData();
            DB::beginTransaction();
            $milkingHistory = MilkingHistory::create($validated);
            $milkStock = $milkingHistory->milkStock();
            if(!$milkStock) {
                MilkStock::create([
                    'location_id' => $milkingHistory->goat->location_id,
                    'qty' => 0,
                    'created_by' => $milkingHistory->created_by,
                ]);
            }
            $milkingHistory->increaseMilkStock();
            DB::commit();
            \Log::info("Created milking history record with ID: " . $milkingHistory->id);
            return $this->sendResponse(
                new MilkingHistoryResource($milkingHistory),
                'Milking history record created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error creating milking history record: " . $e->getMessage());
            return $this->sendError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $milkingHistory = MilkingHistory::findOrFail($id);
            \Log::info("Fetched milking history record with ID: " . $milkingHistory->id);
            return $this->sendResponse(
                new MilkingHistoryResource($milkingHistory),
                'Successfully retrieved milking history record.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching milking history record: " . $e->getMessage());
            return $this->sendError('An error occurred while retrieving the milking history record.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMilkHistoryRequest $request, string $id)
    {
        try{
            $milkingHistory = MilkingHistory::findOrFail($id);
            $oldQty = $milkingHistory->qty;
            $newQty = $request->input('qty', $oldQty);

            DB::beginTransaction();

            $milkingHistory->update($request->getData());

            if ($newQty !== $oldQty) {
                $milkingHistory->adjustMilkStock($newQty, $oldQty);
            }

            DB::commit();

            \Log::info("Updated milking history record with ID: " . $milkingHistory->id);
            return $this->sendResponse(
                new MilkingHistoryResource($milkingHistory),
                'Milking history record updated successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error updating milking history record: " . $e->getMessage());
            return $this->sendError('An error occurred while updating the milking history record.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $milkingHistory = MilkingHistory::findOrFail($id);

            DB::beginTransaction();

            $milkingHistory->decreaseMilkStock();
            $milkingHistory->delete();
            $milkingHistory->deleted_by = auth()->user()->id;
            $milkingHistory->save();

            DB::commit();

            \Log::info("Deleted milking history record with ID: " . $milkingHistory->id);
            return $this->sendResponse(
                [],
                'Milking history record deleted successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error deleting milking history record: " . $e->getMessage());
            return $this->sendError('An error occurred while deleting the milking history record.', 500);
        }
    }

    public function restore(string $id)
    {
        try{
            $milkingHistory = MilkingHistory::withTrashed()->findOrFail($id);

            DB::beginTransaction();

            $milkingHistory->restore();
            $milkingHistory->deleted_by = null;
            $milkingHistory->save();
            $milkingHistory->increaseMilkStock();

            DB::commit();

            \Log::info("Restored milking history record with ID: " . $milkingHistory->id);
            return $this->sendResponse(
                MilkingHistoryResource::collection($milkingHistory),
                'Milking history record restored successfully.'
            );
        }catch(\Exception $e){
            \Log::error("Error restoring milking history record: " . $e->getMessage());
            return $this->sendError('An error occurred while restoring the milking history record.', 500);
        }
    }
}
