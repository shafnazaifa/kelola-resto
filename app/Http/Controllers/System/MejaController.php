<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mejas = Meja::all();
        return view('Dashboard.Systems.Meja.index', compact('mejas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Dashboard.Systems.Meja.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'nomer_meja' => 'required|string|min:1',
            'kursi' => 'required|in:2,4,6,8',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meja = Meja::create($request->all());
        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meja = Meja::find($id);
        if(!$meja){
            return redirect()->back()->with('failed', 'meja tidak ditemukan');
        }

        // Prevent editing occupied tables
        if($meja->status === Meja::STATUS_DIISI){
            return redirect()->back()->with('failed', 'Tidak dapat mengedit meja yang sedang digunakan');
        }

        $validator = Validator::make($request->all(),[
            'kursi' => 'required|in:2,4,6,8',
            'status' => 'required|in:tersedia,tidak_tersedia',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meja->update($request->all());
        return redirect()->route('meja.index')->with('success', 'Meja berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meja = Meja::find($id);
        if(!$meja){
            return redirect()->back()->with('failed', 'meja tidak ditemukan');
        }
        
        // Prevent deleting occupied tables
        if($meja->status === Meja::STATUS_DIISI){
            return redirect()->back()->with('failed', 'Tidak dapat menghapus meja yang sedang digunakan');
        }
        
        $meja->delete();
        return redirect()->route('meja.index')->with('success', 'Meja berhasil dihapus');
    }
}
