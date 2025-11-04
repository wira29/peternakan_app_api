<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Http\Requests\Feed\StoreFeedRequest;
use App\Http\Requests\Feed\UpdateFeedRequest;
use Symfony\Component\HttpFoundation\Response;

class FeedController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:manage-feeds');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all feeds");
        return Feed::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedRequest $request)
    {
       $validated = $request->validated();

        $feed = Feed::create($request->getData());
        \Log::info("Created new feed with ID: " . $feed->id);
        return response()->json($feed, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching feed with ID: " . $id);
        $feed = Feed::findOrFail($id);
        return response()->json($feed, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedRequest $request, string $id)
    {
        try {
            $validatedData = $request->getData();
            $feed = Feed::findOrFail($id);
            $feed->fill($validatedData);

            if (!$feed->isDirty()) {
                Log::info("No changes detected for feed with ID: " . $id);

                return response()->json([
                    'message' => 'No changes detected. The original data is returned.',
                    'data'    => $feed
                ], Response::HTTP_OK);
            }

            $feed->save();
            Log::info("Updated feed with ID: " . $feed->id);

            return response()->json([
                'message' => 'Feed updated successfully.',
                'data'    => $feed
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error("Error updating feed with ID $id: " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the feed.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feed = Feed::findOrFail($id);
        $feed->delete();
        \Log::info("Deleted feed with ID: " . $feed->id);
        return response()->json('Feed deleted successfully', Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $feed = Feed::withTrashed()->findOrFail($id);
        $feed->restore();
        \Log::info("Restored feed with ID: " . $feed->id);
        return response()->json('Feed restored successfully.', Response::HTTP_OK);
    }
}
