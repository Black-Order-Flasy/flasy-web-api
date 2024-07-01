<x-app-layout>
    <div class="mt-5 px-5 mx-auto">

        <div class="flex justify-self-center flex-col lg:flex-row lg:flex-wrap lg:items-stretch gap-4 lg:ps-5 ">

            <x-card :title="'Verified User'" :count="$verified" :color="'text-success'" class="mt-2" />
            <x-card :title="'Unverified User'" :count="$unverified" :color="'text-error'" class="mt-2" />
            <x-card :title="'Evacuation Point'" :count="$total_evacuation" :color="'text-primary'" class="mt-2" />

            <div class="card w-full lg:w-96  bg-white  text-black mb-4 shadow-md">

                <div class="card-body">
                    <h2 class="card-title justify-center font-light text-flasyapp2">
                        Evacuation Count by District
                    </h2>
                    <canvas id="districtChart"></canvas>
                    {{-- <div class="overflow-x-auto">
                        <table class="table ">
    
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Kecamatan</th>
                                    <th>Total</th>
    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($district as $item)
                                    <tr class="hover">
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $item['district'] }}</td>
                                        <td>{{ $item['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>


            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('districtChart').getContext('2d');

            // Data yang kamu hasilkan sebelumnya
            const data = @json($district);

            // Mengambil nama distrik dan totalnya
            const labels = data.map(item => item.district);
            const totals = data.map(item => item.total);

            const districtChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Documents per District',
                        data: totals,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</x-app-layout>
