<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthTokens extends Model
{
    use HasFactory;

    protected $table = 'auth_tokens';

    protected $fillable = [
        'owner_id',
        'auth_type',
        'key_type',
        'key_value',
        'refresh_token'
    ];
    public function owner_id()
    {
        return $this->belongsTo(User::class);
    }

}
