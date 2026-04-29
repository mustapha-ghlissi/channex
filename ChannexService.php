<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ChannexService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.channex.api_key');
        $this->baseUrl = config('services.channex.base_url');
        $this->timeout = config('services.channex.timeout', 30);

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * Get all properties from Channex
     */
    public function getProperties(array $params = []): array
    {
        try {
            $response = $this->client->get('/properties', [
                'query' => $params
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get properties', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get single property
     */
    public function getProperty(string $propertyId): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}");
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get property', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new property
     */
    public function createProperty(array $data): array
    {
        try {
            $response = $this->client->post('/properties', [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to create property', ['data' => $data, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update property
     */
    public function updateProperty(string $propertyId, array $data): array
    {
        try {
            $response = $this->client->put("/properties/{$propertyId}", [
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to update property', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get available rooms for a property
     */
    public function getRooms(string $propertyId): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}/rooms");
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get rooms', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get listing channels
     */
    public function getChannels(string $propertyId): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}/channels");
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get channels', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Connect property to a channel
     */
    public function connectChannel(string $propertyId, array $channelData): array
    {
        try {
            $response = $this->client->post("/properties/{$propertyId}/channels", [
                'json' => $channelData
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to connect channel', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get availability
     */
    public function getAvailability(string $propertyId, string $startDate, string $endDate): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}/availability", [
                'query' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get availability', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update availability
     */
    public function updateAvailability(string $propertyId, array $availability): array
    {
        try {
            $response = $this->client->put("/properties/{$propertyId}/availability", [
                'json' => $availability
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to update availability', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get rates
     */
    public function getRates(string $propertyId, string $startDate, string $endDate): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}/rates", [
                'query' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get rates', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update rates
     */
    public function updateRates(string $propertyId, array $rates): array
    {
        try {
            $response = $this->client->put("/properties/{$propertyId}/rates", [
                'json' => $rates
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to update rates', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get reservations
     */
    public function getReservations(string $propertyId, array $params = []): array
    {
        try {
            $response = $this->client->get("/properties/{$propertyId}/reservations", [
                'query' => $params
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Channex: Failed to get reservations', ['property_id' => $propertyId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Test API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->client->get('/');
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            Log::error('Channex: Connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
