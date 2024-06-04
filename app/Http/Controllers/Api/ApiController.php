<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getPrediction(Request $request)
    {
        // dd($request);
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $api_key = 'ecc0077b617286e1e79731c8473fb975';
        // Call API 1
        $response1 = Http::get('https://api.openweathermap.org/data/2.5/forecast/daily', [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $api_key,
        ]);

        $weatherData = $this->extractCurrentWeatherData($response1->json());
        // Call API 2
        $response2 = Http::get('https://api.open-meteo.com/v1/elevation', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        
        $modelInput = $this->prepareModelInput($weatherData['rain'], $response2['elevation'][0]);
        // dd($modelInput);
        
        $prediction = $this->callMachineLearningModel($modelInput);

        return response()->json([
            'prediction' => $prediction,
        ]);
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
    private function prepareModelInput($data1, $data2)
    {
        return [
            'rainfall' => $data1,
            'elevation' => $data2,
        ];
    }

    private function callMachineLearningModel($input)
    {
        // Call your machine learning model here
        $modelResponse = Http::post('https://yourmlmodel.example.com/predict', $input);

        return $modelResponse->json();
    }
}
