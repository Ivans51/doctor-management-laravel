<!-- Modal -->
<div class="{{ $modalClass }} fixed w-full h-full top-0 left-0 flex items-center justify-center hidden">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

        <!-- Modal content -->
        <div class="modal-content py-4 text-left px-6">
            <div class="flex justify-between items-center pb-3">
                <p class="text-2xl font-bold">{{ $title }}</p>
                <div class="modal-close cursor-pointer z-50">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                         height="18" viewBox="0 0 18 18">
                        <path
                            d="M18 1.5L16.5 0 9 7.5 1.5 0 0 1.5 7.5 9 0 16.5 1.5 18 9 10.5 16.5 18 18 16.5 10.5 9z"/>
                    </svg>
                </div>
            </div>
            {{ $content }}
        </div>
    </div>
</div>
