<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as Controller;
use App\Transformers\ProfileTransformer;
use App\Transformers\TweetsTransformer;
use App\Models\User;
use App\Models\Tweets;
use App\Models\FollowedUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    //

    protected $profileTransformer;
    protected $tweetsTransformer;


    function __construct(Request $request, ProfileTransformer $profileTransformer,TweetsTransformer $tweetsTransformer){
        $this->profileTransformer = $profileTransformer;
        $this->tweetsTransformer = $tweetsTransformer;
    }



    public function Profile( Request $request ) // get user profile data
    {

        $user =  $this->getAuthenticatedUser();
        return $this->respond(['status_code'=> 200,'data' => $this->profileTransformer->transform($user) ]);
    }


    public function Tweets( Request $request )
    {

        $user =  $this->getAuthenticatedUser();

        if ( $request->limit ) {
            $this->setPagination($request->limit);
        }

        $pagination = Tweets::where('user_id' ,$user->id);
        $pagination = $pagination->paginate($this->getPagination());
        $tweets = $this->tweetsTransformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, ['data' => $tweets,'status_code' => 200 , 'message' => "success" ]);
    }

    

    public function StoreTweet(Request $request)
    {

        $user =  $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(),[
            'tweet'          => 'required|max:140',
        ]);
   
   
        if ($validator->fails()) {
            return $this->setStatusCode(422)->respondWithError($validator->messages()->first());
        }


        $tweet              = new Tweets;
        $tweet->user_id     = $user->id;
        $tweet->tweet       = $request->tweet;
        $tweet->save();


        return $this->respondWithSuccess(trans('api_msgs.tweet created'));


    }

    public function FollowUser(Request $request)
    {

        $user =  $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(),[
            'user_id'          => 'required|exists:users,id',
        ]);
   
   
        if ($validator->fails()) {
            return $this->setStatusCode(422)->respondWithError($validator->messages()->first());
        }

        $followed_user = FollowedUsers::where("user_id",$user->id)->where("followed_user_id",$request->user_id)->first();

        if($followed_user)
        {
            return $this->setStatusCode(406)->respondWithError(trans('api_msgs.followed already')); 
        }


        $follow                         = new FollowedUsers;
        $follow->user_id                = $user->id;
        $follow->followed_user_id       = $request->user_id;//follower id
        $follow->save();


        return $this->respondWithSuccess(trans('api_msgs.user followed'));


    }

    public function UnFollowUser(Request $request)
    {

        $user =  $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(),[
            'user_id'          => 'required|exists:users,id',
        ]);
   
   
        if ($validator->fails()) {
            return $this->setStatusCode(422)->respondWithError($validator->messages()->first());
        }

        $followed_user = FollowedUsers::where("user_id",$user->id)->where("followed_user_id",$request->user_id)->first();

        if(!$followed_user)
        {
            return $this->setStatusCode(406)->respondWithError(trans('api_msgs.not followed')); 
        }

        $followed_user->delete();
    

        return $this->respondWithSuccess(trans('api_msgs.user unfollowed'));


    }


    public function TimeLine( Request $request )
    {

        $user =  $this->getAuthenticatedUser();

        if ( $request->limit ) {
            $this->setPagination($request->limit);
        }

        $pagination =  new Tweets;
        $pagination =  $pagination->whereIn('user_id',function($query) use($user) {
                        $query->select('followed_user_id')->from('followed_users')->where('user_id',$user->id);
                    });

        $pagination = $pagination->paginate($this->getPagination());
        $tweets = $this->tweetsTransformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, ['data' => $tweets,'status_code' => 200  ]);
    }

    public function AllUsers( Request $request )
    {

        $user =  $this->getAuthenticatedUser();

        if ( $request->limit ) {
            $this->setPagination($request->limit);
        }

        $pagination =  new User;
        $pagination = $pagination->where('id','!=',$user->id); 
        $pagination = $pagination->paginate($this->getPagination());
        $this->profileTransformer->setFlag(true);
        $users = $this->profileTransformer->transformCollection(collect($pagination->items()));
        return $this->respondWithPagination($pagination, ['data' => $users,'status_code' => 200]);
    }





}
