<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSourceField extends Model
{
    use HasFactory;
    protected $table = 'data_source_fields';
    protected $fillable = [
        'data_source_id',
        'data_source',
        'data_source_headers'
    ];
}
