<?php

namespace App\Models\Master;

use App\Models\Master\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetIndikator extends Model
{
     use HasFactory;
    
    protected $table = "targetindikator_m";
    protected $guarded = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "id";
    
}