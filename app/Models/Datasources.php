<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datasources extends Model
{
    use HasFactory;

    protected $table = "user_datasources";

    
    public function owner_id()
    {
        return $this->belongsTo(User::class);
    }




}
