<nav id="sidebar" class="w-full md:w-1/4 md:left-0 -left-full absolute md:relative h-full bg-white flex">
    <div class="w-2/4 md:w-full px-2">
        <div class="flex space-x-2">
            <a href="{{ route($route) }}">
                <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">
            </a>
            <button
                id="btn-close-sidebar"
                class="md:hidden"
                onclick="openSidebar()"
            >
                <x-ri-close-line class="h-8"/>
            </button>
        </div>
        {{ $slot }}
    </div>

    <div
        id="overlay"
        class="md:hidden block bg-black bg-opacity-50 w-2/4 h-full"
        onclick="openSidebar()"
    ></div>
</nav>
