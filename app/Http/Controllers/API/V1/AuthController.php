<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as Controller;
use App\Models\User;
use App\Transformers\ProfileTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JWTAuth;
use Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{

    protected $profileTransformer;

  	function __construct(Request $request, ProfileTransformer $profileTransformer){

        
        $this->profileTransformer = $profileTransformer;
        $this->middleware('jwt.auth')->only(['logout']);

    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'             => 'required|email|max:255|unique:users,email',
            'password'          => 'required|min:6',
            'name'              => 'required',
            'image'             => 'required|file'
        ]);

        
        if ($validator->fails()) {
            return $this->setStatusCode(422)->respondWithError($validator->messages()->first());
        }

        if(isset($request->image) && !empty($request->image) )
        {
            $image_url = $this->uploadFile($request->image);
        }
        
        $user           = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = $request->password;
        $user->image    = $image_url;
        $user->save();

        return $this->generateToken( $request->only('email','password'));
        //return $this->respondWithSuccess(trans('api_msgs.Success Register'));
    }



    public function login(Request $request)
    {
         $validator = Validator::make($request->all(),[
         'email'            => 'required|email|exists:users,email',
         'password'         => 'required'
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->respondWithError($validator->messages()->first());
        }


        $credentials = $request->only('email','password');

        if ( !$this->isAccountExist( $credentials ) ) {
            return $this->setStatusCode(401)->respondWithError(trans('api_msgs.check_credentials')); 
        }  
        
        
        return $this->generateToken( $request->only('email','password'));


    }

    #################### check account exist in database 
    public function isAccountExist( array $credentails ) :bool
    {
        
            if (! Auth::attempt(['email' => $credentails['email'] , 'password' => $credentails['password']])) {
                return false;

            }else{

                return true;
            }
        
    }


    public function generateToken( $credentails)
    {

    	try {
            if ( !$token = JWTAuth::attempt($credentails) ) {

                   return $this->respondUnauthorized( trans('api_msgs.check_credentials') );
            }

        } catch (JWTException $e) {

            return $this->setStatusCode(500)->respondWithError(trans('api_msgs.can not create token'));
        }
        
        $user = JWTAuth::toUser($token);
        $profile = $this->profileTransformer->transform($user);

        return $this->respond(['status_code'=>200,'data'=>$profile,'token' => $token,'message'=>"success" ]) ;
    }
    
    



}
