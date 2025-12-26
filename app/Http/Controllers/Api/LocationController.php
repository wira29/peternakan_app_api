<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-locations');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all locations");
        return Location::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();

        $location = Location::create($request->getData());
        \Log::info("Created new location with ID: " . $location->id);
        return response()->json($location, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching location with ID: " . $id);
        $location = Location::findOrFail($id);
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, string $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->getData());
        \Log::info("Updated location with ID: " . $location->id);
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);
        $location->deleted_by = auth()->user()->id;
        $location->save();
        $location->delete();
        \Log::info("Deleted location with ID: " . $location->id);
        return response()->json(['message' => 'Location deleted successfully'], Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $location = Location::withTrashed()->findOrFail($id);
        $location->restore();
        \Log::info("Restored location with ID: " . $location->id);
        return response()->json($location, Response::HTTP_OK);
    }
}
