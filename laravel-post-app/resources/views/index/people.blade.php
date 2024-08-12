@include('includes.header-script', ['url' => 'People'])
<meta name="csrf-token" content="{{ csrf_token() }}">

<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    
    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => 'people'])
        @endif
        <div class="w-full sm:w-full md:w-9/12 z-10 min-h-screen py-10 flex flex-wrap content-start items-start justify-center h-full">
            {{-- <div class="flex flex-col rounded-lg cursor-pointer h-2/5 w-1/5 bg-gray-300 m-2">
                <div class="h-3/5 w-full rounded-tl-lg rounded-tr-lg bg-gray-400"></div>
                <div class="flex flex-col justify-center items-center w-full h-2/5">
                    <h3 class="text-2xl text-gray-600 text-center overflow-hidden w-5/6 text-ellipsis" style='white-space: nowrap;'>Ashiru Sheriff  Olanrewaju</h3>
                    <h3 class="mb-2 text-gray-600">40 Followers</h3>
                    <div class="flex flex-row w-full justify-evenly items-center">
                        <button class="bg-blue-600 rounded-md h-8 m-auto text-gray-50 shadow-md cursor-pointer w-4/6 hover:bg-blue-700">Follow</button>
                        <button class="w-1/6 rounded-md text-gray-50 h-8 bg-gray-400 hover:bg-gray-500 mr-2"><i class="fa fa-ban"></i></button>
                    </div>
                </div>
            </div> --}}
            @include('includes.people', ['data' => $data]);
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>