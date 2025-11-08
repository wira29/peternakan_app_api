<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Goat\StoreGoatRequest;
use App\Http\Requests\Goat\UpdateGoatRequest;
use App\Http\Resources\GoatResource;
use App\Models\Goat;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class GoatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-goats');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goats = Goat::withoutTrashed()
            ->with(['breed', 'cage', 'father', 'mother', 'createdBy', 'updatedBy'])
            ->latest("updated_at")
            ->get();
        
        if ($goats->isEmpty()) {
            return $this->sendResponse(
                [], 
                'No goats data found'
            );
        }
        
        return $this->sendResponse(
            GoatResource::collection($goats),
            'Successfully get goats data'
        );
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoatRequest $request)
    {
        $validated = $request->getData();
        \Log::info("Data to create goat: " . json_encode($validated));
        $goat = Goat::create($validated);

        \Log::info("Created new goat with ID: " . $goat->code);
        return $this->sendResponse(
            new GoatResource($goat),
            'Goat created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code)
    {
        \Log::info("Fetching goat with ID: " . $code);
        $goat = Goat::findOrFail($code);
        return $this->sendResponse(
            new GoatResource($goat),
            'Goat retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoatRequest $request, string $code)
    {
        $validated = $request->validated();
        $goat = Goat::findOrFail($code);
        $goat->update($validated);
        \Log::info("Updated goat with ID: " . $goat->code);
        return response()->json($goat, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code)
    {
        $goat = Goat::findOrFail($code);
        $goat->delete();
        \Log::info("Deleted goat with ID: " . $goat->code);
        return response()->json('Goat deleted successfully', Response::HTTP_OK);
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $code)
    {
        $goat = Goat::withTrashed()->findOrFail($code);
        $goat->restore();
        \Log::info("Restored goat with ID: " . $goat->code);
        return response()->json('Goat restored successfully.', Response::HTTP_OK);
    }
}
