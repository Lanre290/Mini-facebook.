
@include('includes.session')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="w-full sm:w-full md:w-full lg:w-9/12 z-10flex flex-col h-full justify-start bg-cover bg-center bg-no-repeat overflow-y-auto pb-5">
    <div class="flex flex-col">
        <div class="w-full h-1/3 py-36  rounded-bl-xl rounded-br-xl bg-gray-400 relative bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset(session('user')->cover_img_path) }}')" id="user-cover-img">
            <div class="w-36 h-36 rounded-full border border-gray-50 bg-gray-300 absolute bottom-0 left-4 bg-cover bg-center bg-no-repeat" id="user-dp-img" style="transform: translateY(50%);background-image: url('{{ asset(session('user')->image_path) }}')">
            </div>
        </div>
        <div class="flex flex-col md:flex-row align-center justify-between mt-16 w-fullz">
            <h1 class="text-5xl text-gray-800 ml-3 p-2 bg-transparent mb-3" id='user-name-text'>{{ session('user')->name }}</h1>
            <div class="flex flex-row">
                <button class="w-5/6 md:w-auto py-2 h-10 px-5 bg-blue-600 mr-5 m-auto rounded-lg shadow-md cursor-pointer text-gray-50 hover:bg-blue-700" onclick="toggleMakePost()" id="make-post-btn"><i class="fa fa-plus"></i></button>
                <button class="w-5/6 md:w-auto py-2 h-10 px-5 bg-blue-600 mr-5 m-auto rounded-lg shadow-md cursor-pointer text-gray-50 hover:bg-blue-700" id='edit-account'>Edit Profile</button>
                <div class="flex flex-col relative w-10 h-10 mr-5 m-auto">
                    <button class="w-10 h-10 rounded-md cursor-pointer px-5 py-2 mr-5 m-auto bg-gray-200 hover:bg-gray-300 hover:bg-opacity-50 text-gray-50 flex justify-center items-center text-lg" onclick="toggleUserOptions(this)"><i class="fa fa-ellipsis-h"></i></button>
                    <div class="absolute -bottom-12 w-36 right-4 bg-gray-50 shadow-lg flex-col" style="display: none;" id="options">
                        <button class="w-full cursor-pointer px-2 py-2 bg-transparent text-gray-500 hover:bg-gray-200 hover:bg-opacity-50" onclick="logout()"><i class="fa fa-sign-out text-red-600"></i>&nbsp;&nbsp;Log out</button>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="p-2 mb-3 ml-3 text-2xl italic" id='user-bio-text'>
            @if (session('user')->bio !== '')
                {{ session('user')->bio }}
            @endif
        </h3>
        <div class="flex flex-row ml-5">
            <div class="text-gray-800 mr-5">
                <Span class="font-bold"> {{ $data->followers }} </span> followers
            </div>
            <div class="text-gray-800">
                <Span class="font-bold"> {{ $data->following }} </Span> following
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-center border-t mt-5 border-gray-400 py-4 w-11/12">
        <ul class="flex flex-row">
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='posts-btn'>Posts</li>
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='followers-btn'>Followers</li>
            <li class="py-2 px-5 cursor-pointer rounded-lg m-1 hover:bg-gray-300 hover:bg-opacity-50 user-deets-btn" id='videos-btn'>Videos</li>
        </ul>
    </div>
    <div class="w-full z-10 py-10 flex flex-col h-full pb-5 justify-between" id='posts-cont'>
        @include('includes.posts',['data' => $posts])
    </div>

    <div class="w-full flex flex-wrap content-start items-start justify-center h-full min-h-screen user-posts-conts" id='videos-cont' style="display: none;">
        <div class="relative w-1/4 h-2/5 m-1 bg-center bg-cover bg-no-repeat cursor-pointer">
            <i class="fa fa-play text-3xl top-1 right-1 text-gray-50"></i>
            <video src="{{ asset('clips/clip_1.mp4') }}" class="h-full w-full max-h-full max-w-full absolute top-0 bottom-0 right-0 left-0 object-cover" autoplay loop muted></video>
            <div class="absolute bg-opacity-0 opacity-0 z-10 bg-gray-600 top-0 bottom-0 right-0 left-0 cursor-pointer hover:bg-opacity-50 hover:opacity-100 flex justify-center items-center">
                <div class="flex flex-row">
                    <span class="mr-2 text-gray-50 text-2xl">
                        <i class="fa fa-heart"></i>
                        200
                    </span>
                    <span class="mr-2 text-gray-50 text-2xl">
                        <i class="fa fa-comment"></i>
                        200
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full flex content-start items-start h-full min-h-screen user-posts-conts {{ count($followers) > 0 ? 'flex-wrap justify-center' : 'flex-col justify-start' }}" id='followers-cont' style="display: none;">
        @if (count($followers) > 0)
            @include('includes.people',['data' => $followers])
        @else
            <img src="{{ asset('img/nobody.png') }}" alt="no-comment" class="w-32 h-32 object-contain mx-auto" />
            <h3 class="text-gray-600 mx-auto">You have no followers. ☹️</h3>
        @endif
    </div>
</div>

<div class="fixed top-0 bottom-0 right-0 left-0 bg-gray-800 bg-opacity-50 justify-center items-center z-50 hidden" id="edit-profile-div">
    <div class="w-full h-full sm:w-full sm:h-full md:w-3/6 md:h-5/6 rounded-lg bg-gray-50">
        <div class="flex flex-row justify-between mt-5 w-full px-3">
            <i></i>
            <h3 class="text-gray-600 text-2xl">Edit your profile</h3>
            <button class="w-12 h-12 rounded-full cursor-pointer bg-transparent text-gray-600 hover:bg-gray-400 hover:bg-opacity-50 close-dialog" id="close-edit-profile">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="flex flex-col justify-start h-5/6 overflow-y-auto">
            <form action="/api/save-user-changes" method="post" class="flex flex-col justify-between h-full" id="edit-profile-form" onsubmit="saveUserChanges(this, event)">
                @csrf
                <div class="w-5/6 bg-gray-200 rounded-lg cursor-pointer relative m-auto h-36 change_dp bg-cover bg-center bg-no-repeat" id="ci_image" style="background-image: url('{{ asset(session('user')->cover_img_path) }}')">
                    <input type="file" name="cover_image" id="ci_input" class="absolute top-0 bottom-0 right-0 left-0 opacity-0 rounded-lg cursor-pointer z-10">
                    <div class="absolute top-0 bottom-0 right-0 left-0 rounded-lg text-3xl text-gray-50 opacity-0 bg-gray-600 bg-opacity-50 flex justify-center z-5 items-center camera_div"><i class="fa fa-camera"></i></div>
                </div>
                <div class="h-32 w-32 rounded-full bg-cover bg-no-repeat bg-center relative mt-3 m-auto change_dp" id='change_dp' style="background-image: url('{{ asset(session('user')->image_path) }}')">
                    <div class="absolute top-0 bottom-0 right-0 left-0 rounded-full text-3xl text-gray-50 opacity-0 bg-gray-600 bg-opacity-50 flex justify-center z-5 items-center camera_div" id="camera_div"><i class="fa fa-camera"></i></div>
                    <input type="file" name="image" id="dp_input" class="absolute top-0 bottom-0 right-0 left-0 opacity-0 rounded-full cursor-pointer z-10">
                </div>
                <div class="flex flex-col">
                    <h3 class="text-gray-600 ml-5">Name: </h3>
                    <input type="text" name="name" id="name" class="p-3 w-5/6 sm:w-5/6 m-5 mt-2 md:w-1/3 min-w-1//5 bg-gray-200" placeholder="Name..." value=" {{ session('user')->name }} "/>
                    <h3 class="text-gray-600 ml-5">Bio: </h3>
                    <input type="text" name="bio" id="bio" class="p-3 m-5 mt-2 w-5/6 sm:w-5/6 md:w-1/3 min-w-1//5 bg-gray-200" placeholder="Bio..." value=" {{ session('user')->bio }} "/>
                </div>

                <button class="py-2 h-10 px-5 bg-blue-600 mb-5 m-auto rounded-lg shadow-md cursor-pointer flex flex-row text-gray-50 hover:bg-blue-700" id='edit-profile-btn' type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>



<style>
    .active-place{
        border-bottom: 4px solid rgba(37, 99, 235);
    }
    .change_dp:hover .camera_div{
        opacity: 1;
    }
</style>
<script>
    document.querySelectorAll('.user-deets-btn')[0].classList.add('active-place')
    document.querySelectorAll('.user-deets-btn').forEach(element => {
        element.addEventListener('click', async () => {
            document.querySelectorAll('.user-deets-btn').forEach(elem => {
                elem.classList.remove('active-place');
            });
            document.querySelectorAll('.user-posts-conts').forEach(elem => {
                elem.style.display = 'none';
            });

            if(element.id == 'posts-btn'){
                document.getElementById('posts-cont').style.display = 'flex';
                document.getElementById('videos-cont').style.display = 'none';
                document.getElementById('followers-cont').style.display = 'none';
            }
            else if(element.id == 'videos-btn'){
                document.getElementById('videos-cont').style.display = 'flex';
                document.getElementById('followers-cont').style.display = 'none';
                document.getElementById('posts-cont').style.display = 'none';
            }
            else if(element.id == 'followers-btn'){
                document.getElementById('followers-cont').style.display = 'flex';
                document.getElementById('posts-cont').style.display = 'none';
                document.getElementById('videos-cont').style.display = 'none';
            }

            element.classList.add('active-place');
        });
    })


    document.querySelectorAll('.close-dialog').forEach(element => {
        element.addEventListener('click', () => {
            element.parentElement.parentElement.parentElement.style.display = 'none';
        }); 
    }); 

    document.getElementById('edit-account').addEventListener('click', () => {
        document.getElementById('edit-profile-div').style.display = 'flex';
    }); 
    document.getElementById('dp_input').onchange = (e) => {
        if(document.getElementById('dp_input').files[0] && document.getElementById('dp_input').files[0].type.startsWith('image/')){
            document.getElementById('change_dp').style.backgroundImage = `url('${URL.createObjectURL(document.getElementById('dp_input').files[0])}')`;
        }
    }
    document.getElementById('ci_input').onchange = (e) => {
        if(document.getElementById('ci_input').files[0] && document.getElementById('ci_input').files[0].type.startsWith('image/')){
            document.getElementById('ci_image').style.backgroundImage = `url('${URL.createObjectURL(document.getElementById('ci_input').files[0])}')`;
            console.log(document.getElementById('ci_image'));
        }
    }

    
</script>