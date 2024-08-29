@include('includes.session')
@if (count($data) == 0)
    <div class="w-full flex flex-col items-start justify-center">
        <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
        <h3 class="text-gray-600 mx-auto text-2xl m-auto">You have zero notifications. 🙃</h3>
    </div>
@endif
<div class="w-fullflex flex-col items-start">
    <h3 class="text-4xl text-gray-900 font-bold ml-2 mb-3">Notifications</h3>
    @foreach ($data as $notif)
        <a class="w-full flex flex-row items-center hover:bg-gray-100 py-2" href="{{ $notif->href }}">
            <div class="w-12 h-12 rounded-full m-2 bg-cover bg-no-repeat bg-center mr-2 relative" style="background-image: url('{{ asset($notif->image) }}')">
                @if ($notif->status == 'unread')
                    <div class="absolute bg-blue-500 h-4 w-4 rounded-full bottom-0 right-0"></div>
                @endif
            </div>
            <h3 class="text-2xl text-gray-900 text-ellipsis overflow-hidden whitespace-nowrap" style="width: calc(100% - 54px)">{{ $notif->text }}</h3>
        </a>
    @endforeach
</div>
