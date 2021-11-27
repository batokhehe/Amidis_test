<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanBarang;
use App\Models\DetailPermintaanBarang;
use Illuminate\Support\Facades\DB;
use Mail;

class PermintaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PermintaanBarang::select('permintaan_barangs.id', 'users.nik', 'name', 'departemen', 'tanggal_permintaan')
                    ->join('users', 'users.nik', '=', 'permintaan_barangs.nik')
                    ->get();
        return response()->json( $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik'    => 'required',
            'tanggal_permintaan' => 'required',
            'detailBarangs' => 'required',
        ]);
        foreach ($request->input('detailBarangs') as $detail){
            if($detail['kuantiti'] == 0 || $detail['tersedia'] == '' || $detail['keterangan'] == '' || (int)$detail['kuantiti'] > $detail['tersedia'] )
                return response()->json([
                                            'status' => 'error',
                                            'message'   =>  'The given data was invalid.',
                                            'errors' => 'Check your detail permintaan barang data'
                                        ], 500);
        }
        
        $data = array(
                    'nik' => $request->input('nik'), 
                    'tanggal_permintaan' =>$request->input('tanggal_permintaan')
                );
        $id = PermintaanBarang::create($data)->id;
        foreach ($request->input('detailBarangs') as $detail){
            $details[] = array(
                    'id_permintaan_barang' => $id, 
                    'kode_barang' => $detail['kode'],
                    'qty' => $detail['kuantiti'],
                    'keterangan' => $detail['keterangan'],
                );
        }
        DetailPermintaanBarang::insert($details);

        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PermintaanBarang::select('permintaan_barangs.id', 'users.nik', 'name', 'departemen', 'tanggal_permintaan')
                    ->join('users', 'users.nik', '=', 'permintaan_barangs.nik')
                    ->where('permintaan_barangs.id', '=', $id)
                    ->first();
        $data['detailBarangs'] = DetailPermintaanBarang::select(DB::raw('CONCAT(barangs.kode, " - ", barangs.nama) AS label'), 'barangs.lokasi', 'barangs.qty as tersedia', 'detail_permintaan_barangs.qty as kuantiti', 'barangs.uom as satuan', 'detail_permintaan_barangs.keterangan')
                                    ->join('barangs', 'barangs.kode', '=', 'detail_permintaan_barangs.kode_barang')
                                    ->where('id_permintaan_barang', '=', $id)->get();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json( [ 'template' => PermintaanBarang::find($id) ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nik'    => 'required',
            'tanggal_permintaan' => 'required',
        ]);
        $model = PermintaanBarang::find($id);
        $model->nik = $request->input('nik');
        $model->tanggal_permintaan = $request->input('tanggal_permintaan');
        $template->save();
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $template = PermintaanBarang::find($id);
        if($template){
            $template->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
