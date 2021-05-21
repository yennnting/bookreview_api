<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'bookID',
        'rate',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class,'bookID','id');
    }
}
