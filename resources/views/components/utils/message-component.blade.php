@if($errors->any())
    <div class="bg-white border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong>Validation errors:</strong>
        <ul class="mt-3 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li><span class="text-red-500 text-xs italic">{{ $error }}</span></li>
            @endforeach
        </ul>
        <span
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
            onclick="this.parentElement.style.display='none';"
        >
            <svg
                class="fill-current h-6 w-6 text-red-500"
                role="button"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <title>Close</title>
                <path
                    d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.357 5.652a.5.5 0 0 0-.707.707L9.293 10l-3.643 3.643a.5.5 0 0 0 .708.707L10 10.707l3.643 3.643a.5.5 0 0 0 .707-.707L10.707 10l3.641-3.648a.5.5 0 0 0 0-.707z"/></svg>
        </span>
    </div>
@endif

@if(session('auth_message'))
    <div class="bg-white border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block">{{ session('auth_message') }}</span>
        <span
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
            onclick="this.parentElement.style.display='none';"
        >
            <svg
                class="fill-current h-6 w-6 text-green-500"
                role="button"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <title>Close</title>
                <path
                    d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.357 5.652a.5.5 0 0 0-.707.707L9.293 10l-3.643 3.643a.5.5 0 0 0 .708.707L10 10.707l3.643 3.643a.5.5 0 0 0 .707-.707L10.707 10l3.641-3.648a.5.5 0 0 0 0-.707z"/></svg>
        </span>
    </div>
@endif

@if(session('success'))
    <div class="bg-white border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block">{{ session('success') }}</span>
        <span
            class="absolute top-0 bottom-0 right-0 px-4 py-3"
            onclick="this.parentElement.style.display='none';"
        >
            <svg
                class="fill-current h-6 w-6 text-green-500"
                role="button"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <title>Close</title>
                <path
                    d="M14.348 5.652a.5.5 0 0 0-.707 0L10 9.293 6.357 5.652a.5.5 0 0 0-.707.707L9.293 10l-3.643 3.643a.5.5 0 0 0 .708.707L10 10.707l3.643 3.643a.5.5 0 0 0 .707-.707L10.707 10l3.641-3.648a.5.5 0 0 0 0-.707z"/></svg>
        </span>
    </div>
@endif

<script>

</script>
