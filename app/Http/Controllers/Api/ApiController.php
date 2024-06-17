<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getFuturePrediction(Request $request)
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
        // Panggil API 2 (elevasi)
        $response2 = Http::get('https://api.open-meteo.com/v1/elevation', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        // Ambil data elevasi
        $elevation = $response2['elevation'][0];

        // Iterasi melalui setiap entri dalam response API 1
        $predictions = [];
        foreach ($response1->json()['list'] as $weatherEntry) {
            $weatherData = $this->extractFutureWeatherData($weatherEntry);
            $rainfall = $weatherData['rain'];
            $weather = $weatherData['weather'];
                
            $timestamp = $weatherEntry['dt'];
            $date = date('Y-m-d', $timestamp);
            $hour = date('H:i:s', $timestamp);

            if (!$rainfall) {
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => 'aman',
                    'weather' => $weather,
                    'elevation' => $elevation,
                    'rainfall' => $rainfall,
                ];
            }else{
                $modelInput = $this->prepareModelInput($rainfall, $elevation);

                $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);
    
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => $prediction['category'], // hanya menampilkan kategori
                    'weather' => $weather,
                    'elevation' => $elevation,
                    'rainfall' => $rainfall,
                ];
            }

        }

        return response()->json($predictions);
    }
    public function getAllPrediction(Request $request)
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
        ]);

        // Panggil API 2 (elevasi)
        $response2 = Http::get('https://api.open-meteo.com/v1/elevation', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        // Ambil data elevasi
        $elevation = $response2['elevation'][0];

        // Iterasi melalui setiap entri dalam response API 1
        $predictions = [];
        foreach ($response1->json()['list'] as $weatherEntry) {
            $weatherData = $this->extractWeatherData($weatherEntry);
            $rainfall = $weatherData['rain'];
            $weather = $weatherData['weather'];
                
            $timestamp = $weatherEntry['dt'];
            $date = date('Y-m-d', $timestamp);
            $hour = date('H:i:s', $timestamp);

            if (!$rainfall) {
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => 'aman',
                    'weather' => $weather,
                    'elevation' => $elevation,
                    'rainfall' => $rainfall,
                ];
            }else{
                $modelInput = $this->prepareModelInput($rainfall, $elevation);

                $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);
    
                $predictions[] = [
                    'date' => $date,
                    'hour' => $hour,
                    'prediction' => $prediction['category'], // hanya menampilkan kategori
                    'weather' => $weather,
                    'elevation' => $elevation,
                    'rainfall' => $rainfall,
                ];
            }
        }

        return response()->json($predictions);
    }

    public function getPrediction(Request $request)
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
            'appid' => $api_key,
            'cnt' => '24',
        ]);
        // Call API 2
        $response2 = Http::get('https://api.open-meteo.com/v1/elevation', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        // dd($response2);

        $weatherData = $this->extractCurrentWeatherData($response1->json());
        $rainfall = $weatherData['rain'];
        $weather = $weatherData['weather'];
        $elevation = $response2['elevation'][0];
       
        if (!$rainfall) {
            return response()->json([
                'prediction' => 'aman',
                'weather' => $weather,
                'elevation' => $elevation,
                'rainfall' => $rainfall,
            ]);
        } else {
            $modelInput = $this->prepareModelInput($rainfall, $elevation);

            $prediction = $this->callMachineLearningModel($ml_model_url, $modelInput);

            return response()->json([
                'prediction' => $prediction['category'],
                'weather' => $weather,
                'elevation' => $elevation,
                'rainfall' => $rainfall,
            ]);
        }


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
            'elevation' => $data2,
        ];
    }

    private function callMachineLearningModel($url, $input)
    {

        $modelResponse = Http::post($url, $input);
        return $modelResponse->json();

    }
}
