@include('includes.header-script', ['url' => 'Search'])


<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')
    
    
    <div class="absolute top-20 right-0 left-0 bottom-0 overflow-y-auto flex flex-row">
        @if (null !== session('user'))
            @include('page-sections.left-sidebar', ['id' => session('user')->id, 'active' => ''])
        @endif
        <div class="w-full sm:w-full lg:w-9/12 z-10 py-10 flex flex-col min-h-full h-fit overflow-y-auto justify-between">
            <div class="w-full flex flex-row">
                <div class="px-6 py-2 cursor-pointer hover:bg-gray-300 hover:bg-opacity-50 active-search search-div" id="people">
                    People
                </div>
                <div class="px-6 py-2 cursor-pointer hover:bg-gray-300 hover:bg-opacity-50 search-div" id="post">
                    Posts
                </div>
            </div>
            <div id="people_div" class="w-full min-h-screen flex {{ count($user) > 0 ? 'flex-wrap' : 'flex-col justify-center items-center'}}">
                @if (count($user) > 0)
                    @include('includes.people', ['data' => $user])
                @else
                    <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
                    <h3 class="text-gray-600 mx-auto text-2xl">No users found. ☹️</h3>
                @endif
            </div>
            <div id="posts_div" class="w-full min-h-screen flex-col hidden">
                @if (count($posts) > 0)
                    @include('includes.posts', ['data' => $posts])
                @else
                    <img src="{{ asset('img/empty-box.png') }}" alt="no saved post" class="w-32 h-32 object-contain mx-auto mt-10" />
                    <h3 class="text-gray-600 mx-auto text-2xl">No posts found. ☹️</h3>
                @endif
            </div>
        </div>
    </div>

    <style>
        .active-search{
            border-bottom: 4px solid rgb(37, 99, 235); 
        }
    </style>

    <script>
        document.querySelectorAll('.search-div').forEach(element => {
            element.addEventListener('click', () => {
                document.querySelectorAll('.search-div').forEach(el => {
                    el.classList.remove('active-search');
                });

                element.classList.add('active-search');

                if(element.id == 'post'){
                    document.getElementById('posts_div').style.display = 'flex';
                    document.getElementById('people_div').style.display = 'none';
                }
                else if(element.id == 'people'){
                    document.getElementById('people_div').style.display = 'flex';
                    document.getElementById('posts_div').style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 