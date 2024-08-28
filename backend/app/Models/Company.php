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

    public function kab()
    {
        return $this->belongsTo(Kabupaten::class, 'kab_id');
    }
    public function kec()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
    public function des()
    {
        return $this->belongsTo(Desa::class, 'des_id');
    }
    public function bs()
    {
        return $this->belongsTo(Bs::class, 'bs_id');
    }

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
