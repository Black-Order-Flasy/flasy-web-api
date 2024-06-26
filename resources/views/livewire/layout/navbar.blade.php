<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<header id="header" class="sticky z-50 py-2 rounded-xl mt-6  lg:px-5 top-6 mb-2">
    <div class="lg:container">
        <nav class="navbar ">
            <label for="my-drawer-2" class="btn btn-ghost text-xs lg:hidden bg-transparent  "><svg
                    class="swap-off fill-current" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                    viewBox="0 0 512 512">
                    <path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z">
                    </path>
                </svg></label>
            <div class="flex-1">
                <div class="text-xs breadcrumbs ">
                    <ul class="flex flex-wrap">
                        <li>
                            <a href="/">
                                <i class="fa-solid fa-house"></i> Home
                            </a>
                        </li>
                        @php
                            $routeName = explode('.', Route::currentRouteName())[0];
                        @endphp
                        <li><a href="/{{ $routeName }}">{{ $routeName }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex-none">
                <p class="text-xs lg:text-md">{{ Auth::user()->name }}</p>

                <button wire:click="logout" class="ms-5 text-xs lg:text-md hover:underline">
                    Logout
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>

            </div>
        </nav>
    </div>
    <script>
        window.addEventListener('scroll', function() {
            var header = document.getElementById('header');
            if (window.scrollY > 0) {
                header.classList.add('glassmor');
                header.classList.add('top-0');

            } else {
                header.classList.remove('glassmor');
                header.classList.remove('top-0');

            }
        });
    </script>
</header>
