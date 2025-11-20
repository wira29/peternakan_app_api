<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedPurchase\StoreFeedPurchaseRequest;
use App\Http\Resources\FeedPurchaseResource;
use App\Models\FeedSale;
use App\Models\FeedSaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeedPurchase;

class FeedPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $feedPurchase = FeedPurchase::withoutTrashed()
            ->with(['details', 'createdBy', 'updatedBy'])
            ->latest('purchase_date')
            ->get();
        if ($feedPurchase->isEmpty()) {
            return $this->sendResponse([], 'No feed purchase data found');
        }

        return $this->sendResponse(FeedPurchaseResource::collection($feedSale), 'Feed purchase data retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedPurchaseRequest $request)
    {
        \Log::info('Store Feed Purchase Request Received');
        $validated = $request->getData();
        \Log::info('Data Request Create Feed Sale: ' .json_encode($validated));
        try {
            DB::beginTransaction();
            $feedPurchase = FeedPurchase::create($validated);
            \Log::info('Created Feed Purchase: ' .json_encode($feedPurchase));
            foreach ($validated['feeds'] as $feedData) {
                \Log::info('Processing Feed Data: ' . json_encode($feedData));
                $feeds = FeedSaleDetail::create([
                    'feed_purchase_id' => $feedPurchase->id,
                    'feed_id' => $feedData['feed_id'],
                    'qty' => $feedData['qty'],
                    'price_per_unit' => $feedData['price_per_unit'],
                    'created_by' => $validated['created_by'],
                ]);
                $feeds->calculateTotal();
                //$feeds->decreaseFeedStock();
                \Log::info('Created Feed Purchase Detail: ' .json_encode($feeds));
            }
            $feedPurchase->sumTotal();
            \Log::info('Calculated Total for Feed Purchase ID ' . $feedPurchase->id . ': ' . $feedPurchase->total_amount);
            DB::commit();
            
            return $this->sendResponse(new FeedPurchaseResource($feedPurchase), 'Feed purchase created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error creating feed purchase: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $feedPurchase = FeedPurchase::findOrFail($id);
            return $this->sendResponse(new FeedPurchaseResource($feedPurchase), 'Feed purchase retrieved successfully');
        } catch (\Throwable $th) {
            \Log::error('Error retrieving feed purchase: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();
            $feedPurchase = FeedPurchase::findOrFail($id);
            \Log::info('Delete Feed Purchase: ' . json_encode($feedPurchase));
            $details = $feedPurchase->details;
            \Log::info('Feed Purchase Details: '. json_encode($details));
            foreach ($details as $detail) {
                //$detail->increaseFeedStock();
                $detail->delete();
                \Log::info('Delete Feed Purchase Detail: '. json_encode($detail));
            }
            $feedPurchase->delete();
            DB::commit();
            return $this->sendResponse(new FeedPurchaseResource($feedPurchase),'Successfully deleted feed purchase');
        }catch (\Throwable $th) {
            \Log::error('Error deleting feed purchase: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function restore(string $id){
        try{
            DB::beginTransaction();
            $feedSale = FeedSale::onlyTrashed()->findOrFail($id);
            if (!$feedSale) {
                return $this->sendError('Feed purchase not found or not deleted', 404);
            }
            $details = FeedSaleDetail::onlyTrashed()->where('feed_sale_id', $feedSale->id)->get();
            \Log::info('Restore Feed Purchase'. json_encode($feedSale));
            foreach ($details as $detail) {
                // $detail->decreaseFeedStock();
                $detail->restore();
            }
            $feedSale->restore();
            DB::commit();
            return $this->sendResponse(new FeedPurchaseResource($feedSale),'Successfully restored feed purchase');
        }catch (\Throwable $th) {
            DB::rollBack();
            \Log::error("Failed to restore feed purchase: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }
}
