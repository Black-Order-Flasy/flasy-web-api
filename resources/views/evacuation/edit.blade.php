<x-app-layout>
    <form method="POST" action="{{ route('evacuation.update', $evacuation['id']) }}">
        @csrf
        @method('PUT') <!-- Method spoofing to allow PUT request -->
        <div class="my-5 px-5">
            <div class="flex justify-self-center flex-col lg:flex-row lg:flex-wrap lg:items-stretch gap-4 lg:ps-5 ">
                <h2 class="card-title text-3xl items-center sm:text-4xl font-bold mb-5">
                    Edit Laporan Titik Evakuasi
                </h2>
                <div class="card w-full bg-white text-black mb-4 shadow-md">
                    <div class="card-body">
                        <h2 class="card-title justify-start">
                            Titik Lokasi
                        </h2>
                        <div id="map" class="overflow-x-auto z-0 lg:h-72 h-96"></div>
                        <div id="coordinates" class="mt-3 text-gray-700">Klik pada peta untuk mendapatkan koordinat.
                        </div>
                    </div>
                </div>
                <input name="lat" id="lat" value="{{ $evacuation['latitude'] }}" hidden />
                <input name="long" id="long" value="{{ $evacuation['longitude'] }}" hidden />

                <x-card-input-text type="text" :name="'nama'" label="Nama Tempat"
                    placeholder="masukkan nama tempat" value="{{ $evacuation['name'] }}" />

                <x-card-input-select  selected="{{ $evacuation['district'] }}" label="Kecamatan" :name="'kecamatan'" placeholder="Pilih kecamatan"
                    :options="[
                        'Biringkanaya' => 'Biringkanaya',
                        'Bontoala' => 'Bontoala',
                        'Kepulauan Sangkarrang' => 'Kepulauan Sangkarrang',
                        'Makassar' => 'Makassar',
                        'Mamajang' => 'Mamajang',
                        'Manggala' => 'Manggala',
                        'Mariso' => 'Mariso',
                        'Panakkukang' => 'Panakkukang',
                        'Rappocini' => 'Rappocini',
                        'Tallo' => 'Tallo',
                        'Tamalanrea' => 'Tamalanrea',
                        'Tamalate' => 'Tamalate',
                        'Ujung Pandang' => 'Ujung Pandang',
                        'Ujung Tanah' => 'Ujung Tanah',
                        'Wajo' => 'Wajo',
                    ]" />

                <x-card-input-text type="text" :name="'nomor_wa'" label="Nomor whatsapp"
                    placeholder="masukkan nomor whatsapp" value="{{ $evacuation['nomor_wa'] }}" />
                <x-card-input-text type="text" :name="'alamat'" label="Alamat lengkap"
                    placeholder="masukkan alamat lengkap" value="{{ $evacuation['address'] }}" />
                <x-card-input-text type="number" :name="'max'" label="Kapasitas tampungan"
                    placeholder="masukkan kapasitas tampungan" value="{{ $evacuation['max_people'] }}" />

                <div class="card w-full lg:w-96 bg-white shadow-md">
                    <div class="card-body">
                        <div class="flex items-center">
                            <h2 class="card-title  justify-start">
                                Supply
                            </h2>
                        </div>
                        <div class="form-control @error('supply') border-red-500 text-red-500 @enderror">
                            @foreach(['Obat - obatan', 'Makanan', 'Pakaian'] as $supply)
                                <label class="label cursor-pointer">
                                    <span class="label-text">{{ $supply }}</span>
                                    <input type="checkbox" name="supply[]" value="{{ $supply }}" 
                                    class="checkbox" {{ in_array($supply, $evacuation['supply']) ? 'checked' : '' }} />
                                </label>
                            @endforeach
                            @error('supply')
                                <div class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <script>
        const key = 'rZ9ABto02JScbcQcTQMs';
        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ $evacuation['latitude'] }};
            var lng = {{ $evacuation['longitude'] }};
            var map = L.map('map').setView([lat, lng], 13);

            L.tileLayer(`https://api.maptiler.com/maps/basic/256/{z}/{x}/{y}.png?key=${key}`, {
                attribution: '',
                maxZoom: 18,
            }).addTo(map);

            L.Control.geocoder({
                collapsed: true,
                geocoder: L.Control.Geocoder.nominatim()
            }).addTo(map);

            var marker = L.marker([lat, lng]).addTo(map);

            function updateCoordinates(lat, lng) {
                document.getElementById('lat').value = lat;
                document.getElementById('long').value = lng;
                document.getElementById('coordinates').innerHTML = 'Latitude: ' + lat + ', Longitude: ' + lng;

                fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
                    )
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        document.getElementById('coordinates').innerHTML = 'Latitude: ' + lat + ', Longitude: ' +
                            lng + '<br>Address: ' + address;
                    })
                    .catch(error => console.log('Error:', error));

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
            }

            // Update coordinates on map load
            updateCoordinates(lat, lng);

            // Update coordinates on map click
            map.on('click', function(e) {
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
</x-app-layout>
