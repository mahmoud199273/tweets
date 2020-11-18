<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FollowedUsers extends Model
{
    //
    protected $table = 'followed_users';
    protected $fillable = ['followed_user_id', 'user_id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public  function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public  function followed_user()
    {
        return $this->belongsTo(User::class, 'followed_user_id', 'id');
    }



}
