
async function follow(event,id){
    event.disabled = true;
    event.style.backgroundColor = 'rgb(191, 219, 254)';
    event.style.cursor = 'not-allowed';
    var formData = new FormData();
    formData.append('id', id);
    const response = await fetch("/api/follow", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    if (response.ok) {
        let res = await response.json();
        event.removeAttribute('disabled');
        event.innerText = 'Unfollow';
        event.class = ('bg-red-500 rounded-md h-8 m-auto text-gray-50 shadow-md cursor-pointer w-4/6 hover:bg-red-600 disabled:bg-red-200');
        event.onclick = () => {
            unFollow(event, event.dataset.id);
        }
        event.style.backgroundColor = 'rgb(220 38 38)';
        event.style.cursor = 'pointer';
        
        if(event.classList.contains('profile-page')){
            console.log(event.nextElementSibling);
            event.nextElementSibling.querySelector('#followers').innerHTML = `<Span class="font-bold">${res.data}</span> followers`
        }
        else{
            event.parentElement.parentElement.getElementsByTagName('h3')[1].innerText = `${res.data} Followers`;
        }
        toastr.info('Account followed successfully.');
    } else {
        let res = await response.json();
        console.log(res);
        toastr.error('Error connecting to database.')
    }
}

async function unFollow(event,id){
    event.disabled = true;
    event.style.backgroundColor = 'rgb(254 202 202)';
    event.style.cursor = 'not-allowed';
    var formData = new FormData();
    formData.append('id', id);
    const response = await fetch("/api/unfollow", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.onclick = () => {
        follow(event, event.dataset.id);
    }

    if (response.ok) {
        let res = await response.json();
        event.removeAttribute('disabled');
        event.innerText = 'Follow';
        event.class = ('bg-blue-600 rounded-md h-8 m-auto text-gray-50 shadow-md cursor-pointer w-4/6 hover:bg-blue-700 disabled:bg-blue-200');
        event.style.backgroundColor = 'rgb(29 78 216)';
        event.style.cursor = 'pointer';
        if(event.classList.contains('profile-page')){
            event.nextElementSibling.querySelector('#followers').innerHTML = `<Span class="font-bold">${res.data}</span> followers`
        }
        else{
            event.parentElement.parentElement.getElementsByTagName('h3')[1].innerText = `${res.data} Followers`;
        }
        toastr.error('Account unfollowed successfully.');
    } else {
        toastr.error('Error connecting to database.')
    }
}

async function block(event, id){
    var formData = new FormData();
    formData.append('id', id);
    const response = await fetch("/api/block", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.onclick = () => {
        follow(event, event.dataset.id);
    }

    if (response.ok) {
        event.parentElement.parentElement.parentElement.parentElement.style.display = 'none';
        toastr.info('Account blocked successfully.');
    } else {
        toastr.error('Error connecting to database.')
    }
}

async function like(event){
    if(event.dataset.liked == true){
        event.firstElementChild.classList.remove('fa');
        event.firstElementChild.classList.add('far');
        event.classList.remove('text-red-700');
        event.dataset.liked = false;
    }
    else{
        event.firstElementChild.classList.remove('far');
        event.firstElementChild.classList.add('fa');
        event.classList.add('text-red-700');
        event.dataset.liked = true;
    }
    let id = event.dataset.id;

    var formData = new FormData();
    formData.append('id', id);
    const response = await fetch("/api/like-post", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="like-token"]').value
        }
    });


    if (response.ok) {
        let res = await response.json();
        event.nextElementSibling.nextElementSibling.innerText = `${res.data} Likes`;
        if(res.isLiked == true){
            event.firstElementChild.classList.remove('far');
            event.firstElementChild.classList.add('fa');
            event.classList.add('text-red-700');
            event.dataset.liked = true;
        }
        else{
            event.firstElementChild.classList.remove('fa');
            event.firstElementChild.classList.add('far');
            event.classList.remove('text-red-700');
            event.dataset.liked = false;
        }
    } else {
        toastr.error('You seem to be offline.');
    }
}

async function save(event){
    if(event.dataset.saved == true){
        event.firstElementChild.classList.remove('fa');
        event.firstElementChild.classList.add('far');
        event.classList.remove('text-red-700');
        event.dataset.saved = false;
    }
    else{
        event.firstElementChild.classList.remove('far');
        event.firstElementChild.classList.add('fa');
        event.classList.add('text-red-700');
        event.dataset.saved = true;
    }
    let id = event.dataset.id;

    var formData = new FormData();
    formData.append('id', id);
    const response = await fetch("/api/save-post", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="save-token"]').value
        }
    });


    if (response.ok) {
        let res = await response.json();
        if(res.isSaved == true){
            event.firstElementChild.classList.remove('far');
            event.firstElementChild.classList.add('fa');
            event.classList.add('text-red-700');
            event.dataset.saved = true;
            toastr.info('Post Saved.');
        }
        else{
            event.firstElementChild.classList.remove('fa');
            event.firstElementChild.classList.add('far');
            event.classList.remove('text-red-700');
            event.dataset.saved = false;
            toastr.error('Post Unsaved.');
        }
    }
    else{
        toastr.error('You seem to be offline.');
    }
}

function playVideo(element, event){
    event.stopPropagation();
    let VideoElement =element.nextElementSibling;
    let button = element;
    if(element.classList.contains('video-cont')){
        VideoElement = element.querySelector('video');
        button = element.querySelector('#play-btn');
    }
    if(VideoElement.paused || VideoElement.ended){
        VideoElement.play();
        VideoElement.onended = () => {
            button.innerHTML = '<i class="fa fa-play"></i>';
        }
        button.innerHTML = '<i class="fa fa-pause"></i>';
    }
    else{
        VideoElement.pause();
        button.innerHTML = '<i class="fa fa-play"></i>';
    }
}

function progressPostVideo(element, event){
    event.stopPropagation();
    let currentTime = element.currentTime;
    let duration = element.duration;

    let slide = element.nextElementSibling.querySelector('#playback');
    slide.value = (currentTime/duration) * 100; 
}

function seekPostVideo(element, event){
    event.stopPropagation();
    let videoEl = element.parentElement.previousElementSibling;

    videoEl.currentTime = (element.value/100) * videoEl.duration;
}

function mutePostVideo(element, event){
    event.stopPropagation();
    let videoEl = element.parentElement.parentElement.previousElementSibling;
    
    if(videoEl.muted){
        videoEl.muted = false;
        element.innerHTML = '<i class="fa fa-volume-up"></i>';
    }
    else{
        videoEl.muted = true;
        element.innerHTML = '<i class="fa fa-volume-down"></i>';
    }
}

function managePostVolume(element, event){
    event.stopPropagation();
    let videoEl = element.parentElement.parentElement.previousElementSibling;

    videoEl.volume = (element.value)/100;

}

function validateCommentButton(element){
    if(element.value.trim().length < 1){
        element.nextElementSibling.disabled = true
        element.nextElementSibling.classList.add('text-gray-600');
        element.nextElementSibling.classList.remove('text-blue-600');
        element.nextElementSibling.style.cursor = 'not-allowed';
    }
    else{
        element.nextElementSibling.disabled = false
        element.nextElementSibling.classList.add('text-blue-600');
        element.nextElementSibling.classList.remove('text-gray-600');
        element.nextElementSibling.style.cursor = 'pointer';
    }
}

async function submitPostComment(element, event){
    event.preventDefault();

    var formData = new FormData(element);
    formData.append('post', element.querySelector('input[name="text"]').dataset.id)
    const response = await fetch("/api/comment", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="comment-token"]').value
        }
    });


    if (response.ok) {
        console.log(response);
        let res = await response.json();
        if(res.data == true){
            toastr.info('comment saved');
            element.parentElement.previousElementSibling.querySelector('#comment-text').innerText = `${res.comments} Comments`;
            element.querySelector('input[name="text"]').value = '';
            element.querySelector('input[name="text"]').blur();

            if(element.classList.contains('real-post-comment')){
                if(res.post_comments.length > 0){
                    element.parentElement.parentElement.parentElement.previousElementSibling.style.alignItems = 'flex-start';
                    element.parentElement.parentElement.parentElement.previousElementSibling.style.justifyContent = 'flex-start';
                    element.parentElement.parentElement.parentElement.previousElementSibling.innerHTML = '';
                    res.post_comments.forEach(comment => {
                        element.parentElement.parentElement.parentElement.previousElementSibling.innerHTML = element.parentElement.parentElement.parentElement.previousElementSibling.innerHTML +
                        `
                            <div class="flex-row flex w-full p-3">
                                <a href="/profile/${comment.user.id}" class="h-12 w-12 min-h-12 min-w-12 flex rounded-full cursor-pointer bg-gray-400 mr-3 bg-center bg-cover bg-no-repeat" style="background-image: url('${comment.user.image_path}')"></a>
                                <div class="flex flex-col w-full">
                                    <div class="bg-gray-300 rounded-lg p-3 flex-col text-clip w-fit" style="max-width: calc(96% - 48px);width: fit-content;">
                                        <a href="/profile/${comment.user.id}" class="text-gray-600 text-xs hover:text-blue-600 hover:underline">${comment.user.name}</a>
                                        <h3 class="text-gray-600">${comment.text}</h3>
                                    </div>
                                    ${comment.by_user == true ? `<input type="hidden" name="delete-comment-token" value="${comment.tk}">` : ''}
                                    <h3 class="text-gray-600 text-xs w-fit ml-3 cursor-pointer hover:text-blue-600 hover:underline" data-id="${comment.id}" onclick="deleteComment(this)">Delete</h3>
                                </div>        
                            </div>
                        `;
                    });
                    element.parentElement.parentElement.parentElement.previousElementSibling.innerHTML.scrollBy({left: '', top: 999999999999999, behavior: 'smooth'});
                }
                else{
                    element.parentElement.parentElement.parentElement.previousElementSibling.innerHTML = 
                    `
                    <img src="{{ asset('img/coment.png') }}" alt="no-comment" class="w-32 h-32 object-contain mx-auto" />
                    <h3 class="text-gray-600 mx-auto">No Comments on this post</h3>`;
                }
            }
        }
        else{
            toastr.error('Error.')
        }
    } else {
        console.error(await response.json());
        toastr.error('You seem to be offline.');
    }
}

function showPostOption(element){
    let div = element.parentElement.querySelector('div');
    if(div.style.display == 'none'){
        div.style.display = 'flex';
    }
    else{
        div.style.display = 'none';
    }
}

async function deletePost(element, id){
    var formData = new FormData();
    console.log(id);
    formData.append('id', id)
    const response = await fetch("/api/delete-post", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="comment-token"]').value
        }
    });


    if (response.ok) {
        let res = await response.json();
        if(res.data == true){
            toastr.info('Post deleted successfully.');
            
            if(element.classList.contains('main-post')){
                window.location.replace('/home');
            }
            else{
                element.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.style.display = 'none'; 
            }
        }
        else{
            toastr.error(res.data);
        }
    } else {
        console.error(await response.json());
        toastr.error('You seem to be offline.');
    }
}

async function deleteComment(element){
    let id = element.dataset.id;

    var formData = new FormData();
    formData.append('id', id)
    const response = await fetch("/api/delete-comment", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="delete-comment-token"]').value
        }
    });


    if (response.ok) {
        let res = await response.json();
        if(res.data == true){
            toastr.info('Comment deleted successfully.');
            element.parentElement.parentElement.style.display = 'none';
        }
        else{
            toastr.error(res.data);
        }
    } else {
        console.error(await response.json());
        toastr.error('You seem to be offline.');
    }    
}

async function saveUserChanges(elem, event) {
    event.preventDefault();
    
    let btn = document.getElementById('edit-profile-btn');
    btn.innerHTML = `<svg fill="currentColor" class="animate-spin text-primary mr-1 w-7 h-7" aria-label="Loading..." aria-hidden="true" data-testid="icon" width="16" height="17" viewBox="0 0 16 17" xmlns="http://www.w3.org/2000/svg"><path d="M4.99787 2.74907C5.92398 2.26781 6.95232 2.01691 7.99583 2.01758V3.01758C7.10643 3.01768 6.23035 3.23389 5.44287 3.64765C4.6542 4.06203 3.97808 4.66213 3.47279 5.39621C2.9675 6.13029 2.64821 6.97632 2.54245 7.86138C2.51651 8.07844 2.5036 8.29625 2.50359 8.51367H1.49585C1.49602 8.23118 1.51459 7.94821 1.55177 7.66654C1.68858 6.62997 2.07326 5.64172 2.67319 4.78565C3.27311 3.92958 4.07056 3.23096 4.99787 2.74907Z"></path><path opacity="0.15" fill-rule="evenodd" clip-rule="evenodd" d="M8 14.0137C11.0376 14.0137 13.5 11.5512 13.5 8.51367C13.5 5.47611 11.0376 3.01367 8 3.01367C4.96243 3.01367 2.5 5.47611 2.5 8.51367C2.5 11.5512 4.96243 14.0137 8 14.0137ZM8 15.0137C11.5899 15.0137 14.5 12.1035 14.5 8.51367C14.5 4.92382 11.5899 2.01367 8 2.01367C4.41015 2.01367 1.5 4.92382 1.5 8.51367C1.5 12.1035 4.41015 15.0137 8 15.0137Z"></path></svg>&nbsp;Loading...`;
    btn.disabled = true;
    btn.style.backgroundColor = 'rgb(191, 219, 254)';
    btn.style.cursor = 'not-allowed';

    let formData = new FormData(elem);

  
        const response = await fetch("/api/save-user-changes", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': elem.querySelector('input[name="_token"]').value
            }
        });

        if (response.ok) {
            toastr.info('Account updated successfully.');

            document.getElementById('user-name-text').innerText = formData.get('name');
            document.getElementById('user-bio-text').innerText = formData.get('bio');
            document.getElementById('edit-profile-div').style.display = 'none';

            async function getImage(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = e => resolve(e.target.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            }

            const coverImage = formData.get('cover_image');
            const userImage = formData.get('image');
            
            if (coverImage.name != '') {
                const coverImageUrl = await getImage(coverImage);
                coverImageUrl != '' ? document.getElementById('user-cover-img').style.backgroundImage = `url('${coverImageUrl}')` : '';
            }
            
            if (userImage.name != '') {
                const userImageUrl = await getImage(userImage);
                document.getElementById('user-dp-img').style.backgroundImage = `url('${userImageUrl}')`;
            }

            btn.disabled = false;
            btn.style.backgroundColor = 'rgb(29 78 216)';
            btn.style.cursor = 'default';
            btn.innerHTML = 'Submit';
        } else {
            console.log(response);
            let res = await response.json();
            console.error('Error:', res);
            toastr.error('Error connecting to database.');
            btn.disabled = false;
            btn.style.backgroundColor = 'rgb(29 78 216)';
            btn.style.cursor = 'default';
            btn.innerHTML = 'Submit';
        }
    
}

function copyPostUrl(element){
    let id = element.dataset.rel;

    if(navigator.clipboard.writeText(id)){
        toastr.info('Link Copied.');
        element.parentElement.style.display = 'none';
    }
}

function putVideoFullScreen(element){
    if(element.dataset.type == 'normal'){
        element.dataset.type = 'fullscreen';
        element.innerHTML = '<i class="fa fa-compress"></i>';
        element.parentElement.parentElement.requestFullscreen({navigationUI: 'hide'});
        document.onfullscreenchange = () => {
            if (!document.fullscreenElement) {
                element.dataset.type = 'normal';
                element.innerHTML = '<i class="fa fa-expand"></i>';
                document.exitFullscreen();
            }
        }
    }
    else{
        element.dataset.type = 'normal';
        element.innerHTML = '<i class="fa fa-expand"></i>';
        document.exitFullscreen();
   }
}


function showControl(element){
    element.querySelector('#play-btn').style.display = 'block';
    element.querySelector('#controls').style.display = 'flex';
    clearInterval(element.dataset.interval);
    let interval = setTimeout(() => {
        element.querySelector('#play-btn').style.display = 'none';
        element.querySelector('#controls').style.display = 'none';
    }, 2000);

    element.dataset.interval = interval;
}

function hidePost(element){
    if(element.parentElement.parentElement.parentElement.parentElement.style.display = 'none'){
        toastr.info('Post Hidden.')
    }
}

function logout(){
    fetch("/logout", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    window.location.href = "{{ route('login') }}"
}


function toggleUserOptions(element){
    let target = element.parentElement.querySelector('#options');
    if(target.style.display == 'none'){
        target.style.display = 'flex';
    }
    else{
        target.style.display = 'none';
    }
}