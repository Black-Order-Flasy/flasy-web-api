@props(['name', 'label', 'placeholder', 'type'])


<div class="card w-full lg:w-96 bg-white shadow-md">
    <div class="card-body">

        <div class="flex items-center">
            <h2 class="card-title  justify-start">
                {{ $label }}
            </h2>

        </div>
        <h1 class="text-2xl">
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">Remember me</span>
                    <input type="checkbox" checked="checked" class="checkbox" />

                </label>
            </div>
        </h1>

    </div>
</div>
