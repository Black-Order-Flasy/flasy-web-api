<x-app-layout>
    {{-- @dd($user); --}}
    <div class="mt-5 px-5 mx-auto">
        <div class="card w-full bg-white  text-black mb-4 shadow-md">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table">

                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr class="hover">
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->verified ? 'Yes' : 'No' }}</td>
                                    <td>
                                        @if (!$item->verified)
                                            <x-verify :item="$item->id" />
                                        @endif

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
