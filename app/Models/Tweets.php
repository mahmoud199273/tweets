<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Tweets extends Model
{
    //
    protected $table = 'tweets';
    protected $fillable = ['tweet', 'user_id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public  function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
