<nav id="sidebar" class="z-50 md:relative bg-white h-full w-64 col-start-1 row-start-1 transition-all duration-300
    absolute -left-full md:left-0">
    <div class="w-64 px-2">
        <div class="flex justify-between items-center">
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
        <div class="truncate break-words max-w-full">
            {{ $slot }}
        </div>
    </div>
</nav>

<div
    id="overlay"
    class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
    onclick="openSidebar()"
    style="display: none;"
></div>
