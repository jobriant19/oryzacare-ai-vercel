<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysisImage extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    protected $fillable = ['name', 'mime', 'data'];
}
