<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'bookname',
        'author',
        'description',
        'category',
        'publisher',
        'publish_date',
        'image'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class,'bookID','id');
    }

}
