<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('includes.header-script', ['url' => $post->user->name.' - '.$post->text])
    @include('includes.session')

    <div class="fixed top-0 right-0 bottom-0 left-0 h-full w-full flex flex-col sm:flex-row overflow-y-auto">
        <div class="md:hidden flex-row justify-between w-full h-full py-2 px-2 flex bg-gray-200">
            <div class="flex flex-row items-center justify-center">
                <a href="{{ route('profile', ['id' => $post->user->id]) }}" class="h-12 w-12 rounded-full cursor-pointer bg-gray-400 mr-1 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ $post->user->image_path }}')"></a>
                <div class="flex flex-col">
                    <a href="{{ route('profile', ['id' => $post->user->id]) }}" class=""><h3 class="text-gray-600 ml-1 hover:text-blue-600">{{ $post->user->name }} </h3></a>
                    <h3 class="text-gray-600 ml-1 text-xs">{{ $post->date }}</h3>
                </div>
            </div>
            <div class="flex flex-row m-3">
                <div class="flex flex-col relative">
                    <button class="p-3 h-12 w-12 px-4 rounded-full cursor-pointer bg-transparent text-gray-600 mr-1 hover:bg-gray-400 hover:bg-opacity-50" onclick="showPostOption(this)"><i class="fa fa-ellipsis-h"></i></button>
                    <div class="flex-col w-40 absolute top-14 -right-1/4 bg-gray-300 z-50 rounded-lg" style="display: none;">
                        <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 {{ $post->user->id == session('user')->id ? 'rounded-tl-lg rounded-tr-lg' : 'rounded-lg' }}" data-rel="{{ route('post', ['id' => $post->id]) }}" onclick="copyPostUrl(this)"><i class="fa fa-link"></i> Copy Url</button>
                        @if ($post->user->id == session('user')->id)
                            <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 rounded-bl-lg rounded-br-lg main-post" onclick="deletePost(this,{{ $post->id }})"><span class="text-red-600"><i class="fa fa-archive"></i> </span>Delete Post</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="h-full w-full sm:w-4/6 bg-gray-100 bg-cover bg-no-repeat bg-center relative post-div scroll-smooth">
            @if (count($post->files) > 1)
                <button class="w-10 h-10 text-gray-500 bg-transparent hover:bg-gray-400 hover:bg-opacity-50 absolute top-1/2 left-1 z-50 prev-post">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="w-10 h-10 text-gray-500 bg-transparent hover:bg-gray-400 hover:bg-opacity-50 absolute top-1/2 right-1 z-50 next-post">
                    <i class="fa fa-chevron-right"></i>
                </button>
            @endif
            <div class="w-full flex flex-row h-full overflow-x-auto post-div" onsrollend="maintainPostScroll(this)">
                @if (count($post->files) > 0)
                    @foreach ($post->files as $file)
                        @if (is_file($file->path))
                            @if (strpos(mime_content_type($file->path), 'image/') === 0)
                                <image src="{{ asset($file->path) }}" class="h-full w-full min-h-full min-w-full object-contain"></image>
                            @elseif (strpos(mime_content_type($file->path), 'video/') === 0)
                                <div class="relative h-full w-full min-h-full min-w-full video-cont bg-black"  data-interval="" onmousemove="showControl(this)">
                                    <button class="absolute top-1/2 left-1/2 right-1/2 bottom-1/2 text-gray-50 text-7xl cursor-pointer p-6 z-50" id="play-btn" style="transform: translate(-50%, -50%);" onclick="playVideo(this, event)"><i class="fa fa-play"></i></button>
                                    <video src="{{ asset($file->path) }}" class="h-full w-full min-h-full min-w-full bg-black" id="post_video" ontimeupdate="progressPostVideo(this, event)">
                                    </video>
                                    <div class="absolute w-full flex flex-row justify-end items-end bottom-3 right-0 h-40 left-0 z-50" id="controls">
                                        <input type="range" class="h-1 cursor-pointer mb-3 m-2 flex-grow white-accent outline-none" id="playback" value="0" oninput="seekPostVideo(this, event)">
                                        <div class="flex flex-col justify-center items-end w-16 h-40 relative post-audio-cont">
                                            <input type="range" class="cursor-pointer white-accent outline-none h-3 w-24 absolute bottom-20 -right-7 -mt-5 ml-3 volume-control hidden" style="transform: rotate(-90deg);" value="100" oninput="managePostVolume(this, event)"/>
                                            <button class="text-gray-50 text-4xl cursor-pointer w-10 h-10 absolute -bottom-1 right-1" onclick="mutePostVideo(this, event)">
                                                <i class="fa fa-volume-up"></i>
                                            </button>
                                        </div>
                                        <button class="text-gray-50 text-4xl cursor-pointer w-16 h-10 -mb-1" data-type="normal" onclick="putVideoFullScreen(this)">
                                            <i class="fa fa-expand"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="h-screen w-screen min-h-screen min-w-screen flex flex-col items-center justify-center object-cover">
                                <div class="text-8xl"><i class="fa fa-chain-broken"></i></div>
                                <div class="text-5xl">File not found. ☹️</div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <h3 class="h-full w-full text-3xl text-gray-700 text-center flex justify-center text-clip break-words items-center"> {{ $post->text }} </h3>
                @endif
            </div>
        </div>
            

        <div class="w-full sm:w-2/6 flex flex-col">
            <div class="w-full md:h-1/6 flex flex-col items-center">
                <div class="md:flex flex-row justify-between w-full h-full py-2 px-2 hidden bg-gray-200">
                    <div class="flex flex-row items-center justify-center">
                        <a href="{{ route('profile', ['id' => $post->user->id]) }}" class="h-12 w-12 rounded-full cursor-pointer bg-gray-400 mr-1 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ $post->user->image_path }}')"></a>
                        <div class="flex flex-col">
                            <a href="{{ route('profile', ['id' => $post->user->id]) }}" class=""><h3 class="text-gray-600 ml-1 hover:text-blue-600">{{ $post->user->name }} </h3></a>
                            <h3 class="text-gray-600 ml-1 text-xs">{{ $post->date }}</h3>
                        </div>
                    </div>
                    <div class="flex flex-row m-3">
                        <div class="flex flex-col relative">
                            <button class="p-3 h-12 w-12 px-4 rounded-full cursor-pointer bg-transparent text-gray-600 mr-1 hover:bg-gray-400 hover:bg-opacity-50" onclick="showPostOption(this)"><i class="fa fa-ellipsis-h"></i></button>
                            <div class="flex-col w-40 absolute top-14 -right-1/4 bg-gray-300 z-50 rounded-lg" style="display: none;">
                                <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 {{ $post->user->id == session('user')->id ? 'rounded-tl-lg rounded-tr-lg' : 'rounded-lg' }}" data-rel="{{ route('post', ['id' => $post->id]) }}" onclick="copyPostUrl(this)"><i class="fa fa-link"></i> Copy Url</button>
                                @if ($post->user->id == session('user')->id)
                                    <button class="w-full h-12 text-gray-600 cursor-pointer hover:bg-gray-400 hover:bg-opacity-50 rounded-bl-lg rounded-br-lg main-post" onclick="deletePost(this,{{ $post->id }})"><span class="text-red-600"><i class="fa fa-archive"></i> </span>Delete Post</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="text-gray-600 flex justify-start bg-gray-200 p-2 w-full">
                    @if (count($post->files) > 0)
                        {{ $post->text }}
                    @endif
                </h3>
            </div>

            <div class="h-4/6 bg-gray-200 border-t flex flex-col border-gray-600 border-b overflow-y-auto {{ count($comments) < 1 ? 'items-center justify-center' : '' }}">
                @if(count($comments) > 0)
                    @foreach ($comments as $comment)
                        <div class="flex-row flex w-full p-2">
                            <a href="{{ route('profile', ['id' => $comment->user->id]) }}" class="h-12 w-12 min-h-12 min-w-12 flex rounded-full cursor-pointer bg-gray-400 mr-3 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ $comment->user->image_path }}')"></a>
                            <div class="flex flex-col w-full">
                                <div class="bg-gray-300 flex rounded-lg p-3 flex-col text-clip w-fit" style="max-width: calc(96% - 48px);width: fit-content;">
                                    <a href="{{ route('profile', ['id' => $comment->user->id]) }}" class="text-gray-600 text-xs hover:text-blue-600 hover:underline flex flex-row">  <h3 class="text-blue-600">{{ $comment->by_creator == true ? 'Creator • ' : '' }}</h3>{{$comment->user->id == session('user')->id ? 'You' : $comment->user->name }}</a>
                                    <h3 class="text-gray-600">{{ $comment->text }}</h3>
                                </div>
                                <div class="flex flex-row mt-1">
                                    <h3 class="text-xs text-gray-600 ml-2"> {{ $comment->date }} </h3>
                                    <input type="hidden" name="like-comment-token" value="{{ csrf_token() }}">
                                    @if ($comment->user->id != session('user')->id)
                                        <img src="{{ $comment->isLiked == true ? asset('img/heart_.png') : asset('img/like.png') }}" alt="Like" class=" m-2 mt-0 mr-0 w-4 h-4 cursor-pointer grayscale post-btns" data-liked="{{ $comment->isLiked == true ? "true" : "false" }}" data-id="{{ $comment->id }}" onclick="likeComment(this)"/>
                                        <h3 class="text-xs text-gray-600 ml-2"> {{ $comment->no_of_likes }} Likes </h3>
                                    @endif
                                    @if ($comment->user->id == session('user')->id)
                                        <input type="hidden" name="delete-comment-token" value="{{ csrf_token() }}">
                                        <h3 class="text-gray-600 text-xs w-fit ml-2 cursor-pointer hover:text-blue-600 hover:underline" data-id="{{ $comment->id }}" onclick="deleteComment(this)">Delete</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <img src="{{ asset('img/coment.png') }}" alt="no-comment" class="w-32 h-32 object-contain mx-auto" />
                    <h3 class="text-gray-600 mx-auto">No Comments on this post</h3>
                @endif
            </div>

            <div class="md:h-1/6">
                <div class="h-full w-full flex flex-col justify-end items-start bg-gray-200 sm:rounded-bl-xl sm:rounded-br-xl">
                    <div class="flex flex-row">
                        <input type="hidden" name="like-token" value="{{ csrf_token() }}">
                        <input type="hidden" name="save-token" value="{{ csrf_token() }}">
                        <button class="{{ $post->isLiked == true ? 'text-red-700' : '' }} cursor-pointer text-3xl mt-1 ml-2 mr-2 post-btns" data-liked="{{ $post->isLiked }}" data-id="{{$post->id}}" onclick="like(this)"><i class="{{ $post->isLiked == true ? 'fa fa-heart' : 'far fa-heart' }}"></i></button>
                        <button class="{{ $post->isSaved == true ? 'text-red-700' : '' }} cursor-pointer text-3xl mt-1 ml-2 mr-2 post-btns" data-saved="{{ $post->isSaved }}" data-id="{{$post->id}}" onclick="save(this)"><i class="{{ $post->isSaved == true ? 'fa fa-bookmark' : 'far fa-bookmark' }}"></i></button>
                        <h3 class="h-full flex items-end" onclick="showPostFull({{$post->id}})"> {{ $post->likes }} Likes </h3>
                        <h3 class="h-full flex items-end">&nbsp;&bull;&nbsp;</h3>
                        <h3 class="h-full flex items-end" onclick="showPostFull({{$post->id}})" id="comment-text"> {{ $post->comment_count }} Comments </h3>
                    </div>
                    <div class="flex flex-row w-full p-2">
                        <form action="api/comment" method="post" onsubmit="submitPostComment(this, event)" class="w-full flex flex-row real-post-comment">
                            <input type="hidden" class="hidden" name="comment-token" value="{{ csrf_token() }}">
                            <input type="text" data-id="{{$post->id}}" name="text" id="" class=" flex flex-grow  p-2 bg-gray-400 bg-opacity-50 rounded-lg comment-box" oninput="validateCommentButton(this)" placeholder="Leave a comment">
                            <button class="cursor-not-allowed w-12 h-12 bg-cover bg-center bg-no-repeat ml-4 rounded-full text-gray-600 text-3xl hover:bg-gray-400 hover:bg-opacity-50 disabled:text-gray-600" disabled><i class="fa fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    



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
            }
        });
        document.querySelectorAll('.next-post').forEach(element => {
            element.onclick = () => {
                element.nextElementSibling.scrollBy({left: element.nextElementSibling.clientWidth,top: 0, behavior: 'smooth'});
            }
        });

        function maintainPostScroll(target){
            let ratio = Math.floor(target.scrollLeft / target.clientWidth);
            target.scrollLeft = (target.clientWidth * (ratio-1));
        }

        
    </script>
   
        

</body>
</html> 