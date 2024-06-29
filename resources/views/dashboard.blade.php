<x-app-layout>

    <div class="flex justify-start flex-col lg:flex-row lg:flex-wrap lg:items-stretch gap-4 lg:ps-5">

        <x-card :title="'Verified User'" :count="'5'" :color="'text-success'" class="mt-2" />
        <x-card :title="'Unverified User'" :count="'3'" :color="'text-error'" class="mt-2" />
        <x-card :title="'Evacuation Point'" :count="'8'" :color="'text-primary'" class="mt-2" />




    </div>
    <div class="w-96 ms-5 mt-5 ">
    <div class="card w-full bg-white  text-black mb-4 shadow-md">
        <div class="card-body">
                <table class="table ">
    
                    <thead>
                        <tr>
                            <th></th>
                            <th>Kecamatan</th>
                            <th>Total</th>
            
                        </tr>
                    </thead>
                    <tbody>
            
                        <tr class="hover">
                            <th>1</th>
                            <td>Biringkanaya</td>
                            <td>5</td>

                        </tr>
                        <tr class="hover">
                            <th>2</th>
                            <td>Manggala</td>
                            <td>3</td>

                        </tr>
                        <tr class="hover">
                            <th>3</th>
                            <td>Tamalate</td>
                            <td>2</td>

                        </tr>
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-app-layout>
