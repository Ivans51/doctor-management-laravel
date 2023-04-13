<div class="bg-primary h-screen w-3/4">
    <header class="flex justify-between px-4 py-2 items-center">
        <div>
            <label>
                <input class="bg-primary" type="search" placeholder="Search Appointment, Patient or etc">
            </label>
        </div>
        <div class="flex items-center h-8">
            <div class="relative">
                <x-ri-notification-2-line id="btn-notification" class="h-8 mr-4 cursor-pointer"/>
                <div
                    id="content-notification"
                    class="bg-white absolute border rounded-lg py-2 px-4 mt-3 hidden right-0"
                >
                    <ul class="space-y-2 btn-link">
                        <li><a href="/#" class="whitespace-nowrap">My Profile</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Change Password</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Notifications</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Reviews</a></li>
                    </ul>
                </div>
            </div>
            <div class="relative">
                <div id="btn-user" class="cursor-pointer flex">
                    <img
                        class="h-8"
                        src="{{ Vite::asset('resources/img/home/icons8-profile-face-64.png') }}"
                        alt="user"
                    >
                    <div class="flex ml-2">
                        <div class="flex flex-col text-xs">
                            <span><strong>Stephen Conley</strong></span>
                            <span>Cardiologist</span>
                        </div>
                        <x-ri-arrow-drop-down-fill class="h-8 inline-block"/>
                    </div>
                </div>
                <div id="content-user" class="bg-white absolute border rounded-lg py-2 px-4 mt-3 hidden right-0">
                    <ul class="space-y-2 btn-link">
                        <li><a href="/#" class="whitespace-nowrap">My Profile</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Change Password</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Notifications</a></li>
                        <li><a href="/#" class="whitespace-nowrap">Reviews</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="p-6">
        @yield('content')
    </main>
</div>
