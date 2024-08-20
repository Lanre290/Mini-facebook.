<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Views;
use App\Models\Followers;
use App\Models\blocked;
use App\Models\Users;
use App\Models\Post;
use App\Models\Likes;
use App\Models\PostFiles;
use App\Models\SavedPost;
use App\Models\Comments;
use App\Models\Messages;
use App\Models\deletedMessages;
use App\Models\LikedComment;


date_default_timezone_set('Africa/Lagos');

class UserActions extends Controller
{
    protected $Views;
    public function __construct(Views $Views){
        // Dependency inject for views controller
        $this->Views = $Views;
    }

    public function follow(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'integer|required'
            ]);
            $id = $request->id;
    
            Followers::create([
                'follower' => session('user')->id,
                'following' => $id,
            ]);
    
            $followers = Followers::where('following', $id)
                                ->count();
    
            return response()->json(['data' => $followers], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function unfollow(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'integer|required'
            ]);

            $id = $request->id;

            Followers::where('follower', session('user')->id)
                            ->where('following', $id)
                            ->delete();

            $followers = Followers::where('following', $id)
                            ->count();

            return response()->json(['data' => $followers], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function block(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'integer|required'
            ]);

            $id = $request->id;

            blocked::create([
                'blocker' => session('user')->id,
                'blocked' => $request->id,
            ]);

            return response(['data' => true], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function saveChanges(Request $request){
        try {
            if(null !== session('user')){
                $request->validate([
                    'name' => 'string|required',
                    'bio' => 'string|required',
                    'image' => 'mimes:jpeg,png,jpg|max:200000',
                    'cover_image' => 'mimes:jpeg,png,jpg|max:200000'
                ]);
    
                $name = $request->name;
                $bio = $request->bio;
                $id = session('user')->id;
                $image_path = '';
                $cover_image_path = '';
    
                $image = $request->file('image');
                $cover_image = $request->file('cover_image');
                $imageChanged = false;
                $cimageChanged = false;
    
    
                $imageName = Users::where('id', $id)->pluck('id')->first() . '.png';
                $coverImageName = Users::where('id', $id)->pluck('id')->first() . '.png';
                
                if($image !== null){
                    $image->move(public_path('img/users_dp'), $imageName);
                    $image_path = 'img/users_dp/' . $imageName;
                    $imageChanged = true;
                }

                if($cover_image!== null){
                    $cover_image->move(public_path('img/cover_images'), $coverImageName);
                    $cover_image_path = 'img/cover_images/' . $coverImageName;
                    $cimageChanged = true;
                }
    
                $update = Users::where('id', $id)->update([
                    'name' => $name,
                    'bio' => $bio,
                    'image_path' => $image_path,
                    'cover_img_path' => $cover_image_path
                ]);
    
                if($update){
                    session('user')->name = $name;
                    session('user') ->bio = $bio;
                    $cimageChanged == true ? session('user')->cover_img_path = $cover_image_path : '';
                    $imageChanged == true ? session('user')->image_path = $image_path : '';
    
                    return response()->json(['dat' => true,'user'=> session('user')]); 
                }
                else{
                    return response()->json(['data' => false], 500); 
                }
            }
            else{
                return redirect(route('login'));
            }
        } catch (Throwable $th) {
            return response()->json(['data' => $th]); 
        }
    }

    public function getFollowers(Request $request){
        if(session('user')){
            $people = Followers::where('following', session('user')->id)->get();
            $count = 0;

            foreach ($people as $person) {
                $person->id = $person->follower;
                $isFollowing = Followers::where('following',$person->id)
                            ->where('follower', session('user')->id)
                            ->count();
                if ($isFollowing > 0) {
                    $person->is_following = true;
                }
                $isFollowed = Followers::where('follower',$person->id)
                            ->where('following', session('user')->id)
                            ->count();
                if ($isFollowed > 0) {
                    $person->followed = true;
                }
                $followers = Followers::where('following',$person->id)
                            ->count();
                $person->followers = $followers;

                $isBlocked = $this->Views->isBlocked($person->id);
                if($isBlocked > 0){
                    $people[$count] = [];
                }else{
                    $person_deets = Users::where('id', $person->id)->select('id','name', 'email', 'bio', 'image_path')->get();
                    array_merge($person, $person_deets);
                }
                $count++;
            }
            
            return response()->json($people); 
        }
        else{
            return redirect(route('login'));
        }
    }

    public function makePost(Request $request){
        $request->validate([
            'caption' => 'string|required',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi'
        ]);

        $text = $request->caption;

        $uploadedFiles = $request->file('files');
        $timestamp = time();

        $months = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $year = (int)date("Y");
        $month = (int)date("m");
        $month = $months[$month];
        $day = (int)date("d");
        $hour = (int)date('G');
        $minute = (int)date('i');

        Post::create([
            'user' => session('user')->id,
            'text' => $text,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'timestamp' => $timestamp
        ]);

        $post_id = Post::where('user', session('user')->id)->where('timestamp', $timestamp)->pluck('id')->first();

        if($uploadedFiles){
            foreach ($uploadedFiles as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('img/post_files'), $fileName);
    
                PostFiles::create([
                    'post_id' => $post_id,
                    'path' => 'img/post_files/'.$fileName
                ]);
            }
        }

        return response()->json([
            'url' => route('post', ['id' => $post_id])
        ]);
    }

    public function likePost(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'integer|required'
            ]);
            $id = $request->id;

            $isLiked = Likes::where('user', session('user')->id)->where('post', $id)->count();
    
            if($isLiked == 0){
                Likes::create([
                    'user' => session('user')->id,
                    'post' => $id,
                ]);
            }
            else{
                Likes::where('user', session('user')->id)->where('post', $id)->delete();
            }
    
            $likes = Likes::where('post', $id)->count();
            $isStillLiked = Likes::where('user', session('user')->id)->where('post', $id)->count();

            return response()->json(['data' => $likes, 'isLiked' => $isStillLiked > 0], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function savePost(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'integer|required'
            ]);
            $id = $request->id;

            $isSaved = SavedPost::where('user', session('user')->id)->where('post', $id)->count();
    
            if($isSaved == 0){
                SavedPost::create([
                    'user' => session('user')->id,
                    'post' => $id,
                ]);
            }
            else{
                SavedPost::where('user', session('user')->id)->where('post', $id)->delete();
            }

            $isStillSaved = SavedPost::where('user', session('user')->id)->where('post', $id)->count();
    
            return response()->json(['data' => true, 'isSaved' => $isStillSaved > 0], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function comment(Request $request){
        if(null !== session('user')){
            $request->validate([
                'text' => 'string|required',
                'post' => 'integer|required'
            ]);

            $text = $request->text;
            $post_id = $request->post;
            $months = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $year = (int)date("Y");
            $month = (int)date("m");
            $month = $months[$month];
            $day = (int)date("d");
            $hour = (int)date('G');
            $minute = (int)date('i');

            $post = Post::where('id', $post_id)->get();
            $post = $this->Views->validatePost($post);

            Comments::create([
                'text' => $text,
                'post' => $post_id,
                'user' => session('user')->id,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'minute' => $minute
            ]);

            $comments = Comments::where('post', $post_id)->count();

            $post_comments = Comments::where('post', $post_id)->get();
            $post_comments = $this->Views->validateComments($post_comments, $post[0]->user->id);
    
            return response()->json(['data' => true, 'comments' => $comments, 'post_comments' => $post_comments], 200);
        }
        else{
            return redirect(route('login'));
        }
    }

    public function deletePost(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->id;
            if(Post::where('id', $id)->count() < 1){
                return response()->json(['data' => 'Post not found'], 404);
            }
            else{
                if(Post::where('id', $id)->delete()){
                    return response()->json(['data' => true], 200);
                }
                else{
                    return response()->json(['data' => false], 503);
                }
            }
        }
        else{
            return redirect(route('login'));
        }
    }

    public function deleteComment(Request $request){
        if(null !== session('user')){
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->id;
            if(Comments::where('id', $id)->count() < 1){
                return response()->json(['data' => 'Comment not found'], 404);
            }
            else{
                if(Comments::where('id', $id)->delete()){
                    return response()->json(['data' => true], 200);
                }
                else{
                    return response()->json(['data' => false], 503);
                }
            }
        }
        else{
            return redirect(route('login'));
        }
    }

    public function sendMessage(Request $request){
        $request->vaidate([
            'message' => 'string|required',
            'receiver' => 'string|required',
            'status' => 'string|required'
        ]);

        $message = $request->message;
        $receiver = $request->receiver;
        $user = session('user')->id;
        $status = $request->status;
        $months = ['','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $year = (int)date("Y");
        $month = (int)date("m");
        $month = $months[$month];
        $day = (int)date("d");
        $hour = (int)date('G');
        $minute = (int)date('i');

        $query = Messages::create([
            'text' => $message,
            'sender' => $user,
            'receiver' => $receiver,
            'status' => $status,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'timestamp' => time()
        ]);

        if($query){
            return response()->json(['data' => true], 200);
        }
        else{
            return response()->json(['error' => 'error connecting to database.'], 405);
        }
    }

    public function likeComment(Request $request){
        $request->validate([
            'id' => 'integer|required'
        ]);

        $id = $request->id;
        $query;

        $checkIsLiked = LikedComment::where('user', session('user')->id)
                                    ->where('comment', $id)
                                    ->count();
        if($checkIsLiked > 0){
            $query = LikedComment::where('user', session('user')->id)
                                    ->where('comment', $id)
                                    ->delete();
        }
        else{
            $query = LikedComment::create([
                'user' => session('user')->id,
                'comment' => $id
            ]);
        }

        $numberOfComment = LikedComment::where('comment', $id)
                                    ->count();

        if($query){
            return response()->json(['data' => true,'like' => asset('img/like.png'), 'heart' => asset('img/heart_.png'),'number' => $numberOfComment], 200);
        }
        else{
            return response()->json(['error' => 'error connecting to database.', 500]);
        }
    }
}
