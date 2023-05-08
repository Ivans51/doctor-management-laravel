<section class="w-full md:w-2/5 text-center bg-white rounded p-6 flex flex-col justify-center items-center">
    <div class="flex flex-col items-center">
        <img
            class="mb-2 w-24 h-24"
            src="{{ Vite::asset('resources/img/home/logo.png') }}"
            alt="patient profile image"
            style="border-radius: 50%"
        >
        <p class="font-bold">Dr. Stephen Colin</p>
        <p class="text-zinc-400">Cardiologist</p>
    </div>

    <button
        class="rounded text-white bg-violet-500 px-4 py-1 text-sm flex items-center flex-row my-8"
    >
        <x-ri-edit-2-fill class="w-6 h-6 mr-2"/>
        <span>Edit Profile</span>
    </button>

    <div class="space-y-2">
        <p>146 Rates</p>
        <div class="flex space-x-2">
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-half-fill class="w-4 h-4 text-yellow-400"/>
            <x-ri-star-line class="w-4 h-4 text-yellow-400"/>
        </div>
    </div>
</section>
