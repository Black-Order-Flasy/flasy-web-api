<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirestoreService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $verified = User::where('verified', 1)->count();
        $unverified = User::where('verified', 0)->where('name', '!=', 'Admin')->count();
        $firestoreService = app()->make(FirestoreService::class, ['collection' => 'evacuation_points']);
        $collectionData = $firestoreService->getDocuments();
        $districtCounts = array_reduce($collectionData, function ($result, $document) {
            if (isset($document['district'])) {
                $district = $document['district'];
                if (!isset($result[$district])) {
                    $result[$district] = 0;
                }
                $result[$district]++;
            }
            return $result;
        }, []);
        
        $indexedDistrictCounts = [];
        foreach ($districtCounts as $district => $total) {
            $indexedDistrictCounts[] = [
                'district' => $district,
                'total' => $total
            ];
        }
        // dd($indexedDistrictCounts);
        // dd(count($collectionData));
        return view('dashboard', [
            'verified' => $verified,
            'unverified' => $unverified,
            'total_evacuation' => count($collectionData),
            'district' => $indexedDistrictCounts,
        ]);
    }
}
