<?php

namespace App\Services;
use MrShan0\PHPFirestore\FirestoreClient;



class FirestoreService
{
    protected $db;
    protected $name;

    public function __construct($collection)
    {
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $this->db = new FirestoreClient($projectId, $apiKey, [
            'database' => 'flasy-app-database',
        ]);
        $this->name = $collection;
    }

    public function addDocument($data)
    {
        $this->db->collection($this->name)->add($data);
    }

    public function getDocuments()
    {
        $collections = $this->db->listDocuments($this->name, [

        ]);
        // dd($collections);

        $documents = $collections['documents'];
        
        $data = [];
        
        foreach ($documents as $document) {
            $documentData = $document->toArray();
            $documentName = $document->getName();
            
            // Extract the document ID from the document name
            $documentId = substr($documentName, strrpos($documentName, '/') + 1);
            
            // Add the document ID to the document data
            $documentData['id'] = $documentId;
            
            $data[] = $documentData;
        }
        
        // dd($data);die;

        return $data;
    
    }

    public function getDocument($id)
    {
        $document = $this->db->collection($this->name)->document($id)->snapshot();
        if ($document->exists()) {
            return $document->data();
        } else {
            return null;
        }
    }

    public function updateDocument($id, $data)
    {
        $this->db->collection($this->name)->document($id)->set($data);
    }

    public function deleteDocument($id)
    {
        $this->db->collection($this->name)->document($id)->delete();
    }

    private function parseFirestoreValue($value)
    {
        switch (key($value)) {
            case 'stringValue':
                return $value['stringValue'];
            case 'integerValue':
                return (int) $value['integerValue'];
            case 'doubleValue':
                return (float) $value['doubleValue'];
            case 'timestampValue':
                return $value['timestampValue'];
            case 'arrayValue':
                return array_map([$this, 'parseFirestoreValue'], $value['arrayValue']['values']);
            // Add more cases as needed
            default:
                return null;
        }
    }
}

