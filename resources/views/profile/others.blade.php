
@include('includes.session')


<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="w-full sm:w-full md:w-full lg:w-9/12 z-10 flex flex-col h-full justify-start overflow-y-auto">
    <div class="flex flex-col">
        <div class="w-full h-1/3 py-36  rounded-bl-xl rounded-br-xl bg-gray-400 bg-center bg-cover bg-no-repeat relative" style="background-image: url('{{asset($data->cover_img_path) }}')">
            <div class="w-36 h-36 rounded-full border border-gray-50 bg-gray-300 absolute bottom-0 left-4 bg-cover bg-center bg-no-repeat" style="transform: translateY(50%);background-image: url('{{ asset($data->image_path) }}')">
            </div>
        </div>
        <div class="flex flex-row align-center justify-between mt-16">
            <h1 class="text-5xl text-gray-800 ml-3 p-2 bg-transparent" id='user-name-text'>{{ $data->name }}</h1>
        </div>
        <h3 class="p-2 mb-3 ml-3 text-2xl italic" id='user-bio-text'>
            @if ($data->bio !== '')
                {{ $data->bio }}
            @endif
        </h3>
        @if ($data->is_following)
            <button class="bg-red-500 rounded-md h-10 mb-2 ml-5 text-gray-50 shadow-md cursor-pointer w-5/6 md:w-64 hover:bg-red-600 disabled:bg-red-200 profile-page" data-id="{{ $data->id }}" onclick="unFollow(this,{{ $data->id }})">Unfollow</button>
        @else
            <button class="bg-blue-600 rounded-md h-10 mb-2 ml-5 text-gray-50 shadow-md cursor-pointer w-5/6 md:w-64 hover:bg-blue-700 disabled:bg-blue-200 profile-page" data-id="{{ $data->id }}" onclick="follow(this,{{ $data->id }})">Follow</button>
        @endif
        <div class="flex flex-row ml-5">
            <div class="text-gray-800 mr-5" id="followers">
                <Span class="font-bold"> {{ $data->followers }} </span> followers
            </div>
            <div class="text-gray-800">
                <Span class="font-bold"> {{ $data->following }} </Span> following
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-center border-t mt-5 border-gray-400 py-4 w-11/12">
        <ul class="flex flex-row">
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='posts-btn'>Posts</li>
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='followers-btn'>Followers</li>
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='videos-btn'>Videos</li>
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='img-btn'>Pictures</li>
        </ul>
    </div>
    <div class="w-full z-10 py-10 flex flex-col h-full pb-5 justify-between user-posts-conts" id='posts-cont'>
        @include('includes.posts',['data' => $posts])
    </div>
    <div class="w-full flex flex-wrap content-start items-start h-full pb-40 justify-center user-posts-conts" id='videos-cont' style="display: none;">
        @if (count($videos) == 0)
            <div class="w-full flex flex-col items-center">
                <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
                <h3 class="text-gray-600 mx-auto text-2xl">No videos found for this account. ☹️</h3>
            </div>
        @endif
        @foreach ($videos as $video)
            <a class="relative w-1/4 h-56 m-1 bg-center bg-cover bg-no-repeat cursor-pointer bg-gray-200" href = "{{ route('post', ['id' => $video->post_id]) }}">
                <i class="fa fa-play text-3xl top-1 right-1 text-gray-50"></i>
                <video src="{{ asset($video->path) }}" class="h-full w-full max-h-full max-w-full absolute top-0 bottom-0 right-0 left-0 object-cover" autoplay loop muted></video>
                <div class="absolute bg-opacity-0 opacity-0 z-10 bg-gray-600 top-0 bottom-0 right-0 left-0 cursor-pointer hover:bg-opacity-50 hover:opacity-100 flex justify-center items-center">
                    <div class="flex flex-row">
                        <span class="mr-2 text-gray-50 text-2xl">
                            <i class="fa fa-heart"></i>
                            {{$video->likes}}
                        </span>
                        <span class="mr-2 text-gray-50 text-2xl">
                            <i class="fa fa-comment"></i>
                            {{$video->comments}}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="w-full flex flex-wrap content-start items-start h-full pb-40 justify-center user-posts-conts" id='img-cont' style="display: none;">
        @if (count($images) == 0)
            <div class="w-full flex flex-col items-center">
                <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
                <h3 class="text-gray-600 mx-auto text-2xl">No images found for this account. ☹️</h3>
            </div>
        @endif
        @foreach ($images as $image)
            <a class="relative w-1/4 h-56 m-1 bg-center bg-cover bg-no-repeat cursor-pointer bg-gray-200" href = "{{ route('post', ['id' => $image->post_id]) }}">
                <i class="fa fa-play text-3xl top-1 right-1 text-gray-50"></i>
                <image src="{{ asset($image->path) }}" class="h-full w-full max-h-full max-w-full absolute top-0 bottom-0 right-0 left-0 object-cover" autoplay loop muted></image>
                <div class="absolute bg-opacity-0 opacity-0 z-10 bg-gray-600 top-0 bottom-0 right-0 left-0 cursor-pointer hover:bg-opacity-50 hover:opacity-100 flex justify-center items-center">
                    <div class="flex flex-row">
                        <span class="mr-2 text-gray-50 text-2xl">
                            <i class="fa fa-heart"></i>
                            {{$image->likes}}
                        </span>
                        <span class="mr-2 text-gray-50 text-2xl">
                            <i class="fa fa-comment"></i>
                            {{$image->comments}}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="w-full flex content-start items-start h-full min-h-screen user-posts-conts {{ count($followers) > 0 ? 'flex-wrap justify-center' : 'flex-col justify-start' }}" id='followers-cont' style="display: none;">
        @if (count($followers) > 0)
            @include('includes.people',['data' => $followers])
        @else
            <img src="{{ asset('img/nobody.png') }}" alt="no-comment" class="w-32 h-32 object-contain mx-auto" />
            <h3 class="text-gray-600 mx-auto">No followers found for this account. ☹️</h3>
        @endif
    </div>
</div>


<style>
    .active-place{
        border-bottom: 4px solid rgba(37, 99, 235);
    }
</style>
<script>
    document.querySelectorAll('.user-deets-btn')[0].classList.add('active-place')
    document.querySelectorAll('.user-deets-btn').forEach(element => {
        element.addEventListener('click', async () => {
            document.querySelectorAll('.user-deets-btn').forEach(elem => {
                elem.classList.remove('active-place');
            });
            document.querySelectorAll('.user-posts-conts').forEach(elem => {
                elem.style.display = 'none';
            });

            if(element.id == 'posts-btn'){
                document.getElementById('posts-cont').style.display = 'flex';
            }
            else if(element.id == 'videos-btn'){
                document.getElementById('videos-cont').style.display = 'flex';
            }
            else if(element.id == 'followers-btn'){
                document.getElementById('followers-cont').style.display = 'flex';
            }
            else if(element.id == 'img-btn'){
                document.getElementById('img-cont').style.display = 'flex';
            }

            element.classList.add('active-place');
        });
    })
</script>