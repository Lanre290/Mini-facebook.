@foreach ($data as $user)
    <div class="flex flex-col rounded-lg cursor-pointer h-2/5 w-full md:w-1/4 sm:w-2/5 lg:w-1/5 bg-gray-300 m-2">
        <a href="/profile/{{ $user->id }}" class="h-3/5 w-full rounded-tl-lg rounded-tr-lg bg-gray-400 bg-cover bg-no-repeat bg-center" style="background-image: url('{{ asset($user->image_path) }}')"></a>
        <div class="flex flex-col justify-center items-center w-full h-2/5">
            <a href="/profile/{{ $user->id }}" class="w-full">
                <h3 class="text-2xl text-gray-600 text-center m-auto overflow-hidden w-5/6 text-ellipsis" style='white-space: nowrap;'> {{$user->name}} </h3>
                <h3 class="mb-2 m-auto text-center text-gray-600">{{ $user->followers }} Followers</h3>
            </a>
            <div class="flex flex-row w-full justify-evenly items-center">
                @if ($user->is_following)
                    <button class="bg-red-500 rounded-md h-8 m-auto text-gray-50 shadow-md cursor-pointer w-4/6 hover:bg-red-600 disabled:bg-red-200" data-id="{{ $user->id }}" onclick="unFollow(this,{{ $user->id }})">Unfollow</button>
                @else
                    <button class="bg-blue-600 rounded-md h-8 m-auto text-gray-50 shadow-md cursor-pointer w-4/6 hover:bg-blue-700 disabled:bg-blue-200" data-id="{{ $user->id }}" onclick="follow(this,{{ $user->id }})">Follow</button>
                @endif
                <button class="w-1/6 rounded-md text-gray-50 h-8 bg-gray-400 hover:bg-gray-500 mr-2"><i class="fa fa-ban" onclick="block(this,{{ $user->id }})"></i></button>
            </div>
        </div>
    </div>
@endforeach