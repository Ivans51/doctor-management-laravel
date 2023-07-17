<nav class="w-1/4 mx-4">
    <a href="{{ route('patient.home') }}">
        <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">
    </a>

    <ul id="menu-vertical">
        <li>
            <a href="{{ route('patient.home') }}">
                <x-ri-dashboard-line/>
                Overview
            </a>
        </li>
        <li>
            <a href="{{ route('patient.appointments') }}">
                <x-ri-calendar-line/>
                Appointment
            </a>
        </li>
        <li>
            <a href="{{ route('patient.schedule.timing') }}">
                <x-ri-time-line/>
                Schedule Timing
            </a>
        </li>
        <li>
            <a href="{{ route('patient.messages') }}">
                <x-ri-time-line/>
                Message
            </a>
        </li>
        <li>
            <a href="{{ route('patient.payments') }}">
                <x-ri-dashboard-line/>
                Payments
            </a>
        </li>
    </ul>
</nav>

@push('scripts-bottom')
    <script>
        let siteBaseURL = document.location.pathname.split("/")[2]
        if (siteBaseURL === 'patient') {
            $($('#menu-vertical li a')[0]).addClass('active')
        }
        if (siteBaseURL === 'appointments') {
            $($('#menu-vertical li a')[1]).addClass('active')
        }
        if (siteBaseURL === 'schedule') {
            $($('#menu-vertical li a')[2]).addClass('active')
        }
        if (siteBaseURL === 'messages') {
            $($('#menu-vertical li a')[3]).addClass('active')
        }
        if (siteBaseURL === 'payments') {
            $($('#menu-vertical li a')[4]).addClass('active')
        }
    </script>
@endpush
