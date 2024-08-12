@include('includes.session')
@foreach ($data as $post)
    <div class="w-full sm:w-4/6 lg:w-3/6 min-h-full h-full sm:rounded-xl m-auto my-4">
        <div class="flex flex-col items-center h-full w-full">
            <div class="flex flex-row justify-between w-full h-1/6 py-2 px-2 bg-gray-200 sm:rounded-tl-xl sm:rounded-tr-xl" href="{{ route('post', ['id' => $post->id]) }}">
                <div class="flex flex-row items-center justify-center">
                    <a href="{{ route('profile', ['id' => $post->user->id]) }}" class="h-12 w-12 rounded-full cursor-pointer bg-gray-400 mr-1 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ $post->user->image_path }}')"></a>
                    <div class="flex flex-col">
                        <a href="{{ route('profile', ['id' => $post->user->id]) }}">
                            <h3 class="text-gray-600 ml-1 hover:text-blue-600"> {{ $post->user->name }} </h3>
                        </a>
                        <h3 class="text-gray-600 ml-1 text-xs">{{ $post->date }}</h3>
                    </div>
                </div>
                <div class="flex flex-row m-3">
                    <a href="{{ route('post', ['id' => $post->id]) }}" class="p-3 h-12 w-12 px-4 rounded-full cursor-pointer bg-transparent text-gray-600 mr-1 hover:bg-gray-400 hover:bg-opacity-50"><i class="fa fa-external-link"></i></a>
                    <div class="flex flex-col relative">
                        <button class="p-3 h-12 w-12 px-4 rounded-full cursor-pointer bg-transparent text-gray-600 mr-1 hover:bg-gray-400 hover:bg-opacity-50" onclick="showPostOption(this)"><i class="fa fa-ellipsis-h"></i></button>
                        <div class="flex-col w-40 absolute top-14 -right-1/2 bg-gray-300 z-50 rounded-lg" style="display: none;">
                            <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 {{ $post->user->id == session('user')->id ? 'rounded-tl-lg rounded-tr-lg' : 'rounded-lg' }}" data-rel="{{ route('post', ['id' => $post->id]) }}" onclick="copyPostUrl(this)"><i class="fa fa-link"></i> Copy Url</button>
                            @if ($post->user->id == session('user')->id)
                                <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 rounded-bl-lg rounded-br-lg" onclick="deletePost(this,{{ $post->id }})"><span class="text-red-600"><i class="fa fa-archive"></i> </span>Delete Post</button>
                            @endif
                        </div>
                    </div>
                    <button class="p-3 h-12 w-12 px-4 rounded-full cursor-pointer bg-transparent text-gray-600 mr-1 hover:bg-gray-400 hover:bg-opacity-50" onclick="hidePost(this)"><i class="fa fa-close"></i></button>
                </div>
            </div>
            <h3 class="text-gray-600 flex justify-start bg-gray-200 p-2 w-full">

                @if (count($post->files) > 0)
                    {{ $post->text }}
                @endif
            </h3>
            <div class="h-4/6 w-full bg-gray-100 bg-cover bg-no-repeat bg-center relative post-div scroll-smooth">
                @if (count($post->files) > 1)
                    <button class="w-10 h-10 text-gray-500 bg-transparent hover:bg-gray-400 hover:bg-opacity-50 absolute top-1/2 left-1 z-30 prev-post">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button class="w-10 h-10 text-gray-500 bg-transparent hover:bg-gray-400 hover:bg-opacity-50 absolute top-1/2 right-1 z-30 next-post">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                @endif
                <div class="w-full flex flex-row h-full overflow-x-auto post-div" onsrollend="maintainPostScroll(this)">
                    @if (count($post->files) > 0)
                        @foreach ($post->files as $file)
                            @if (strpos(mime_content_type($file->path), 'image/') === 0)
                                <image src="{{ asset($file->path) }}" class="h-full w-full min-h-full min-w-full object-cover"></image>
                            @elseif (strpos(mime_content_type($file->path), 'video/') === 0)
                                <div class="relative h-full w-full min-h-full min-w-full video-cont" data-interval="" onmousemove="showControl(this)">
                                    <button class="absolute top-1/2 left-1/2 right-1/2 bottom-1/2 text-gray-50 text-4xl cursor-pointer p-6 z-50" id="play-btn" style="transform: translate(-50%, -50%);" onclick="playVideo(this, event)"><i class="fa fa-play"></i></button>
                                    <video src="{{ asset($file->path) }}" class="h-full w-full min-h-full min-w-full" id="post_video" ontimeupdate="progressPostVideo(this, event)">
                                    </video>
                                    <div class="absolute w-full flex flex-row justify-end items-end bottom-1 right-0 h-40 left-0 z-50" id="controls">
                                        <input type="range" class="h-1 cursor-pointer mb-3 m-2 flex-grow white-accent outline-none" id="playback" value="0" oninput="seekPostVideo(this, event)">
                                        <div class="flex flex-col justify-center items-end w-6 h-40 relative post-audio-cont">
                                            <input type="range" class="cursor-pointer white-accent outline-none h-2 w-20 absolute bottom-16 -right-7 -mt-5 ml-3 volume-control hidden" style="transform: rotate(-90deg);" oninput="managePostVolume(this, event)"/>
                                            <button class="text-gray-400 cursor-pointer w-10 h-10 absolute -bottom-1 -right-2" onclick="mutePostVideo(this, event)">
                                                <i class="fa fa-volume-up"></i>
                                            </button>
                                        </div>
                                        <button class="text-gray-400 cursor-pointer w-10 h-10 -mb-1" data-type="normal" onclick="putVideoFullScreen(this)">
                                            <i class="fa fa-expand"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <h3 class="h-full w-full text-3xl text-gray-700 text-center flex justify-center text-clip break-words items-center"> {{ $post->text }} </h3>
                    @endif
                </div>
            </div>
            <div class="h-1/6 w-full flex flex-col justify-start items-start bg-gray-200 sm:rounded-bl-xl sm:rounded-br-xl">
                <div class="flex flex-row">
                    <input type="hidden" name="like-token" value="{{ csrf_token() }}">
                    <input type="hidden" name="save-token" value="{{ csrf_token() }}">
                    <button class="{{ $post->isLiked == true ? 'text-red-700' : '' }} cursor-pointer text-3xl mt-1 ml-2 mr-2 post-btns" data-liked="{{ $post->isLiked }}" data-id="{{$post->id}}" onclick="like(this)"><i class="{{ $post->isLiked == true ? 'fa fa-heart' : 'far fa-heart' }}"></i></button>
                    <button class="{{ $post->isSaved == true ? 'text-red-700' : '' }} cursor-pointer text-3xl mt-1 ml-2 mr-2 post-btns" data-saved="{{ $post->isSaved }}" data-id="{{$post->id}}" onclick="save(this)"><i class="{{ $post->isSaved == true ? 'fa fa-bookmark' : 'far fa-bookmark' }}"></i></button>
                    <a class="h-full flex items-end cursor-pointer hover:underline hover:text-blue-600" href="{{ route('post', ['id' => $post->id]) }}"> {{ $post->likes }} Likes </a>
                    <h3 class="h-full flex items-end">&nbsp;&bull;&nbsp;</h3>
                    <a class="h-full flex items-end cursor-pointer hover:underline hover:text-blue-600" href="{{ route('post', ['id' => $post->id]) }}" id="comment-text"> {{ $post->comment_count }} Comments </a>
                </div>
                <div class="flex flex-row w-full p-2">
                    <form action="api/comment" method="post" onsubmit="submitPostComment(this, event)" class="w-full flex flex-row">
                        <input type="hidden" class="hidden" name="comment-token" value="{{ csrf_token() }}">
                        <input type="text" data-id="{{$post->id}}" name="text" id="" class=" flex flex-grow  p-2 bg-gray-400 bg-opacity-50 rounded-lg" oninput="validateCommentButton(this)" placeholder="Leave a comment">
                        <button class="cursor-not-allowed w-12 h-12 bg-cover bg-center bg-no-repeat ml-4 rounded-full text-gray-600 text-3xl hover:bg-gray-400 hover:bg-opacity-50 disabled:text-gray-600" disabled><i class="fa fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <style>
        .post-div::-webkit-scrollbar{
            display: none;
        }
        .post-btns:active{
            transform: scale(0.90);
        }
        .white-accent{
            accent-color: white;
        }
        .post-audio-cont:hover .volume-control{
            display: block;
        }
    </style>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        document.querySelectorAll('.prev-post').forEach(element => {
            element.onclick = () => {
                element.nextElementSibling.nextElementSibling.scrollBy({left: -(element.nextElementSibling.nextElementSibling.clientWidth),top: 0, behavior: 'smooth'});
                try {
                    if(element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('video').paused == false){
                        element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('video').pause();
                        element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('#play-btn').innerHTML = '<i class="fa fa-play"></i>';
                        console.log(element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('#play-btn'));
                    }
                } catch (error) {
                    
                }
            }
        });
        document.querySelectorAll('.next-post').forEach(element => {
            element.onclick = () => {
                element.nextElementSibling.scrollBy({left: element.nextElementSibling.clientWidth,top: 0, behavior: 'smooth'});
                try {
                    if(element.nextElementSibling.querySelector('.video-cont').querySelector('video').paused == false){
                        element.nextElementSibling.querySelector('.video-cont').querySelector('video').pause();
                        console.log(element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('#play-btn'));
                        element.nextElementSibling.nextElementSibling.querySelector('.video-cont').querySelector('#play-btn').innerHTML = '<i class="fa fa-pause"></i>';
                    }
                } catch (error) {
                    
                }
            }
        });

        function maintainPostScroll(target){
            let ratio = Math.floor(target.scrollLeft / target.clientWidth);
            target.scrollLeft = (target.clientWidth * (ratio-1));
        }
    </script>