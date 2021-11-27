<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index() {
       $model = Barang::select(DB::raw('CONCAT(kode, " - ", nama) AS label'), 'kode as value')
        ->whereNull('deleted_at')
        ->get();
        return response()->json($model);
    }

    public function show($id) {
        $model = Barang::select('kode', 'nama', 'lokasi', 'qty', 'uom')
        ->where('kode', '=', $id)
        ->first();
        return response()->json( $model );
    }
}
