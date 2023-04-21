<nav class="w-1/4 mx-4">
    <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">

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
            <a href="{{ route('my-patients') }}">
                <x-ri-user-3-line/>
                My patients
            </a>
        </li>
        <li>
            <a href="{{ route('schedule-timing') }}">
                <x-ri-time-line/>
                Schedule Timing
            </a>
        </li>
        <li>
            <a href="{{ route('payments') }}">
                <x-ri-bank-card-line/>
                Payments
            </a>
        </li>
        <li>
            <a href="{{ route('messages') }}">
                <x-ri-message-3-line/>
                Message
            </a>
        </li>
        <li>
            <a href="{{ route('blog') }}">
                <x-lineawesome-blog-solid/>
                Blog
            </a>
        </li>
        <li>
            <a href="{{ route('settings') }}">
                <x-ri-settings-5-line/>
                Settings
            </a>
        </li>
    </ul>
</nav>

@push('scripts-bottom')
    <script>
        let siteBaseURL = document.location.pathname.split("/")[1]
        if (siteBaseURL === '') {
            $($('#menu-vertical li a')[0]).addClass('active')
        }
        if (siteBaseURL === 'appointments') {
            $($('#menu-vertical li a')[1]).addClass('active')
        }
        if (siteBaseURL === 'my-patients') {
            $($('#menu-vertical li a')[2]).addClass('active')
        }
        if (siteBaseURL === 'schedule-timing') {
            $($('#menu-vertical li a')[3]).addClass('active')
        }
        if (siteBaseURL === 'payments') {
            $($('#menu-vertical li a')[4]).addClass('active')
        }
        if (siteBaseURL === 'messages') {
            $($('#menu-vertical li a')[5]).addClass('active')
        }
        if (siteBaseURL === 'blog') {
            $($('#menu-vertical li a')[6]).addClass('active')
        }
        if (siteBaseURL === 'settings') {
            $($('#menu-vertical li a')[7]).addClass('active')
        }
        console.log(siteBaseURL)
    </script>
@endpush
