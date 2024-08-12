<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="w-0 sm:w-0 md:w-0 lg:w-3/12 hidden z-10 py-10 sm:hidden md:hidden lg:flex flex-col h-full justify-between">
    <ul class="w-full">
            <li class="w-5/6 rounded-3xl text-center cursor-pointer bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center mx-auto" id='home_nav'>
                <a href='{{ route('home')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-center'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/3d-house.png') }}')"></div>
                    Home
                </a>
            </li>
            <li class="w-5/6 rounded-3xl text-center cursor-pointer bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center mx-auto" id='profile_nav'>
                <a href='{{ route('profile', ['id' => $id]) }}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-center'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/user.png') }}')"></div>
                    Profile
                </a>
            </li>
            <li class="w-5/6 rounded-3xl text-center cursor-pointer bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center mx-auto" id='people_nav'>
                <a href='{{ route('people')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-center'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/social.png') }}')"></div>
                    People
                </a>
            </li>
            <li class="w-5/6 rounded-3xl text-center cursor-pointer bg-transparent hover:bg-gray-400 m-2 hover:bg-opacity-50 text-gray-700 text-2xl flex items-center justify-center mx-auto" id='saved_nav'>
                <a href='{{ route('saved')}}' class='w-full h-full py-3 px-6 flex flex-row items-center justify-center'>
                    <div class="bg-center bg-no-repeat bg-cover w-10 h-10 mx-4" style="background-image: url('{{ asset('img/bookmark.png') }}')"></div>
                    Saved
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
        @endif
    </ul>

    <div class="flex flex-col justify-center items-center w-full">
        <button class="bg-blue-500 py-3 px-6 rounded-xl cursor-pointer text-2xl text-gray-50 font-bold w-5/6 mb-5 m-auto hover:bg-blue-600" id="make-post-btn">Create</button>
        <a class="text-red-600 font-bold text-2xl mx-auto cursor-pointer mt-5 m-3" id='logout'>Logout</a>
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
    document.getElementById('logout').addEventListener('click', async () => {
        fetch("/logout", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        window.location.href = "{{ route('login') }}"
    });

    document.querySelectorAll('.close-dialog').forEach(element => {
        element.addEventListener('click', () => {
            element.parentElement.parentElement.parentElement.style.display = 'none';
        });
    }); 

    document.getElementById('make-post-btn').addEventListener('click', () => {
        document.getElementById('make-post-div').style.display = 'flex';
    });

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
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3V4a12 12 0 100 24v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
        </svg>&nbsp;Posting`;
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
                postImages = [];
                let res = await response.json();
                window.location.href = res.url;
                document.getElementById('make-post-div').style.display = 'none';
                toastr.info('post successful');
                document.getElementById('post_images_div').innerHTML = '';
                document.getElementById('post_input').value = '';
            } else {
                toastr.error('Error connecting to database.')
                console.error(await response);
                console.error(await response.json())
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