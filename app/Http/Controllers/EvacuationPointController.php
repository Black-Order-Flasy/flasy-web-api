<?php

namespace App\Http\Controllers;

use App\Services\FirestoreService;
use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;

class EvacuationPointController extends Controller
{
    protected $firestore;

    public function __construct(FirestoreService $firestore)
    {
        $this->firestore = $firestore;
    }

    public function getCollection(Request $request)
    {
        $collectionName = $request->input('collection', 'default-collection-name');

        // Resolve FirestoreService with the specific collection
        $firestoreService = app()->make(FirestoreService::class, ['collection' => $collectionName]);

        $collectionData = $firestoreService->getCollectionData();

        return response()->json($collectionData);
    }
}
