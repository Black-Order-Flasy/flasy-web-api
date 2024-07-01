<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirestoreService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getEvacuation(Request $request)
    {
        $collectionName = 'evacuation_points';

        $firestoreService = app()->make(FirestoreService::class, ['collection' => $collectionName]);

        $collectionData = $firestoreService->getDocuments();

        // Convert latitude and longitude to number
        $collectionData = array_map(function ($document) {
            if (isset($document['latitude'])) {
                $document['latitude'] = (float) $document['latitude'];
            }
            if (isset($document['longitude'])) {
                $document['longitude'] = (float) $document['longitude'];
            }
            return $document;
        }, $collectionData);

        $district = $request->district ?? '';

        if ($district) {
            $district = explode(' ', $request->district)[1];

            $collectionData = array_filter($collectionData, function ($document) use ($district) {
                return isset($document['district']) && $document['district'] === $district;
            });

            $collectionData = array_values($collectionData);
        }

        return response()->json($collectionData);
    }


    public function forecastFlood(Request $request)
    {
        // Validasi request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $api_key = config('services.weather_api.key');
        $ml_model_url = config('services.ml_model.url');

        // Panggil API 1 (cuaca)
        $response1 = Http::get('https://api.openweathermap.org/data/2.5/forecast/daily', [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $api_key,
        ]);
        // Panggil API 2 (streamflow)
        $response2 = Http::get('https://flood-api.open-meteo.com/v1/flood', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'daily' => "river_discharge",
            'forecast_days' => '7',
            // 'timeformat' => "unixtime",
            'timezone' => "Asia/Singapore",
        ]);
        // dd($response2->json());
        // $streamflow = $response2['daily']['river_discharge'][0];


        // Iterasi melalui setiap entri dalam response API 1
        $predictions = [];
        foreach ($response1->json()['list'] as $index => $weatherEntry) {
            $weatherData = $this->extractFutureWeatherData($weatherEntry);
            $rainfall = $weatherData['rain'];
            $weather = $weatherData['weather'];

            $streamflow = $response2['daily']['river_discharge'][$index];

            $timestamp = $weatherEntry['dt'];
            $date = date('Y-m-d', $timestamp);
            $hour = date('H:i:s', $timestamp);

            if (!$rainfall) {
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => 'Aman',
                    'weather' => $weather,
                    'streamflow' => $streamflow,
                    'rainfall' => $rainfall,
                ];
            } else {
                $modelInput = $this->prepareModelInput($rainfall, $streamflow);

                $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);

                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => $prediction['category'], // hanya menampilkan kategori
                    'weather' => $weather,
                    'streamflow' => $streamflow,
                    'rainfall' => $rainfall,
                ];
            }

        }

        return response()->json($predictions);
    }
    public function todayFlood(Request $request)
    {
        // Validasi request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $api_key = config('services.weather_api.key');
        $ml_model_url = config('services.ml_model.url');

        // Panggil API 1 (cuaca)
        // $response1 = Http::get('https://api.openweathermap.org/data/2.5/forecast/daily', [
        $response1 = Http::get('https://pro.openweathermap.org/data/2.5/forecast/hourly', [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $api_key,
            'cnt' => 24,
        ]);

        $response2 = Http::get('https://flood-api.open-meteo.com/v1/flood', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'daily' => "river_discharge",
            "forecast_days" => '1',
            'timezone' => "Asia/Singapore",
        ]);

        $streamflow = $response2['daily']['river_discharge'][0];

        // Iterasi melalui setiap entri dalam response API 1
        $predictions = [];
        foreach ($response1->json()['list'] as $weatherEntry) {
            $weatherData = $this->extractWeatherData($weatherEntry);
            $rainfall = $weatherData['rain'];
            $weather = $weatherData['weather'];

            $timestamp = $weatherEntry['dt'];
            $datetime = new \DateTime("@$timestamp");
            $datetime->setTimezone(new \DateTimeZone('Asia/Singapore'));
            $date = $datetime->format('Y-m-d');
            $hour = $datetime->format('H:i:s');

            if (!$rainfall) {
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => 'Aman',
                    'weather' => $weather,
                    'streamflow' => $streamflow,
                    'rainfall' => $rainfall,

                ];
            } else {
                $modelInput = $this->prepareModelInput($rainfall, $streamflow);

                $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);

                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => $prediction['category'],
                    'weather' => $weather,
                    'streamflow' => $streamflow,
                    'rainfall' => $rainfall,
                ];
            }
        }

        return response()->json($predictions);
    }

    public function predictionFlood(Request $request)
    {

        // dd($request);
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $api_key = config('services.weather_api.key');
        $ml_model_url = config('services.ml_model.url');

        // Call API 1
        $response1 = Http::get('https://api.openweathermap.org/data/2.5/forecast/daily', [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $api_key
        ]);

        $response2 = Http::get('https://flood-api.open-meteo.com/v1/flood', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'daily' => "river_discharge",
            "forecast_days" => '1',
            'timezone' => "Asia/Singapore",
        ]);

        // dd($response2->json());

        $weatherData = $this->extractCurrentWeatherData($response1->json());
        $rainfall = $weatherData['rain'];
        $weather = $weatherData['weather'];
        $streamflow = $response2['daily']['river_discharge'][0];
        // dd($streamflow);
        // $elevation = $response2['elevation'][0];

        if (!$rainfall) {
            $category = 'Aman';
            $description = $this->getPredictionDescription($category);
            return response()->json([
                'prediction' => $category,
                'description' => $description,
                'weather' => $weather,
                'streamflow' => $streamflow,
                'rainfall' => $rainfall,
            ]);
        } else {
            $modelInput = $this->prepareModelInput($rainfall, $streamflow);
            $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);
            // dd($prediction);
            $category = $prediction['category'];
            $description = $this->getPredictionDescription($category);

            return response()->json([
                'prediction' => $category,
                'description' => $description,
                'streamflow' => $streamflow,
                'weather' => $weather,
                'rainfall' => $rainfall,
            ]);
        }


    }
    public function getNearbyFlood(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $longitude = $request->longitude;
        $latitude = $request->latitude;

        $radius = 2;
        $points = $this->generateRandomPoints($latitude, $longitude, $radius, 4);

        $predictions = [];
        $client = new Client();

        foreach ($points as $point) {
            // dd($response->json());
            $reverseGeoResponse = $client->request('GET', 'https://nominatim.openstreetmap.org/reverse', [
                'query' => [
                    'format' => 'json',
                    'lat' => $point['latitude'],
                    'lon' => $point['longitude'],
                    'addressdetails' => 1
                ],
                'headers' => [
                    'User-Agent' => 'Flasy/1.0 (contact@flasy.com)'
                ]
            ]);
            // dd($reverseGeoResponse);

            if ($reverseGeoResponse) {
                $address = json_decode($reverseGeoResponse->getBody(), true)['display_name'] ?? 'Address not found';
            } else {
                $address = 'Address not found';
            }

            // $response = Http::get('https://flasy-api-jrqtpa5u6q-et.a.run.app/api/prediction-flood', [
            $response = Http::get(url('/api/prediction-flood'), [
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude']
            ]);

            $data = [
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude'],
                'prediction' => $response['prediction'],
                'address' => $address,

            ];
            $predictions[] = $data;
        }

        return response()->json($predictions);
    }

    private function generateRandomPoints($latitude, $longitude, $radius, $num_points)
    {
        $points = [];
        $earth_radius = 6371; // Earth radius in km

        while (count($points) < $num_points) {
            $random_point = $this->getRandomPoint($latitude, $longitude, $radius, $earth_radius);

            // Check if the point is not too close to existing points
            $too_close = false;
            foreach ($points as $point) {
                if ($this->calculateDistance($random_point['latitude'], $random_point['longitude'], $point['latitude'], $point['longitude']) < 0.5) {
                    $too_close = true;
                    break;
                }
            }

            if (!$too_close) {
                $points[] = $random_point;
            }
        }

        return $points;
    }

    private function getRandomPoint($latitude, $longitude, $radius, $earth_radius)
    {
        // Convert radius from km to degrees
        $radius_in_degrees = $radius / $earth_radius;

        // Random distance and angle
        $distance = lcg_value() * $radius_in_degrees;
        $angle = lcg_value() * 2 * pi();

        // Calculate new point
        $new_latitude = asin(sin(deg2rad($latitude)) * cos($distance) + cos(deg2rad($latitude)) * sin($distance) * cos($angle));
        $new_longitude = deg2rad($longitude) + atan2(sin($angle) * sin($distance) * cos(deg2rad($latitude)), cos($distance) - sin(deg2rad($latitude)) * sin($new_latitude));

        return [
            'latitude' => rad2deg($new_latitude),
            'longitude' => rad2deg($new_longitude),
        ];
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // Earth radius in km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earth_radius * $c;

        return $distance;
    }
    private function extractCurrentWeatherData($responseData)
    {
        $currentTimestamp = time();
        $closestData = null;
        $minDifference = PHP_INT_MAX;

        foreach ($responseData['list'] as $data) {
            $timeDifference = abs($currentTimestamp - $data['dt']);
            if ($timeDifference < $minDifference) {
                $minDifference = $timeDifference;
                $closestData = $data;
            }
        }

        $weather = $closestData['weather'][0]['main'];
        $rain = isset($closestData['rain']) ? $closestData['rain'] : null;

        return [
            'datetime' => gmdate("Y-m-d\TH:i:s\Z", $closestData['dt']),
            'weather' => $weather,
            'rain' => $rain,
        ];
    }

    private function extractWeatherData($weatherEntry)
    {
        $weather = isset($weatherEntry['weather'][0]['main']) ? $weatherEntry['weather'][0]['main'] : null;
        $rain = isset($weatherEntry['rain']['1h']) ? $weatherEntry['rain']['1h'] : 0;

        return [
            'weather' => $weather,
            'rain' => $rain,
        ];
    }
    private function extractFutureWeatherData($weatherEntry)
    {
        $weather = isset($weatherEntry['weather'][0]['main']) ? $weatherEntry['weather'][0]['main'] : null;
        $rain = isset($weatherEntry['rain']) ? $weatherEntry['rain'] : 0;

        return [
            'weather' => $weather,
            'rain' => $rain,
        ];
    }
    private function prepareModelInput($data1, $data2)
    {
        return [
            'rainfall' => $data1,
            'forest_ratio' => '0.59',
            'streamflow' => $data2,
        ];
    }

    private function callMachineLearningModel($url, $input)
    {

        $modelResponse = Http::post($url, $input);
        return $modelResponse->json();

    }
    private function getPredictionDescription($category)
    {
        $descriptions = [
            'Aman' => 'Daerah anda aman dari bencana banjir, akan tetapi tetap waspada dan jangan gegabah',
            'Waspada' => 'Harap waspada terhadap ancaman banjir, selalu persiapkan hal yang diperlukan sebelum terlambat',
            'Awas' => 'Persiapkan diri anda, lengkapi kebutuhan hingga informasi lebih lanjut',
            'Siaga' => 'Daerah anda aman dari bencana banjir, akan tetapi tetap waspada dan jangan gegabah',
        ];

        return $descriptions[$category] ?? 'Deskripsi tidak tersedia';
    }

}
