<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlendTransaction\StoreBlendTransactionRequest;
use App\Http\Requests\BlendTransaction\UpdateBlendTransactionRequest;
use App\Http\Resources\BlendTransactionResource;
use App\Models\BlendTransactionDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\BlendTransaction;
use Illuminate\Support\Facades\DB;
use Log;

class BlendTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-blend-materials');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blendTransactions = BlendTransaction::withoutTrashed()
            ->with(['feed', 'createdBy', 'updatedBy', 'materials'])
            ->latest("updated_at")
            ->get();
        
        if ($blendTransactions->isEmpty()) {
            return $this->sendResponse(
                [], 
                'No blend transactions data found'
            );
        }
        return $this->sendResponse(
            BlendTransactionResource::collection($blendTransactions),
            'Successfully get blend transactions data'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlendTransactionRequest $request)
    {
        $validated = $request->getData();
        \Log::info('Data Request Create Blend Transaction: ' .json_encode($validated));
        
        try {
            DB::beginTransaction();
            $blendTransaction = BlendTransaction::create($validated);
            if (isset($validated['materials']) && is_array($validated['materials'])) {
                \Log::info('Materials to create:'. json_encode($validated['materials']));
                
                    $createdMaterials = $blendTransaction->materials()->createMany($validated['materials']);
                    foreach ($createdMaterials as $material) {
                        $material->reduceStockMaterial();
                    }
                
            }
            $blendTransaction->increaseFeedStock();
            DB::commit();
            return $this->sendResponse(
                new BlendTransactionResource($blendTransaction),
                'Successfully created blend transaction'
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error("Failed to create blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(), 
                $th->getCode()
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $blendTransaction = BlendTransaction::with(['feed', 'createdBy', 'updatedBy', 'materials'])
                ->findOrFail($id);
            return $this->sendResponse(
                new BlendTransactionResource($blendTransaction),
                'Successfully retrieved blend transaction'
            );
        } catch (\Throwable $th) {
            \Log::error("Failed to retrieve blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(), 
                $th->getCode()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlendTransactionRequest $request, string $id)
    {
        $validated = $request->getData();
        \Log::info("Data to update blend transaction: " . json_encode($validated));
        
        try {
            DB::beginTransaction();
            $blendTransaction = BlendTransaction::findOrFail($id);
            $blendTransaction->update($validated);
            DB::commit();
            return $this->sendResponse(
                new BlendTransactionResource($blendTransaction),
                'Successfully updated blend transaction'
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error("Failed to update blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            \Log::info('Delete Blend Transaction with id: '. json_encode($id));
            DB::beginTransaction();
            $blendTransaction = BlendTransaction::findOrFail($id);
            $materials = $blendTransaction->materials;
            foreach ($materials as $material) {
                $material->rollbackStockMaterial();
                $material->delete();
                \Log::info('Deleted material ID: ' . $material->id );
            }
            $blendTransaction->rollbackFeedStock();
            $blendTransaction->delete();
            DB::commit();
            \Log::info('Deleted Blend Transaction success');
            return $this->sendResponse(
                null,
                'Successfully deleted blend transaction'
            ); 
        } catch (\Throwable $th) {
            \Log::error("Failed to delete blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }

    /**
     * Restore the specified resource from storage.
     */    
    public function restore(string $id)
    {
        try {
            \Log::info('Restore Blend Transaction with id: '. $id);
            DB::beginTransaction();
            $blendTransaction = BlendTransaction::onlyTrashed()->findOrFail($id);
            $materials = BlendTransactionDetail::onlyTrashed()->where('blend_transaction_id', $blendTransaction->id)->get();
            
            foreach ($materials as $material) {
                $material->restore();
                \Log::info('Restored material ID: ' . $material->id );
                $material->reduceStockMaterial();
            }
            $blendTransaction->restore();
            $blendTransaction->increaseFeedStock();
            DB::commit();
            return $this->sendResponse(
                new BlendTransactionResource($blendTransaction),
                'Successfully restored blend transaction'
            );
        } catch (\Throwable $th) {
            \Log::error("Failed to restore blend transaction: " . $th->getMessage());
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }

    
}
