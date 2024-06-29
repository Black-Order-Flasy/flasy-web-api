@props(['name', 'label', 'placeholder', 'type'])


<div class="card w-full lg:w-96 h-full lg:h-40 bg-white shadow-md">
    <div class="card-body">

        <div class="flex items-center">
            <h2 class="card-title  justify-start">
                {{ $label }}
            </h2>

        </div>
        <h1 class="text-2xl">
            <input name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
                class="input input-bordered w-full max-w-xs  @error($name) border-red-500  @enderror" />
            @error($name)
                <div class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </h1>
    </div>
</div>
