<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedLocationResource;
use App\Models\FeedLocation;
use Illuminate\Http\Request;

class FeedLocationController extends Controller
{
    public function index()
    {
        try {
            $feedLocation = FeedLocation::query()
                ->when(request('location'), function ($query, $locationId) {
                    $query->where('location_id', $locationId);
                })
                ->get();
            if ($feedLocation->isEmpty()) {
                return $this->sendResponse([], 'No feed locations found');
            }
            \Log::info("Fetched " . $feedLocation->count() . " feed locations.");
            \Log::debug("Feed Locations Data: " . $feedLocation->toJson());
        } catch (\Exception $e) {
            \Log::error("Error fetching feed locations: " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
        return $this->sendResponse(FeedLocationResource::collection($feedLocation), 'Successfully retrieved feed locations.');
    }

    public function show(string $id)
    {
        try {
            $feedLocation = FeedLocation::findOrFail($id);
            \Log::info("Fetched feed location with ID: " . $id);
        } catch (\Exception $e) {
            \Log::error("Error fetching feed location with ID " . $id . ": " . $e->getMessage());
            return $this->sendError(
                $e->getMessage(),
                $e->getCode() ?: 500
            );
        }
        return $this->sendResponse(FeedLocationResource::collection($feedLocation), 'Successfully retrieved feed location.');
    }
}
