<x-app-layout>
    <form method="POST" action="{{ secure_url(route('evacuation.store')) }}">
        @csrf
        <div class="my-5 px-5">
            <div class="flex justify-self-center flex-col lg:flex-row lg:flex-wrap lg:items-stretch gap-4 lg:ps-5 ">
                <h2 className="card-title text-3xl items-center sm:text-4xl font-bold mb-5">
                    Buat Laporan Titik Evakuasi
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
                <input name="lat" id="lat" hidden />
                <input name="long" id="long" hidden />

                <x-card-input-text type="text" :name="'nama'" label="Nama Tempat"
                    placeholder="masukkan nama tempat" />

                <x-card-input-select selected="" label="Kecamatan" :name="'kecamatan'" placeholder="Pilih kecamatan"
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
                    placeholder="masukkan nomor whatsapp" />
                <x-card-input-text type="text" :name="'alamat'" label="Alamat lengkap"
                    placeholder="masukkan alamat lengkap" />
                <x-card-input-text type="number" :name="'max'" label="Kapasitas tampungan"
                    placeholder="masukkan kapasitas tampungan" />

                <div class="card w-full lg:w-96 bg-white shadow-md">
                    <div class="card-body">

                        <div class="flex items-center">
                            <h2 class="card-title  justify-start">
                                Supply
                            </h2>
                        </div>
                        <div class="form-control @error('supply') border-red-500 text-red-500 @enderror">
                            <label class="label cursor-pointer">
                                <span class="label-text">Obat - obatan</span>
                                <input type="checkbox" name="supply[]" value="Obat - obatan" class="checkbox" />
                            </label>
                            <label class="label cursor-pointer">
                                <span class="label-text">Makanan</span>
                                <input type="checkbox" name="supply[]" value="Makanan" class="checkbox" />
                            </label>
                            <label class="label cursor-pointer">
                                <span class="label-text">Pakaian</span>
                                <input type="checkbox" name="supply[]" value="Pakaian" class="checkbox" />
                            </label>
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
            // Initialize the map
            var map = L.map('map').setView([-5.161957667574752, 119.43579743191574],
                13); // Center the map at Jakarta, for example

            // Add the MapTiler tile layer
            L.tileLayer(`https://api.maptiler.com/maps/basic/256/{z}/{x}/{y}.png?key=${key}`, {
                attribution: '',
                maxZoom: 18,
            }).addTo(map);

            // Add search control using Nominatim
            L.Control.geocoder({
                collapsed: true,
                geocoder: L.Control.Geocoder.nominatim()
            }).addTo(map);

            var marker;
            // Add click event to map
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                // Update coordinates display
                document.getElementById('lat').value = lat;
                document.getElementById('long').value = lng;
                document.getElementById('coordinates').innerHTML = 'Latitude: ' + lat + ', Longitude: ' +
                    lng;

                // Perform reverse geocoding to get the address
                fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`
                    )
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        // Update coordinates display with address
                        document.getElementById('coordinates').innerHTML = 'Latitude: ' + lat +
                            ', Longitude: ' + lng + '<br>Address: ' + address;
                    })
                    .catch(error => console.log('Error:', error));

                // If a marker exists, remove it
                if (marker) {
                    map.removeLayer(marker);
                }

                // Add a marker to the clicked location
                marker = L.marker([lat, lng]).addTo(map);
            });
        });
    </script>


</x-app-layout>
