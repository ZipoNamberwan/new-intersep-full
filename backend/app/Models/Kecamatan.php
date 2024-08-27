<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'kecamatan';

    public function kab()
    {
        return $this->belongsTo(Kabupaten::class, 'kab_id');
    }
}
