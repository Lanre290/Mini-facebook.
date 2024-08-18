@include('includes.header-script', ['url' => 'Messages'])

<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    
    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => 'messages'])
        @endif
        <div class="w-6/12 border-l border-gray-300 h-full">
            <div class="flex flex-grow flex-col w-full" style="height: calc(100% - 3.6rem);">
                <div class="w-full flex flex-row p-2 items-center justify-end">
                    <div class="flex flex-col w-10 h-10 m-3 relative">
                        <button class="w-10 h-10 rounded-full text-gray-500 cursor-pointer hover:bg-gray-200" onclick="showMessageOption(this)"><i class="fa fa-ellipsis-v"></i></button>
                        <div class="flex flex-col absolute top-11 right-0 w-48 shadow-lg bg-gray-50" id="options" style="display: none;">
                            <button class="w-full h-10 flex items-center justify-center text-gray-500 hover:bg-gray-200">Delete for me</button>
                            <button class="w-full h-10 flex items-center justify-center text-gray-500 hover:bg-gray-200">Delete for everyone</button>
                        </div>
                    </div>
                    <div class="p-2 bg-blue-500 text-gray-50 font-extralight rounded-tr-xl rounded-tl-xl rounded-bl-xl rounded-br-md">Hello.</div>
                </div>
                <div class="w-full flex flex-row p-2 items-center justify-end">
                    <div class="flex flex-col w-10 h-10 m-3 relative">
                        <button class="w-10 h-10 rounded-full text-gray-500 cursor-pointer hover:bg-gray-200" onclick="showMessageOption(this)"><i class="fa fa-ellipsis-v"></i></button>
                        <div class="flex flex-col absolute top-11 right-0 w-48 shadow-lg bg-gray-50" id="options" style="display: none;">
                            <button class="w-full h-10 flex items-center justify-center text-gray-500 hover:bg-gray-200">Delete for me</button>
                            <button class="w-full h-10 flex items-center justify-center text-gray-500 hover:bg-gray-200">Delete for everyone</button>
                        </div>
                    </div>
                    <div class="bg-blue-500 text-gray-50 font-extralight rounded-tr-xl rounded-tl-xl rounded-bl-xl rounded-br-md w-1/2">
                        <div class="bg-center bg-cover bg-no-repeat w-full h-28 rounded-tr-lg rounded-tl-lg" style="background-image: url('{{ asset('img/cover_images/3.png') }}')"></div>
                        <div class="m-2">Hello.</div>
                    </div>
                </div>
            </div>
            <form class="w-full h-14 flex flex-row border items-center px-1 border-t border-gray-300">
                <input type="hidden" class="hidden" name="message-token" value="{{ csrf_token() }}">
                <input type="text" name="message" id="" class="flex h-12 p-3 flex-grow bg-gray-200 rounded-lg" placeholder="Type a message..." oninput="validateCommentButton(this)">
                <button class="cursor-default w-12 h-12 bg-cover bg-center bg-no-repeat ml-4 rounded-full text-gray-600 text-3xl hover:bg-gray-400 hover:bg-opacity-50 disabled:text-gray-600" disabled><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>


        {{-- right side --}}

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