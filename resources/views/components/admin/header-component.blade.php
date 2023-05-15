<nav class="w-1/4 mx-4">
    <img class="mt-4 mb-8 mx-2" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="logo">

    <ul id="menu-vertical">
        <li>
            <a href="{{ route('admin-admins') }}">
                <x-ri-dashboard-line/>
                Admins
            </a>
        </li>
        <li>
            <a href="{{ route('admin-doctors') }}">
                <x-ri-dashboard-line/>
                Doctors
            </a>
        </li>
        <li>
            <a href="{{ route('admin-patients') }}">
                <x-ri-dashboard-line/>
                Patients
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
        if (siteBaseURL === 'admins') {
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
