@props(['name', 'label', 'placeholder', 'selected', 'options' => []])


<div class="card w-full lg:w-96 bg-white shadow-md">
    <div class="card-body">

        <div class="flex items-center">
            <h2 class="card-title  justify-start">
                {{ $label }}
            </h2>

        </div>
        <h1 class="text-2xl">
            <select name="{{$name}}" class="select select-bordered w-full max-w-xs @error($name) border-red-500 text-red-500 @enderror">
                <option disabled selected>{{ $placeholder }}</option>
                @foreach ($options as $option => $text)
                    <option value="{{ $option }}" {{ $selected && $selected == $option ? 'selected' : '' }}>{{ $text }}</option>
                @endforeach
            </select>
            @error($name)
                <div class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </h1>
    </div>
</div>
