<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WebsitesInfo extends Model
{
    use HasFactory;

    protected $table = 'user_websites';


    public function owner_id()
    {
        return $this->belongsTo(User::class);
    }
}
