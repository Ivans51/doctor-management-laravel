<section class="w-full md:w-2/5 text-center bg-white rounded p-6 flex flex-col justify-center items-center">
    <div class="flex flex-col items-center">
        @if($user->profile == null)
            <img
                class="h-30 mr-3"
                src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                alt="profile patient"
                style="border-radius: 50%"
            >
        @else
            <img
                class="h-30 mr-3"
                src="{{asset('storage/'.$user->profile)}}"
                alt="profile patient"
                style="border-radius: 50%"
            >
        @endif
        {{ $user->name }}
        <p class="font-bold">Dr. {{ $user->name }}</p>
        <p class="text-zinc-400">Not found</p>
    </div>

    <a
        href="{{ route('doctor.settings') }}"
        class="rounded text-white bg-violet-500 px-4 py-1 text-sm flex items-center flex-row my-8"
    >
        <x-ri-edit-2-fill class="w-6 h-6 mr-2"/>
        <span>Edit Profile</span>
    </a>

    <div class="space-y-2">
        <p>1 Rate(s)</p>
        <div class="flex space-x-2">
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-half-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-line class="w-4 h-4 text-yellow-400"/>
        </div>
    </div>
</section>
