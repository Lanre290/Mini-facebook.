@include('includes.header-script', ['url' => 'Saved'])


<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    
    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => 'saved', 'unread' => $unread])
        @endif
        <div class="w-full sm:w-full lg:w-9/12 z-10 py-10 flex flex-col min-h-full h-fit overflow-y-auto {{ count($data) > 0 ? 'justify-between' : 'items-center' }}">
            @if (count($data) > 0)
                @include('includes.posts',['data' => $data])
            @else
                <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
                <h3 class="text-gray-600 mx-auto text-2xl">You have no Saved post. ☹️</h3>
            @endif
        </div>
    </div>
</body>
</html>