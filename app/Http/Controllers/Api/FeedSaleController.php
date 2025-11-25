<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedSale\StoreFeedSaleRequest;
use App\Http\Resources\FeedSaleResource;
use App\Models\FeedSale;
use App\Models\FeedSaleDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeedSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedSale = FeedSale::withoutTrashed()
            ->with(['details', 'createdBy', 'updatedBy'])
            ->latest('sale_date')
            ->where('location_id', '!=', null)
            ->get();
        if ($feedSale->isEmpty()) {
            return $this->sendResponse([], 'No feed sales data found');
        }

        return $this->sendResponse(FeedSaleResource::collection($feedSale), 'Feed sales data retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedSaleRequest $request)
    {
        $validated = $request->getData();
        \Log::info('Data Request Create Feed Sale: ' .json_encode($validated));
        try {
            DB::beginTransaction();
            $feedSale = FeedSale::create($validated);
            \Log::info('Created Feed Sale: ' .json_encode($feedSale));
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
                \Log::info('Created Feed Sale Detail: ' .json_encode($feeds));
            }
            $feedSale->sumTotal();
            \Log::info('Calculated Total for Feed Sale ID ' . $feedSale->id . ': ' . $feedSale->total);
            DB::commit();
            
            return $this->sendResponse(new FeedSaleResource($feedSale), 'Feed sale created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error creating feed sale: ' . $th->getMessage());
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
            return $this->sendResponse(new FeedSaleResource($feedSale), 'Feed sale retrieved successfully');
        } catch (\Throwable $th) {
            \Log::error('Error retrieving feed sale: ' . $th->getMessage());
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
            \Log::info('Delete Feed Sale: ' . json_encode($feedSale));
            $details = $feedSale->details;
            \Log::info('Feed Sale Details: '. json_encode($details));
            foreach ($details as $detail) {
                $detail->increaseFeedStock();
                $detail->delete();
                \Log::info('Delete Feed Sale Detail: '. json_encode($detail));
            }
            $feedSale->delete();
            DB::commit();
            return $this->sendResponse(new FeedSaleResource($feedSale),'Successfully deleted feed sale');
        }catch (\Throwable $th) {
            \Log::error('Error deleting feed sale: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function restore(string $id){
        try{
            DB::beginTransaction();
            $feedSale = FeedSale::onlyTrashed()->findOrFail($id);
            $details = FeedSaleDetail::onlyTrashed()->where('feed_sale_id', $feedSale->id)->get();
            \Log::info('Restore Feed Sale'. json_encode($feedSale));
            foreach ($details as $detail) {
                $detail->decreaseFeedStock();
                $detail->restore();
            }
            $feedSale->restore();
            DB::commit();
            return $this->sendResponse(new FeedSaleResource($feedSale),'Successfully restored feed sale');
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
