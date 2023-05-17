@if($errors->any())
    <div class="mb-4">
        <strong>Validation errors:</strong>
        <ul class="mt-3 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li><span class="text-red-500 text-xs italic">{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('auth_message'))
    <div class="text-green-500">
        {{ session('auth_message') }}
    </div>
@endif
