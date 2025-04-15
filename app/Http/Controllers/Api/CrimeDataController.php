<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\CrimeReport;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CrimeDataController extends Controller
{
    /**
     * Retrieve crime report data via API.
     * This fulfills requirement CP006: API Access for Crime Data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Validate the API key
        $apiKey = $this->validateApiKey($request);
        if (!$apiKey) {
            return response()->json(['error' => 'Invalid or missing API key'], 401);
        }

        // Check if the user has an active subscription
        $hasActiveSubscription = Subscription::where('user_id', $apiKey->user_id)
            ->active()
            ->exists();

        if (!$hasActiveSubscription) {
            return response()->json(['error' => 'No active subscription found'], 403);
        }

        // Rate limit checking
        $rateLimitKey = 'api_rate_limit:' . $apiKey->id;
        $requestCount = Cache::get($rateLimitKey, 0);

        if ($requestCount >= $apiKey->rate_limit) {
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }

        // Increment the request count
        Cache::put($rateLimitKey, $requestCount + 1, now()->addMinutes(1));

        // Update last used timestamp
        $apiKey->markAsUsed();

        // Process query parameters
        $query = CrimeReport::with('crimeType')
            ->approved()
            ->select(['id', 'crime_type_id', 'latitude', 'longitude', 'description', 'occurred_at']);

        // Apply filters if provided
        if ($request->has('from_date')) {
            $query->where('occurred_at', '>=', $request->input('from_date'));
        }

        if ($request->has('to_date')) {
            $query->where('occurred_at', '<=', $request->input('to_date'));
        }

        if ($request->has('crime_type')) {
            $query->whereHas('crimeType', function ($q) use ($request) {
                $q->where('name', $request->input('crime_type'));
            });
        }

        if ($request->has('lat') && $request->has('lng') && $request->has('radius')) {
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            $radius = $request->input('radius');

            // Haversine formula for calculating distance
            $query->whereRaw("(
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) <= ?", [$lat, $lng, $lat, $radius]);
        }

        // Get the results with pagination
        $perPage = $request->input('per_page', 20);
        $crimeReports = $query->paginate($perPage);

        return response()->json($crimeReports);
    }

    /**
     * Validate the API key from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\ApiKey|null
     */
    private function validateApiKey(Request $request)
    {
        $keyString = $request->header('X-API-Key');

        if (!$keyString) {
            return null;
        }

        $apiKey = ApiKey::where('key', $keyString)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        return $apiKey;
    }
}
