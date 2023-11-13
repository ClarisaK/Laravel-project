<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    //relacion many to one
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    //relacion many to one
    public function image(){
        return $this->belongsTo(Image::class, 'image_id');
    }
}
