<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MrShan0\PHPFirestore\Fields\FirestoreGeoPoint;
use MrShan0\PHPFirestore\Fields\FirestoreTimestamp;
use MrShan0\PHPFirestore\FirestoreClient;

class EvacuationPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        require 'vendor/autoload.php';
        $projectId = env('FIRESTORE_PROJECT_ID');
        $apiKey = env('FIRESTORE_API_KEY');
        $firestoreClient = new FirestoreClient($projectId, $apiKey, [
            'database' => '(default)',
        ]);

        $collection = 'evacuation_points';

        $dummyData = [
            [
                'district' => 'Tamalanrea',
                'address' => 'Alamat 1',
                'max_people' => 100,
                'name' => 'Nama Titik',
                'latitude' => -5.101569651216837,
                'longitude' => 119.48122270683929,
                'user_id' => 1,
                'supply' => ['makanan', 'obat'],
            ],
            [
                'district' => 'Tamalanrea',
                'address' => 'Alamat 2',
                'max_people' => 150,
                'name' => 'Nama Titik',
                'latitude' => -5.128242188420952,
                'longitude' => 119.48748834687935,
                'user_id' => 2,
                'supply' => ['makanan'],
            ],
            [
                'district' => 'Biringkanaya',
                'address' => 'Alamat 3',
                'max_people' => 200,
                'name' => 'Nama Titik',
                'latitude' => -5.07652186218639,
                'longitude' => 119.49754404069996,
                'user_id' => 3,
               'supply' => ['makanan', 'obat'],
            ],
            [
                'district' => 'Biringkanaya',
                'address' => 'Alamat 4',
                'max_people' => 250,
                'name' => 'Nama Titik',
                'latitude' => -5.076067182318361,
                'longitude' => 119.49851287792939,
                'user_id' => 4,
                'supply' => ['makanan', 'obat'],
            ],
            [
                'district' => 'Biringkanaya',
                'address' => 'Alamat 5',
                'max_people' => 300,
                'name' => 'Nama Titik',
                'latitude' => -5.072231661635277,
                'longitude' => 119.49542412987606,
                'user_id' => 5,
                'supply' => ['obat'],
            ]
        ];

        foreach ($dummyData as $data) {
            $data['created_at'] = new FirestoreTimestamp();
            $data['geopoint'] = new FirestoreGeoPoint($data['latitude'], $data['longitude']);
            $firestoreClient->addDocument($collection, $data);
        }

        echo "Done.";
    }
}
