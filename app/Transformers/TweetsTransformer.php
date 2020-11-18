<?php

namespace App\Transformers;

use App\Transformers\BaseTransformer as Transformer;
use Illuminate\Support\Facades\DB;

class TweetsTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     */

 

    public function transform($tweet ) : array
    {

       return [
                'id'                => (int) $tweet->id,
                'name'              => ($tweet->user) ? $tweet->user->name : null,
                'tweet'             => $tweet->tweet,
                'image'             => ($tweet->user) ? $tweet->user->image : null,
            ];
    }

}
