<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feeding\StoreFeedingRequest;
use App\Http\Requests\Feeding\UpdateFeedingRequest;
use App\Http\Resources\FeedingResource;
use App\Models\Feeding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $feedings = Feeding::with(['cage', 'feedLocation'])->get();
            if ($feedings->isEmpty()) {
                return $this->sendResponse([], 'No feeding records found');
            }
            \Log::info("Fetched " . $feedings->count() . " feeding records.");
            \Log::debug("Feeding Records Data: " . $feedings->toJson());
            return $this->sendResponse(
                FeedingResource::collection($feedings),
                'Successfully retrieved feeding records.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching feeding records: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedingRequest $request)
    {

        try {
            $data = $request->getData();
            DB::beginTransaction();
            $feeding = Feeding::create($data);
            \Log::info("Created feeding record with ID: " . $feeding->id);
            $feeding->decreaseFeedStock();
            DB::commit();
            return $this->sendResponse(
                new FeedingResource($feeding),
                'Feeding record created successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error creating feeding record: " . $e->getMessage());
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
            $feeding = Feeding::with(['cage', 'feedLocation'])->findOrFail($id);
            \Log::info("Fetched feeding record with ID: " . $id);
            return $this->sendResponse(
                new FeedingResource($feeding),
                'Successfully retrieved feeding record.'
            );
        } catch (\Exception $e) {
            \Log::error("Error fetching feeding record with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedingRequest $request, string $id)
    {
        try{
            $data = $request->getData();
            DB::beginTransaction();
            $feeding = Feeding::findOrFail($id);
            if(isset($data['qty'])){
                $feeding->increaseFeedStock();
                $feeding->feedLocation->decreaseStock($data['qty']);
            }
            $feeding->update($data);
            
            DB::commit();
            return $this->sendResponse(
                new FeedingResource($feeding),
                'Feeding record updated successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error updating feeding record with ID " . $id . ": " . $e->getMessage());
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
        try{
            DB::beginTransaction();
            $feeding = Feeding::findOrFail($id);
            \Log::info("Deleting feeding record with ID: " . $feeding->id);
            $feeding->increaseFeedStock();
            $feeding->deleted_by = auth()->user()->id;
            $feeding->save();
            $feeding->delete();
            DB::commit();
            return $this->sendResponse(
                new FeedingResource($feeding),
                'Feeding record deleted successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error deleting feeding record with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }

    public function restore(string $id){
        try{
            DB::beginTransaction();
            $feeding = Feeding::onlyTrashed()->findOrFail($id);
            \Log::info("Restoring feeding record with ID: " . $feeding->id);
            $feeding->decreaseFeedStock();
            $feeding->restore();
            DB::commit();
            return $this->sendResponse(
                new FeedingResource($feeding),
                'Feeding record restored successfully.'
            );
        } catch (\Exception $e) {
            \Log::error("Error restoring feeding record with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(), $e->getCode() ?: 500
            );
        }
    }
}
