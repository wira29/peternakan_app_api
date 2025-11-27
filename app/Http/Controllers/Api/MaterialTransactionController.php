<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialTransaction\StoreMaterialTransactionRequest;
use App\Http\Resources\MaterialTransactionResource;
use App\Models\MaterialTransaction;
use App\Models\MaterialTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialTransaction = MaterialTransaction::withoutTrashed()
            ->with(['details', 'createdBy', 'updatedBy'])
            ->latest('transaction_date')
            ->get();
        if ($materialTransaction->isEmpty()) {
            return $this->sendResponse([], 'No material transactions data found');
        }

        return $this->sendResponse(MaterialTransactionResource::collection($materialTransaction), 'Material transactions data retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialTransactionRequest $request)
    {
        $validated = $request->getData();
        \Log::info('Data Request Create Feed Sale: ' .json_encode($validated));
        try {
            DB::beginTransaction();
            $materialTransaction = MaterialTransaction::create($validated);
            \Log::info('Created Material Transaction: ' .json_encode($materialTransaction));
            foreach ($validated['materials'] as $materialData) {
                \Log::info('Processing Material Data: ' . json_encode($materialData));
                $material = MaterialTransactionDetail::create([
                    'material_transaction_id' => $materialTransaction->id,
                    'material_id' => $materialData['material_id'],
                    'qty' => $materialData['qty'],
                    'price' => $materialData['price'],
                    'created_by' => $validated['created_by'],
                ]);
                $material->calculateTotal();
                $material->increaseMaterialStock();
                \Log::info('Created Material Transaction Detail: ' .json_encode($material));
            }
            $materialTransaction->sumTotal();
            \Log::info('Calculated Total for Material Transaction ID ' . $materialTransaction->id . ': ' . $materialTransaction->total);
            DB::commit();
            
            return $this->sendResponse(new MaterialTransactionResource($materialTransaction), 'Material transaction created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error creating material transaction: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $materialTransaction = MaterialTransaction::findOrFail($id);
            return $this->sendResponse(new MaterialTransactionResource($materialTransaction), 'Material transaction retrieved successfully');
        } catch (\Throwable $th) {
            \Log::error('Error retrieving material transaction: ' . $th->getMessage());
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
            $materialTransaction = MaterialTransaction::findOrFail($id);
            \Log::info('Delete Material Transaction: ' . json_encode($materialTransaction));
            $details = $materialTransaction->details;
            \Log::info('Material Transaction Details: '. json_encode($details));
            foreach ($details as $detail) {
                $detail->decreaseMaterialStock();
                $detail->delete();
                \Log::info('Delete Material Transaction Detail: '. json_encode($detail));
            }
            $materialTransaction->delete();
            DB::commit();
            return $this->sendResponse(new MaterialTransactionResource($materialTransaction),'Successfully deleted material transaction');
        }catch (\Throwable $th) {
            \Log::error('Error deleting material transaction: ' . $th->getMessage());
            return $this->sendError($th->getMessage(), $th->getCode() ?: 500);
        }
    }
    public function restore(string $id){
        try{
            DB::beginTransaction();
            $materialTransaction = MaterialTransaction::onlyTrashed()->findOrFail($id);
            $details = MaterialTransactionDetail::onlyTrashed()->where('material_transaction_id', $materialTransaction->id)->get();
            \Log::info('Restore Material Transaction'. json_encode($materialTransaction));
            foreach ($details as $detail) {
                $detail->increaseMaterialStock();
                $detail->restore();
            }
            $materialTransaction->restore();
            DB::commit();
            return $this->sendResponse(new MaterialTransactionResource($materialTransaction),'Successfully restored material transaction');
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
