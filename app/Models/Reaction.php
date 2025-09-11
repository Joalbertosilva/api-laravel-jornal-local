<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactionable()
    {
        return $this->morphTo();
    }
}
