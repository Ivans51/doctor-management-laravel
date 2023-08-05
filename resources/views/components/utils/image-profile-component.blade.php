@if(isset($item) && isset($item->photo))
    <img
        class="h-10 mr-3"
        src="{{asset('storage/'.$item->photo)}}"
        alt="profile patient"
        style="border-radius: 50%"
    >
@else
    <img
        class="h-10 mr-3"
        src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
        alt="profile patient"
        style="border-radius: 50%"
    >
@endif
