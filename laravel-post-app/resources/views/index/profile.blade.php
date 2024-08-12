@include('includes.header-script', ['url' => 'Profile'])

@include('includes.session')

<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')

    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-hidden flex flex-row">
        @if (null !== session('user') && $data->id == session('user')->id)
            @include('page-sections.left-sidebar', ['active' => 'profile', 'id' => session('user')->id])
        @else
            @include('page-sections.left-sidebar', ['active' => '', 'id' => session('user')->id])
        @endif
        
        
        @if ($data->id == session('user')->id)
            @include('profile.user',['data' => $data, 'posts' => $posts, 'followers' => $followers])
        @else
            @include('profile.others', ['data' => $data, 'posts' => $posts, 'followers' => $followers])
        @endif
    </div>

</body>