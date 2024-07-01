<?php

namespace App\Services;

use MrShan0\PHPFirestore\FirestoreClient;

use FirestoreDocument;
use NotFound;
use FirestoreHelper;

class CustomFirestoreClient
{
    public function listDocuments($collection, array $parameters = [], array $options = [])
    {
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');

        $client = new FirestoreClient($projectId, $apiKey, [
            'database' => 'flasy-app-database',
        ]);

        $response = $client->request('GET', 'documents/' . \MrShan0\PHPFirestore\Helpers\FirestoreHelper::normalizeCollection($collection), $options, $parameters);
        // dd($response);
        $parsedData = $this->parseFirestoreDocument($response);

        return array_merge($parsedData);
    }

    function parseFirestoreDocument($document)
    {
        $fields = $document['fields'] ?? [];

        $documentId = substr($document['name'], strrpos($document['name'], '/') + 1);
            
        $parsedData = [
            'id' => $documentId ?? 'N/A',
            'name' => $fields['name']['stringValue'] ?? 'N/A',
            'district' => $fields['district']['stringValue'] ?? 'N/A',
            'user_id' => $fields['user_id']['integerValue'] ?? 'N/A',
            'longitude' => $fields['longitude']['stringValue'] ?? 'N/A',
            'latitude' => $fields['latitude']['stringValue'] ?? 'N/A',
            'max_people' => $fields['max_people']['stringValue'] ?? 'N/A',
            'nomor_wa' => $fields['nomor_wa']['stringValue'] ?? 'N/A',
            'address' => $fields['address']['stringValue'] ?? 'N/A',
            'supply' => []
        ];

        // Parse the supply values if they exist
        if (isset($fields['supply']['arrayValue']['values'])) {
            $parsedData['supply'] = array_map(function ($item) {
                return $item['stringValue'] ?? 'N/A';
            }, $fields['supply']['arrayValue']['values']);
        }

        return $parsedData;
    }
}
