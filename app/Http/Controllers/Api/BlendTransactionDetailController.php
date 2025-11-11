<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlendTransactionDetail\UpdateBlendTransactionDetailRequest;
use Illuminate\Support\Facades\DB;
use App\Models\BlendTransactionDetail;
use App\Http\Resources\BlendTransactionDetailResource;

class BlendTransactionDetailController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $detail = BlendTransactionDetail::with('material')->findOrFail($id);
            return $this->sendResponse(
                new BlendTransactionDetailResource($detail),
                'Successfully retrieved blend transaction detail.'
            );
        }  catch (\Throwable $th) {
            return $this->sendError(
                $th->getMessage(),
                $th->getCode() 
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlendTransactionDetailRequest $request, string $id)
    {
        $validate = $request->getData();
        try {
            DB::beginTransaction();
            $detail = BlendTransactionDetail::findOrFail($id);
            $detail->update($validate);
            $detail->save();

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError(
                $th->getMessage(),
                $th->getCode()
            );
        }
    }
}
