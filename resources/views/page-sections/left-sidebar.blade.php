<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="w-0 sm:w-0 md:w-0 lg:w-3/12 hidden z-10 py-10 sm:hidden md:hidden lg:flex flex-col h-full justify-between">
    <ul class="w-full flex flex-col items-start px-5">
            <li class="w-full rounded-3xl mb-5 text-center cursor-pointer  bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center my-0" id='profile_nav'>
                <a href='{{ route('profile', ['id' => $id]) }}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-left'>
                    <div class="bg-center bg-no-repeat bg-cover rounded-full w-10 h-10 min-w-10 min-h-10 mx-4" id="sidebar-dp" style="background-image: url('{{ asset(session('user')->image_path) }}');min-height: 2.5rem;min-width:2.5rem;"></div>
                    <h3 class="hidden font-bold whitespace-nowrap overflow-hidden lg:flex" style="text-overflow: ellipsis;">{{ session('user')->name }}</h3>
                </a>
            </li>
            <li class="w-full rounded-3xl text-center cursor-pointer  bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center my-0" id='home_nav'>
                <a href='{{ route('home')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-left'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/3d-house.png') }}')"></div>
                    <h3 class="hidden lg:flex">Home</h3>
                </a>
            </li>
            <li class="w-full rounded-3xl text-center cursor-pointer  bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center my-0" id='notif_nav'>
                <a href='{{ route('notifications')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-left relative'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/notification.png') }}')"></div>
                    <h3 class="hidden lg:flex">Notifications</h3>
                    @isset($unread)
                        @if ($unread > 0)
                            <div class="absolute bg-blue-500 flex justify-center items-center h-6 w-6 text-gray-50 text-sm rounded-full left-16 top-1/4">{{ $unread }}</div>
                        @endif
                    @endisset
                </a>
            </li>
            <li class="w-full rounded-3xl text-center cursor-pointer  bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center my-0" id='people_nav'>
                <a href='{{ route('people')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-left'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/social.png') }}')"></div>
                    <h3 class="hidden lg:flex">People</h3>
                </a>
            </li>
            <li class="w-full rounded-3xl text-center cursor-pointer  bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center my-0" id='saved_nav'>
                <a href='{{ route('saved')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-left'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/bookmark.png') }}')"></div>
                    <h3 class="hidden lg:flex">Saved</h3>
                </a>
            </li>
        @if ($active == 'home')
        <script>
            document.getElementById('home_nav').classList.add('bg-gray-200');
        </script>
        @elseif ($active == 'profile')
            <script>
                document.getElementById('profile_nav').classList.add('bg-gray-200');
            </script>
        @elseif ($active == 'people')
            <script>
                document.getElementById('people_nav').classList.add('bg-gray-200');
            </script>
         @elseif ($active == 'saved')
            <script>
                document.getElementById('saved_nav').classList.add('bg-gray-200');
            </script>
        @elseif ($active == 'messages')
            <script>
                document.getElementById('messages_nav').classList.add('bg-gray-200');
            </script>
        @elseif ($active == 'notifications')
            <script>
                document.getElementById('notif_nav').classList.add('bg-gray-200');
            </script>
        @endif
    </ul>

    <div class="flex flex-col justify-center items-center sm:hidden lg:flex w-full">
        <button class="bg-blue-500 py-3 px-6 rounded-xl cursor-pointer text-2xl text-gray-50 font-bold w-5/6 mb-5 m-auto hover:bg-blue-600" onclick="toggleMakePost()" id="make-post-btn">Create</button>
        <a class="text-red-600 font-bold text-2xl mx-auto cursor-pointer mt-5 m-3" onclick="logout()" id='logout'>Logout</a>
    </div>

    
</div>

<div class="fixed top-0 bottom-0 right-0 left-0 bg-gray-800 bg-opacity-50 justify-center items-center z-50 hidden" id="make-post-div">
    <div class="w-full h-full sm:w-full sm:h-full md:w-3/6 md:h-5/6 rounded-lg bg-gray-50">
        <div class="flex flex-row justify-between mt-5 w-full px-3">
            <i></i>
            <h3 class="text-gray-600 text-2xl">Make a post</h3>
            <button class="w-12 h-12 rounded-full cursor-pointer bg-transparent text-gray-600 hover:bg-gray-400 hover:bg-opacity-50 close-dialog" id="close-make-post">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="flex flex-col justify-start h-5/6 min-h-5/6 overflow-y-auto">
            <form action="{{ route('api/make-post') }}" method="post" class="flex flex-col justify-between h-full" id="make-post-form">
                @csrf
                @method('PUT')
                <input name="csrf-token-save-data" value="{{ csrf_token() }}" hidden>
                <div class="w-5/6 bg-gray-200 rounded-lg cursor-pointer relative m-auto h-36 change_dp bg-cover bg-center flex items-center justify-center text-gray-600 text-3xl bg-no-repeat">
                    <h3 class="">Add an Image/Video</h3>
                    <input type="file" name="cover_image" id="post_input" accept="image/*,video/*" class="absolute top-0 bottom-0 right-0 left-0 opacity-0 rounded-lg cursor-pointer z-10" multiple>
                    <div class="absolute top-0 bottom-0 right-0 left-0 rounded-lg text-3xl text-gray-50 opacity-0 bg-gray-600 bg-opacity-50 flex justify-center z-5 items-center camera_div"><i class="fa fa-plus"></i></div>
                </div>
                <input type="text" name="caption" id="caption" class="p-3 w-5/6 sm:w-5/6 m-5 mt-2 md:w-1/3 min-w-1//5 bg-gray-200" placeholder="Add a caption..."/>
                <div id="post_images_div" class="w-5/6 flex flex-wrap justify-evenly items-start m-auto p-2">
                    {{-- display post images --}}
                </div>

                <button class="py-2 h-10 px-5 bg-blue-600 mb-5 m-auto rounded-lg shadow-md cursor-pointer flex flex-row text-gray-50 hover:bg-blue-700" id='post-btn' type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<style>
    .change_dp:hover .camera_div{
        opacity: 1;
    }
</style>
<script>

    document.querySelectorAll('.close-dialog').forEach(element => {
        element.addEventListener('click', () => {
            element.parentElement.parentElement.parentElement.style.display = 'none';
        });
    }); 

    function toggleMakePost(){
        document.getElementById('make-post-div').style.display = 'flex';
    };

    let postImages = [];

    function removeFile(name, event){
        let index = postImages.findIndex(fileObj => fileObj.name === name)

        postImages.splice(index, 1);
        event.parentElement.style.display = 'none';

        console.log(postImages);
    }

    document.getElementById('post_input').onchange = () => {
        let arr = document.getElementById('post_input').files;

        try {
            for (let index = 0; index < arr.length; index++) {
                const file = arr[index];
                if (file.size < 1) {
                    throw new Error("Invalid file.");
                }
                if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
                    throw new Error("Ensure to upload an image file.");
                }

                if(file.type.startsWith('image/')){
                    document.getElementById('post_images_div').innerHTML = document.getElementById('post_images_div').innerHTML + 
                    `<div class="w-5/6 md:w-1/3 rounded-md relative h-32 border border-gray-200 bg-center bg-cover bg-no-repeat m-1" style="background-image: url('${URL.createObjectURL(file)}')">
                        <button type='button' class="h-11 w-11 bg-transparent rounded-full cursor-pointer text-red-600 text-2xl hover:bg-gray-300 hover:bg-opacity-50" onclick="removeFile('${file.name}', this)"><i class="fa fa-archive"></i></button>
                    </div>`;
                }
                else if(file.type.startsWith('video/')){
                    document.getElementById('post_images_div').innerHTML = document.getElementById('post_images_div').innerHTML + 
                    `<div class="w-5/6 md:w-1/3 rounded-md relative h-32 border border-gray-200 bg-center bg-cover bg-no-repeat m-1">
                        <video src="${URL.createObjectURL(file)}" class="h-full w-full max-h-full max-w-full absolute top-0 bottom-0 right-0 left-0 object-cover rounded-md" autoplay loop muted></video>
                        <button type='button' class="h-11 w-11 bg-transparent rounded-full cursor-pointer text-red-600 text-2xl hover:bg-gray-300 hover:bg-opacity-50 z-50 fixed top-1 left-1 absolute" onclick="removeFile('${file.name}', this)"><i class="fa fa-archive"></i></button>
                    </div>`;
                }

                postImages.push(file);
            }
        } catch (error) {
            toastr.error(error)
        }

        document.getElementById('post_input').value = null;
    }

    document.getElementById('make-post-form').onsubmit = async (event) => {
        event.preventDefault();
        let btn = document.getElementById('post-btn');
        btn.innerHTML = `
        <svg fill="currentColor" class="animate-spin text-primary mr-1 w-7 h-7" aria-label="Loading..." aria-hidden="true" data-testid="icon" width="16" height="17" viewBox="0 0 16 17" xmlns="http://www.w3.org/2000/svg"><path d="M4.99787 2.74907C5.92398 2.26781 6.95232 2.01691 7.99583 2.01758V3.01758C7.10643 3.01768 6.23035 3.23389 5.44287 3.64765C4.6542 4.06203 3.97808 4.66213 3.47279 5.39621C2.9675 6.13029 2.64821 6.97632 2.54245 7.86138C2.51651 8.07844 2.5036 8.29625 2.50359 8.51367H1.49585C1.49602 8.23118 1.51459 7.94821 1.55177 7.66654C1.68858 6.62997 2.07326 5.64172 2.67319 4.78565C3.27311 3.92958 4.07056 3.23096 4.99787 2.74907Z"></path><path opacity="0.15" fill-rule="evenodd" clip-rule="evenodd" d="M8 14.0137C11.0376 14.0137 13.5 11.5512 13.5 8.51367C13.5 5.47611 11.0376 3.01367 8 3.01367C4.96243 3.01367 2.5 5.47611 2.5 8.51367C2.5 11.5512 4.96243 14.0137 8 14.0137ZM8 15.0137C11.5899 15.0137 14.5 12.1035 14.5 8.51367C14.5 4.92382 11.5899 2.01367 8 2.01367C4.41015 2.01367 1.5 4.92382 1.5 8.51367C1.5 12.1035 4.41015 15.0137 8 15.0137Z"></path></svg>&nbsp;Posting`;
        btn.disabled = true;
        btn.style.backgroundColor = 'rgb(191, 219, 254)';
        btn.style.cursor = 'not-allowed';
        try{
            if(document.getElementById('caption').value.trim().length < 1)
                throw new Error('Caption is required.');
            let formData = new FormData(document.getElementById('make-post-form'));
            postImages.forEach((file, index) => {
                formData.append(`files[]`, file);
            });
            const response = await fetch("/api/make-post", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token-save-data"]').attr('content')
                }
            });

            if (response.ok) {
                console.log(response);
                postImages = [];
                let res = await response.json();
                window.location.href = res.url;
                document.getElementById('make-post-div').style.display = 'none';
                toastr.info('post successful');
                document.getElementById('post_images_div').innerHTML = '';
                document.getElementById('post_input').value = '';
            } else {
                let res = await response.json();
                toastr.error('Error connecting to database.')
                console.error(res);
            }
        }
        catch(error){
            toastr.error(error);
            console.error(error)
        }
        finally{
            btn.disabled = false;
            btn.style.backgroundColor = 'rgb(59 130 246)';
            btn.style.cursor = 'pointer';
            btn.innerHTML = 'Post';
        }
    }
</script>