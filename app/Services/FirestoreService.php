<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirestoreService
{
    protected $db;
    protected $name;

    public function __construct()
    {
        $this->db = new FirestoreClient([
            'keyFilePath' => env('FIREBASE_CREDENTIALS'),
        ]);
        $this->name = 'collection-name'; // Change this to your collection name
    }

    public function addDocument($data)
    {
        $this->db->collection($this->name)->add($data);
    }

    public function getDocuments()
    {
        $documents = $this->db->collection($this->name)->documents();
        $data = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data[] = $document->data();
            }
        }
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
}
