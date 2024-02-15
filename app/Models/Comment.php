<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['text' , 'author' , 'status' , 'owner' , 'type' , 'score'];

    public function userAuthor()
    {
        return $this->belongsTo(User::class , 'author' , 'id');
    }

    public function userOwner()
    {
        return $this->belongsTo(User::class , 'owner' , 'id');
    }
}
