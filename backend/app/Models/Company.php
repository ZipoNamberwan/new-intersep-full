<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'companies';

    public function subsectors()
    {
        return $this->belongsToMany(Subsector::class);
    }

    public function commodities()
    {
        return $this->hasMany(Commodity::class);
    }

    public function surveys()
    {
        return $this->belongsToMany(Survey::class);
    }
}
