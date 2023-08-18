<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'result', 'rolled_number', 'winAmount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
