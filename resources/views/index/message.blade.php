@include('includes.header-script', ['url' => 'Messages'])

<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    
    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => 'messages'])
        @endif
        <div class="w-6/12 border-l border-gray-300 h-full">

        </div>
        <div class="w-3/12 border-l border-gray-300 flex flex-col">
            <div class="w-full my-4">
                <div class="rounded-full w-24 h-24 m-auto bg-center bg-cover bg-no-repeat" style="background-image: url('{{ asset('img/users_dp/default.png') }}')"></div>
                <h3 class="text-gray-600 m-auto text-center text-2xl mt-2 font-semibold">Ashiru Olanrewaju</h3>
                <div class="flex flex-row w-full justify-center items-center">
                    <button class="rounded-full bg-gray-100 w-12 h-12 text-gray-600 cursor-pointer m-2 hover:bg-gray-200" title="Visit profile"><i class="fa fa-user"></i></button>
                    <button class="rounded-full bg-gray-100 w-12 h-12 text-gray-600 cursor-pointer m-2 hover:bg-gray-200" title="Block"><i class="fa fa-ban"></i></button>
                </div>
            </div>
            <h3 class="text-gray-600 text-4xl ml-3 font-bold mt-3">Messages</h3>
            <div class="flex-col mt-4 w-full overflow-x-hidden">
                <div class="w-full h-24 cursor-pointer bg-transparent flex flex-row items-center hover:bg-gray-100">
                    <div class="rounded-full bg-center m-3 bg-cover bg-no-repeat min-h-16 min-w-16" style="min-width: 4rem;min-height: 4rem;background-image: url('{{ asset('img/users_dp/default.png') }}')"></div>
                    <div class="flex flex-col h-full items-start justify-center">
                        <h3 class="text-2xl">Ashiru Olanrewaju</h3>
                        <h3 class="w-7/12 text-ellipsis overflow-hidden whitespace-nowrap" style="text-overflow: ellipsis;">Hi, i'm a boyi sdjifgshfwgsjfhgwufuifhiwufgfgfugewfsfgsfgssgsdhj</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 