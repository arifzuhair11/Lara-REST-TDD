<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $guarded = [];
    protected $fillable = ['title', 'episode', 'genre'];
}
