<nav class="w-1/4 mx-4">
    <a href="{{ route('patient-home') }}">
        <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">
    </a>

    <ul id="menu-vertical">
        <li>
            <a href="{{ route('home') }}">
                <x-ri-dashboard-line/>
                Overview
            </a>
        </li>
        <li>
            <a href="{{ route('appointments') }}">
                <x-ri-calendar-line/>
                Appointment
            </a>
        </li>
        <li>
            <a href="{{ route('schedule-timing') }}">
                <x-ri-time-line/>
                Schedule Timing
            </a>
        </li>
        <li>
            <a href="{{ route('payments.index') }}">
                <x-ri-dashboard-line/>
                Payments
            </a>
        </li>
    </ul>
</nav>

@push('scripts-bottom')
    <script>
        let siteBaseURL = document.location.pathname.split("/")[2]
        if (siteBaseURL === 'admins') {
            $($('#menu-vertical li a')[0]).addClass('active')
        }
        if (siteBaseURL === 'medical') {
            $($('#menu-vertical li a')[1]).addClass('active')
        }
        if (siteBaseURL === 'doctors') {
            $($('#menu-vertical li a')[2]).addClass('active')
        }
        if (siteBaseURL === 'patients') {
            $($('#menu-vertical li a')[3]).addClass('active')
        }
    </script>
@endpush
