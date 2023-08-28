<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampMap extends Model
{
    use HasFactory;
    protected $table = 'camp_maps';
    protected $fillable = [
        'campaign_id',
        'field_header',
        'template_variable',
        'val_type',
        'meta'
    ];
}
