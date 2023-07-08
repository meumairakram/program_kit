<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';


    public function owner_id()
    {
        return $this->belongsTo(User::class);
    }
}
