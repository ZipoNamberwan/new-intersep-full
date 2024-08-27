<?php

namespace App\Http\Controllers;

use App\Http\Resources\BsResource;
use App\Http\Resources\DesaResource;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Http\Resources\KabupatenResource;
use App\Http\Resources\KecamatanResource;
use App\Models\Bs;
use App\Models\Desa;

class AreaController extends Controller
{
    public function getKab()
    {
        return KabupatenResource::collection(Kabupaten::all());
    }

    public function getKecByKab($idKab){
        $kec = Kecamatan::where(['kab_id' => $idKab])/* ->with('kab') */->get();
        return KecamatanResource::collection($kec);
    }

    public function getDesByKec($idKec){
        $des = Desa::where(['kec_id' => $idKec])/* ->with('kec') */->get();
        return DesaResource::collection($des);
    }

    public function getBsByDes($idDes){
        $bs = Bs::where(['des_id' => $idDes])/* ->with('des') */->get();
        return BsResource::collection($bs);
    }
}
