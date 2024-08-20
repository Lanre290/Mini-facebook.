<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Http\Response;
use App\Models\Users;
use App\Models\Followers;
use App\Models\Post;
use App\Models\blocked;
use App\Models\PostFiles;
use App\Models\Likes;
use App\Models\SavedPost;
use App\Models\Comments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

date_default_timezone_set('Africa/Lagos');

class Views extends Controller{

    public function profile($id){
        if(null !== session('user')){
            $exists = Users::where('id', $id)->count();
            if($exists > 0){
                $data = Users::where('id', $id)->select('id','name', 'email', 'bio', 'image_path','cover_img_path')->first();
                $followers = Followers::where('following',$id)
                                ->count();
                $following = Followers::where('follower',$id)
                                ->count();
                if($data->image_path == ''){
                    $data->image_path = asset('img/users_dp/default.png');
                }
                else{
                    $data->image_path = asset($data->image_path);
                }
                if($data->cover_img_path !== ''){
                    $data->cover_img_path = asset($data->cover_img_path);
                }
                
                $data->followers = $followers;
                $data->following = $following;
                $data->is_following = false;
                $data->id = $id;

                if($id !== session('user')->id){
                    $is_following = Followers::where('following', $id)->where('follower', session('user')->id)->count();
                    $data->is_following = $is_following > 0;
                }

                $posts = Post::where('user',$id)->orderBy('timestamp', 'DESC')->get();

                $followers = Followers::where('following', $id)->get();
                for($i = 0; $i < count($followers); $i++){
                    $follower = $followers[$i];
                    $user = Users::where('id', $follower->follower)->select('id','name', 'email', 'bio', 'image_path', 'cover_img_path')->first();
                    $user->followers = Followers::where('following', $follower->follower)->count();

                    $following = Followers::where('following', $follower->follower)->where('follower', $id)->count();
                    $user->is_following = $following > 0;


                    if($user->image_path == ''){
                        $user->image_path = asset('img/users_dp/person.png');
                    }

                    $followers[$i] = $user;
                }

                $posts = $this->validatePost($posts);

                return view('index.profile')->with(['data' => $data, 'posts' => $posts, 'followers' => $followers]);
            }
            else{
                return view('404.index');
            }
        }
        else{
            return redirect(route('login'));
        }
    }

    public function people(){
        if(null !== session('user')){
            $people = Users::where('id','!=', session('user')->id)->get();
            $count = 0;

            foreach ($people as $person) {
                $isFollowing = Followers::where('following',$person->id)
                            ->where('follower', session('user')->id)
                            ->count();
                $isBlocked = $this->isBlocked($person->id);
                if($isBlocked > 0){
                    unset($people[$count]);
                }
                if($person->image_path == ''){
                    $person->image_path = asset('img/users_dp/person.png');
                }
                $followers = Followers::where('following',$person->id)
                            ->count();
                if ($isFollowing > 0) {
                    $person->is_following = true;
                }
                $person->followers = $followers;
                $count++;
            }
            
            return view('index.people')->with(['data' => $people]);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function saved(){
        if(null !== session('user')){
            $savedPostIds = SavedPost::where('user', session('user')->id)->pluck('post');
            $posts = Post::whereIn('id', $savedPostIds)->orderBy('timestamp', 'DESC')->get();


            $posts = $this->validatePost($posts);

            return view('index.saved')->with(['data' => $posts]);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function isBlocked($id){
        if(null !== session('user')){
            $isBlocked = blocked::where('blocked', $id)
                                ->where('blocker', session('user')->id)
                                ->count();
            return $isBlocked > 0;
        }
        else{
            return redirect(route('login'));
        }
    }

    public function post($id){
        $exists = Post::where('id', $id)->count();
        if($exists > 0){
            $post = Post::where('id', $id)->get();
            $post = $this->validatePost($post);


            $comments = Comments::where('post', $id)->get();
            $comments = $this->validateComments($comments, $post[0]->user->id);


            return view('index.post')->with(['post' => $post[0], 'comments' => $comments]);
        }
        else{
            return view('404.index');
        }
    }

    public function home(){
        if(null !== session('user')){
            $posts = Post::where('user', '!=', session('user')->id)->orderBy('timestamp', 'DESC')->get();
            

            $posts = $this->validatePost($posts);

            return view('index.home')->with(['data' => $posts]);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function search(Request $request,  $term = ''){
    
        $searchTerm = $term ? $term : $request->term;
    
        $postResult = Post::where('text', 'LIKE', "%{$searchTerm}%")
                        ->get();

        $userResult = Users::where('id', '!=', session('user')->id)
                        ->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('bio', 'LIKE', "%{$searchTerm}%")
                        ->select('id','name', 'email', 'bio', 'image_path')
                        ->get();
        
        $postResult = $this->validatePost($postResult);

        $count = 0;
        foreach ($userResult as $person) {
            $isFollowing = Followers::where('following',$person->id)
                        ->where('follower', session('user')->id)
                        ->count();
            $isBlocked = $this->isBlocked($person->id);
            if($isBlocked > 0){
                unset($people[$count]);
            }
            if($person->image_path == ''){
                $person->image_path = asset('img/users_dp/person.png');
            }
            $followers = Followers::where('following',$person->id)
                        ->count();
            if ($isFollowing > 0) {
                $person->is_following = true;
            }
            $person->followers = $followers;
            $count++;
        }
        
        if(count($postResult) > 0 || count($userResult) >0){
            return view('index.search')->with([
                'posts' => $postResult,
                'user' => $userResult
            ]);
        }
        else{
            return view('index.noresult')->with([
                'data' => $searchTerm,
            ]);
        }
    }


    public function validatePost($posts){
        foreach($posts as $post){
            $files = PostFiles::where('post_id', $post->id)->get();
            $likes = Likes::where('post', $post->id)->count();
            $comment_count = Comments::where('post', $post->id)->count();
            $comments = Comments::where('post', $post->id)->get();
            $isLiked = Likes::where('user', session('user')->id)->where('post', $post->id)->count();
            $isSaved = SavedPost::where('user', session('user')->id)->where('post', $post->id)->count();
            $date = $post->day .' '. $post->month .' '. $post->year;

            $months = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $year = date("Y");
            $month = (int)date("m");
            $day = date("d");
            $hour = date('H');
            $minute = date('i');
            $month = $months[$month];
            $todays_date = $day .' '. $month .' '. $year;


            $post->files = $files;
            $post->date = $date;
            $post->likes = $likes;
            $post->comment_count = $comment_count;
            $post->comments = $comments;
            $post->isLiked = $isLiked > 0;
            $post->isSaved = $isSaved > 0;
            $user = Users::where('id', $post->user)->select('id','name', 'email', 'bio', 'image_path')->first();
            $post->user = $user;
            if($post->user->image_path == ''){
                $post->user->image_path = asset('img/users_dp/default.png');
            }
            else{
                $post->user->image_path = asset($post->user->image_path);
            }

            if($year == $post->year && $month == $post->month && $day == $post->day && $hour != $post->hour && $minute != $post->minute && ($hour - $post->hour) > 12){
                $post->date = 'Today at '. $post->hour.':'.$post->minute;
            }
            else if($year == $post->year && $month == $post->month && $day != $post->day && $hour != $post->hour && $minute != $post->minute && ($day - $post->day) > 1){
                $post->date = ($day - $post->day). ' days ago.';
            }
            else if($year == $post->year && $month == $post->month && $day != $post->day && $hour != $post->hour && $minute != $post->minute && ($day - $post->day) == 1){
                $post->date = 'Yesterday at '. $post->hour.':'.$post->minute;
            }
            else if($year == $post->year && $month == $post->month && $day == $post->day && $hour != $post->hour){
                $post->date = ($hour - $post->hour). ' hours ago.';
            }
            else if($year == $post->year && $month == $post->month && $day == $post->day && $hour == $post->hour && $minute != $post->minute){
                $post->date = ($minute - $post->minute). ' minutes ago.';
            }

            else if($year == $post->year && $month == $post->month && $day == $post->day && $hour == $post->hour && $minute == $post->minute){
                $post->date = 'Just now';
            }
            else if($year != $post->year){
                $post->date = ($year - $post->year). ' years ago.';
            }

        }

        return $posts;
    }

    public function validateComments($comments, $creator){
        foreach($comments as $comment){
            $date = $comment->day .' '. $comment->month .' '. $comment->year;

            $months = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $year = date("Y");
            $month = (int)date("m");
            $day = date("d");
            $hour = date('H');
            $minute = date('i');
            $month = $months[$month];
            $todays_date = $day .' '. $month .' '. $year;

            $comment->date = $date;
            $user = Users::where('id', $comment->user)->select('id','name', 'email', 'bio', 'image_path')->first();
            $comment->user = $user;
            if($comment->user->image_path == ''){
                $comment->user->image_path = asset('img/users_dp/default.png');
            }
            else{
                $comment->user->image_path = asset($comment->user->image_path);
            }

            if($year == $comment->year && $month == $comment->month && $day == $comment->day && $hour != $comment->hour && $minute != $comment->minute && ($hour - $comment->hour) > 12){
                $comment->date = 'Today at '. $comment->hour.':'.$comment->minute;
            }
            else if($year == $comment->year && $month == $comment->month && $day != $comment->day && $hour != $comment->hour && $minute != $comment->minute && ($day - $comment->day) > 1){
                $comment->date = ($day - $comment->day). ' days ago.';
            }
            else if($year == $comment->year && $month == $comment->month && $day != $comment->day && $hour != $comment->hour && $minute != $comment->minute && ($day - $comment->day) == 1){
                $comment->date = 'Yesterday at '. $comment->hour.':'.$comment->minute;
            }
            else if($year == $comment->year && $month == $comment->month && $day == $comment->day && $hour != $comment->hour){
                $comment->date = ($hour - $comment->hour). ' hours ago.';
            }
            else if($year == $comment->year && $month == $comment->month && $day == $comment->day && $hour == $comment->hour && $minute != $comment->minute){
                $comment->date = ($minute - $comment->minute). ' minutes ago.';
            }

            else if($year == $comment->year && $month == $comment->month && $day == $comment->day && $hour == $comment->hour && $minute == $comment->minute){
                $comment->date = 'Just now';
            }
            else if($year != $comment->year){
                $comment->date = ($year - $comment->year). ' years ago.';
            }

            $comment->by_user = false;
            $comment->tk = '';
            if($comment->user->id == session('user')->id){
                $comment->by_user = true;
                $comment->tk = csrf_token();
            }
            if($comment->user->id == $creator){
                $comment->by_creator = true;
            }

        }

        return $comments;
    }

    public function messages(){
        return view('index.message');
    }
}
