<x-utils.menu-component
    route="admin.home"
>
    <ul id="menu-vertical">
        <li>
            <a href="{{ route('admin.admins.index') }}">
                <x-ri-dashboard-line/>
                Admins
            </a>
        </li>
        <li>
            <a href="{{ route('admin.medical.index') }}">
                <x-ri-dashboard-line/>
                Medical Specialty
            </a>
        </li>
        <li>
            <a href="{{ route('admin.doctors.index') }}">
                <x-ri-dashboard-line/>
                Doctors
            </a>
        </li>
        <li>
            <a href="{{ route('admin.patients.index') }}">
                <x-ri-dashboard-line/>
                Patients
            </a>
        </li>
        <li>
            <a href="{{ route('admin.payments.index') }}">
                <x-ri-dashboard-line/>
                Payments
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings') }}">
                <x-ri-settings-5-line/>
                Settings
            </a>
        </li>
    </ul>
</x-utils.menu-component>

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
        if (siteBaseURL === 'payments') {
            $($('#menu-vertical li a')[4]).addClass('active')
        }
        if (siteBaseURL === 'settings') {
            $($('#menu-vertical li a')[5]).addClass('active')
        }
    </script>
@endpush
