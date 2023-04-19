<nav class="w-1/4 mx-4">
    <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">

    <ul>
        <li>
            <a class="active" href="{{ route('appointments') }}">
                <x-ri-dashboard-line />
                Overview
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-calendar-line />
                Appointment
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-user-3-line />
                My patients
            </a>
        </li>
        <li>
            <a href="{{ route('schedule-timing') }}">
                <x-ri-time-line />
                Schedule Timing
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-bank-card-line />
                Payments
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-message-3-line />
                Message
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-lineawesome-blog-solid />
                Blog
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-settings-5-line />
                Settings
            </a>
        </li>
    </ul>
</nav>
