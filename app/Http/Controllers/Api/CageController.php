<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cage;
use App\Http\Requests\Cage\StoreCageRequest;
use App\Http\Requests\Cage\UpdateCageRequest;
use Symfony\Component\HttpFoundation\Response;

class CageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-cages');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all cages");
        return Cage::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCageRequest $request)
    {
        $validated = $request->validated();

        $cage = Cage::create($validated);
        \Log::info("Created new cage with ID: " . $cage->id);
        return response()->json($cage, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching cage with ID: " . $id);
        $cage = Cage::findOrFail($id);
        return response()->json($cage, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cage = Cage::findOrFail($id);
        $cage->update($request->getData());
        \Log::info("Updated cage with ID: " . $cage->id);
        return response()->json($cage, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cage = Cage::withTrashed()->findOrFail($id);
        $cage->restore();
        \Log::info("Restored cage with ID: " . $cage->id);
        return response()->json('Cage restored successfully.', Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $cage = Cage::withTrashed()->findOrFail($id);
        $cage->restore();
        \Log::info("Restored cage with ID: " . $cage->id);
        return response()->json('Cage restored successfully.', Response::HTTP_OK);
    }
}
