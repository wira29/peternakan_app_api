<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedPurchaseResource;
use App\Models\FeedSale;
use App\Models\FeedSaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $feedSale = FeedSale::withoutTrashed()
            ->with(['details', 'createdBy', 'updatedBy'])
            ->latest('sale_date')
            ->where('location_id', '==', null)
            ->where('created_by','==', auth()->user()->id )
            ->get();
        if ($feedSale->isEmpty()) {
            return $this->sendResponse([], 'No feed purchase data found');
        }

        return $this->sendResponse(FeedPurchaseResource::collection($feedSale), 'Feed purchase data retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->getData();
        \Log::info('Data Request Create Feed Sale: ' .json_encode($validated));
        try {
            DB::beginTransaction();
            $feedSale = FeedSale::create($validated);
            \Log::info('Created Feed Purchase: ' .json_encode($feedSale));
            foreach ($validated['feeds'] as $feedData) {
                \Log::info('Processing Feed Data: ' . json_encode($feedData));
                $feeds = FeedSaleDetail::create([
                    'feed_sale_id' => $feedSale->id,
                    'feed_id' => $feedData['feed_id'],
                    'qty' => $feedData['qty'],
                    'price_per_unit' => $feedData['price_per_unit'],
                    'created_by' => $validated['created_by'],
                ]);
                $feeds->calculateTotal();
                $feeds->decreaseFeedStock();
                \Log::info('Created Feed Purchase Detail: ' .json_encode($feeds));
            }
            $feedSale->sumTotal();
            \Log::info('Calculated Total for Feed Purchase ID ' . $feedSale->id . ': ' . $feedSale->total);
            DB::commit();
            
            return $this->sendResponse(new FeedPurchaseResource($feedSale), 'Feed purchase created successfully');
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
            $feedSale = FeedSale::findOrFail($id);
            return $this->sendResponse(new FeedPurchaseResource($feedSale), 'Feed purchase retrieved successfully');
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
            $feedSale = FeedSale::findOrFail($id);
            \Log::info('Delete Feed Purchase: ' . json_encode($feedSale));
            $details = $feedSale->details;
            \Log::info('Feed Purchase Details: '. json_encode($details));
            foreach ($details as $detail) {
                // $detail->increaseFeedStock();
                $detail->delete();
                \Log::info('Delete Feed Purchase Detail: '. json_encode($detail));
            }
            $feedSale->delete();
            DB::commit();
            return $this->sendResponse(new FeedPurchaseResource($feedSale),'Successfully deleted feed purchase');
        }catch (\Throwable $th) {
            \Log::error('Error deleting feed purchase: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function restore(string $id){
        try{
            DB::beginTransaction();
            $feedSale = FeedSale::onlyTrashed()->findOrFail($id);
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
            \Log::error("Failed to restore blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }
}
