<x-app-layout>

    <div class="mt-5 px-5 mx-auto">
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
                            <th>Action</th>
            
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($evacuation as $item)
                            <tr class="hover">
                                <th>{{$loop->iteration}}</th>
                                <td>{{$item['district']}}</td>
                                <td>{{$item['address']}}</td>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['max_people']}}</td>
                                <td>{{$item['latitude']}}</td>
                                <td>{{$item['longitude']}}</td>
                                <td>
                                    <a class="btn btn-xs btn-danger btn-flat text-info" data-toggle="tooltip" title='Edit'>
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <a class="btn btn-xs btn-danger btn-flat text-error" data-toggle="tooltip" title='Delete'>
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
