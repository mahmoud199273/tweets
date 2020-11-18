<?php

namespace App\Transformers;

use App\Transformers\BaseTransformer as Transformer;
use Illuminate\Support\Facades\DB;
use App\Models\FollowedUsers;

class ProfileTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     */

    protected $flag = false;
    function setFlag($flag)
    {
        $this->flag = $flag; // set true to show user followed data
    }
   
 

    public function transform($user ) : array
    {
       $return_arr =  [
                'id'                => (int) $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'image'             => ($user->image) ? $user->image : null,
            ];
        if($this->flag)
        {
            $return_arr['is_followed'] = $user->followedUser();
        }
        return $return_arr;
    }

}
