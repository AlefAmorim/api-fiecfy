<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{   
    use HasFactory, SoftDeletes;
    protected $fillable = ["name", "description", "cover_image"];

    public function song(){
        return $this->belongsToMany(Song::class);
    }

    public function artist(){
        return $this->belongsToMany(Artist::class);
    }
}
