<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWebsiteKey extends Model
{
    use HasFactory;
    protected $table = 'user_website_keys';
    protected $fillable = [
        'owner_id',
        'website_url',
        'verification_key'
    ];
}
