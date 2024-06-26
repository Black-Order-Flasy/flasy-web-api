<?php

namespace App\Http\Controllers;

use App\Services\FirestoreService;
use Illuminate\Http\Request;

class EvacuationPointController extends Controller
{

    public function index()
    {
        $collectionName = 'evacuation_points';
        $firestoreService = app()->make(FirestoreService::class, ['collection' => $collectionName]);
        $collectionData = $firestoreService->getDocuments();
        
        $user_id = auth()->id();

        if (auth()->user()->hasRole('Volunteer')) {
            $collectionData = array_filter($collectionData, function ($document) use ($user_id) {
                return isset($document['user_id']) && $document['user_id'] === $user_id;
            });
        } 

        return view('evacuation', [
            'evacuation' => $collectionData
        ]);
    }
}
