<?php

namespace App\Http\Controllers;

use App\Services\CustomFirestoreClient;
use App\Services\FirestoreService;
use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;

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

        // dd($collectionData);
        return view('evacuation.index', [
            'evacuation' => $collectionData
        ]);
    }
    public function create()
    {
        return view('evacuation.create');
    }
    public function store(Request $request)
    {
        // dd(auth()->user()->id);
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'nama' => 'required',
            'nomor_wa' => 'required',
            'kecamatan' => 'required',
            'alamat' => 'required',
            'max' => 'required',
            'supply' => 'required',
        ]);
        // dd('test');
        $data = [
            'district' => $request->kecamatan,
            'address' => $request->alamat,
            'max_people' => $request->max,
            'name' => $request->nama,
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'nomor_wa' => $request->nomor_wa,
            'user_id' => auth()->user()->id,
            'supply' => $request->supply,
        ];
        // dd($data);

        $collectionName = 'evacuation_points';
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $firestoreClient = new FirestoreClient($projectId, $apiKey, [
            'database' => 'flasy-app-database',
        ]);

        $firestoreClient->addDocument($collectionName, $data);
        
        return redirect('evacuation');
    }
    public function edit($id)
    {
        $firestoreClient = new CustomFirestoreClient();
        $documents = $firestoreClient->listDocuments('evacuation_points/'.$id,[]);
        // dd($documents);

        return view('evacuation.edit', [
            'evacuation' => $documents,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        // dd(auth()->user()->id);
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'nama' => 'required',
            'nomor_wa' => 'required',
            'kecamatan' => 'required',
            'alamat' => 'required',
            'max' => 'required',
            'supply' => 'required',
        ]);
        // dd('test');
        $data = [
            'district' => $request->kecamatan,
            'address' => $request->alamat,
            'max_people' => $request->max,
            'name' => $request->nama,
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'nomor_wa' => $request->nomor_wa,
            'user_id' => auth()->user()->id,
            'supply' => $request->supply,
        ];
        // dd($data);

        $collectionName = 'evacuation_points';
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');

        $firestoreClient = new FirestoreClient($projectId, $apiKey, [
            'database' => 'flasy-app-database',
        ]);
        $firestoreClient->deleteDocument('evacuation_points/'.$id);
        $firestoreClient->addDocument($collectionName, $data);
        
        return redirect('evacuation');
    }
    
    public function delete( $id)
    {

        $collectionName = 'evacuation_points';
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');

        $firestoreClient = new FirestoreClient($projectId, $apiKey, [
            'database' => 'flasy-app-database',
        ]);

        $firestoreClient->deleteDocument('evacuation_points/'.$id);
        return redirect('evacuation');
    }
}
