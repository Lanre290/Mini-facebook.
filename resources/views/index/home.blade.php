@include('includes.header-script', ['url' => 'Home'])


<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    

    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => 'home'])
        @endif
        <div class="w-full sm:w-full lg:w-9/12 z-10 py-10 flex flex-col min-h-full h-fit overflow-y-auto justify-between">
            @include('includes.posts',['data' => $data])
        </div>
    </div>
</body>
</html> 