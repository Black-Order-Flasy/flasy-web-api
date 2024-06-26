<div class="drawer-side z-20">
    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
    <div class="lg:ms-5 bg-flasy mt-5  rounded-2xl" style="min-height: calc(100vh - 2.5rem)">
        <div class="flex flex-col items-center justify-center">
            <a href="/"><img class="mt-4" src="https://storage.googleapis.com/flasy-bucket/assets/flasyy.png"
                    alt="Description of the image" width="200px" /></a>

        </div>
        <hr class="mt-2 mx-8" style="border-color: white; height: 2px;">
        <ul class="menu p-4 w-64 min-h-full text-blue-950 text-lg">
            @role('Admin')
                <li
                    class="{{ Request::is('dashboard') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/dashboard">
                        <i class="fa-solid fa-gauge"></i>Dashboard
                    </a>
                </li>
                <li
                    class="{{ Request::is('evacuation') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/evacuation">
                        <i class="fa-solid fa-location-dot"></i>Evacuation Point
                    </a>
                </li>
                <li
                    class="{{ Request::is('volunteer') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/volunteer">
                        <i class="fa-solid fa-handshake-angle"></i>Volunteer
                    </a>
                </li>
                <li
                    class="{{ Request::is('profile') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/profile">
                        <i class="fa-solid fa-gear"></i>Setting
                    </a>
                </li>
            @endrole
            @role('Volunteer')
                <li
                    class="{{ Request::is('evacuation') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/evacuation">
                        <i class="fa-solid fa-location-dot"></i>Evacuation Point
                    </a>
                </li>
                <li
                    class="{{ Request::is('profile') ? 'hover:bg-flasyapp2  hover:text-black  rounded-md bg-flasyapp text-xl' : 'hover:bg-flasyapp2  hover:text-black  rounded-md text-xl' }}">
                    <a class="focus:text-black" href="/profile">
                        <i class="fa-solid fa-gear"></i>Setting
                    </a>
                </li>
            @endrole

        </ul>
    </div>
</div>
