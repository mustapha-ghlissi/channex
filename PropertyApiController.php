<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\ChannexService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class PropertyApiController extends Controller
{
    protected ChannexService $channexService;

    public function __construct(ChannexService $channexService)
    {
        $this->channexService = $channexService;
    }

    /**
     * Get all properties
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $properties = Property::with('rooms', 'channels')
                ->when($request->search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $properties,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single property
     */
    public function show(Property $property): JsonResponse
    {
        try {
            $property->load('rooms', 'channels', 'availabilities');

            return response()->json([
                'success' => true,
                'data' => $property,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get property availability
     */
    public function availability(Request $request, Property $property): JsonResponse
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($property->channex_id) {
                $availability = $this->channexService->getAvailability(
                    $property->channex_id,
                    $validated['start_date'],
                    $validated['end_date']
                );

                return response()->json([
                    'success' => true,
                    'data' => $availability,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Property not linked to Channex',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get property rates
     */
    public function rates(Request $request, Property $property): JsonResponse
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            if ($property->channex_id) {
                $rates = $this->channexService->getRates(
                    $property->channex_id,
                    $validated['start_date'],
                    $validated['end_date']
                );

                return response()->json([
                    'success' => true,
                    'data' => $rates,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Property not linked to Channex',
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update property availability
     */
    public function updateAvailability(Request $request, Property $property): JsonResponse
    {
        try {
            $validated = $request->validate([
                'availability' => 'required|array',
            ]);

            if (!$property->channex_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not linked to Channex',
                ], 400);
            }

            $result = $this->channexService->updateAvailability(
                $property->channex_id,
                $validated['availability']
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update property rates
     */
    public function updateRates(Request $request, Property $property): JsonResponse
    {
        try {
            $validated = $request->validate([
                'rates' => 'required|array',
            ]);

            if (!$property->channex_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not linked to Channex',
                ], 400);
            }

            $result = $this->channexService->updateRates(
                $property->channex_id,
                $validated['rates']
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get property channels
     */
    public function channels(Property $property): JsonResponse
    {
        try {
            $channels = $property->channels()->get();

            return response()->json([
                'success' => true,
                'data' => $channels,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
