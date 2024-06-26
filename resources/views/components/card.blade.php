@props(['title', 'count', 'color'])


<div class="card w-80 lg:w-72 bg-white shadow-md">
    <div class="card-body">

        <div class="flex items-center">
            <span class="text-4xl text-slate-700">

            </span>
            <h2 class="card-title ml-auto justify-end font-light {{ $color }}">
                {{ $title }}
            </h2>

        </div>
        <h1 class="text-end text-2xl font-bold {{ $color }}">
            {{ $count }}
        </h1>
    </div>
</div>
