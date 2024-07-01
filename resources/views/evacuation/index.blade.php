<x-app-layout>

    <div class="mt-5 px-5 mx-auto">
        <a class="btn bg-flasy text-dark mb-5" href="/evacuation/create" data-toggle="tooltip" title='Edit'>
            <i class="fa-solid fa-plus"></i> Create
        </a>
    <div class="card w-full bg-white  text-black mb-4 shadow-md">

        <div class="card-body">
       
            <div class="overflow-x-auto">
                <table class="table">
    
                    <thead>
                        <tr>
                            <th></th>
                            <th>District</th>
                            <th>Address</th>
                            <th>Name</th>
                            <th>Max People</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Contact</th>
                            <th>Action</th>
            
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($evacuation) --}}
                            @foreach ($evacuation as $item)
                            <tr class="hover">
                                <th>{{$loop->iteration}}</th>
                                <td>{{$item['district']}}</td>
                                <td>{{$item['address']}}</td>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['max_people']}}</td>
                                <td>{{$item['latitude']}}</td>
                                <td>{{$item['longitude']}}</td>
                                <td>{{$item['nomor_wa'] ?? ''}}</td>
                                <td class="text-center align-middle">
                                    @foreach ($item['supply'] as $supply)
                                    <span class="w-auto h-auto badge text-xs lg:text-md "> {{$supply}}</span>
                                       
                                    @endforeach

                                </td>
                                <td>
                                    <a href="{{route('evacuation.edit' , $item['id'])}}" class="btn btn-xs btn-flat text-info w-auto h-auto" data-toggle="tooltip" title='Delete'>
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <a href="{{route('evacuation.delete' , $item['id'])}}" class="btn btn-xs btn-flat text-error w-auto h-auto" data-toggle="tooltip" title='Delete'>
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                          
                            @endforeach
                        
                    </tbody>
                </table>
            </div>
            </div>

        </div>
    </div>

</x-app-layout>
