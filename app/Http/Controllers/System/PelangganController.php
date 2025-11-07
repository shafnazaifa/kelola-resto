<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('Dashboard.Systems.Pelanggan.index',compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name_pelanggan' => 'required|string|min:3',
            'gender'=> 'required',
            'phone_number' => 'required|regex:/^08[0-9]{8,12}$/',
            'address' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Normalize gender: accept '1'/'0', 1/0, 'Laki-laki'/'Perempuan'
        $genderRaw = $request->gender;
        $genderBool = null;
        if ($genderRaw === '1' || $genderRaw === 1 || $genderRaw === true || $genderRaw === 'Laki-laki') {
            $genderBool = 1; // male
        } elseif ($genderRaw === '0' || $genderRaw === 0 || $genderRaw === false || $genderRaw === 'Perempuan') {
            $genderBool = 0; // female
        }

        if ($genderBool === null) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['gender' => ['Format gender tidak valid']],
                ], 422);
            }
            return redirect()->back()->withErrors(['gender' => 'Format gender tidak valid'])->withInput();
        }

        $pelanggan = Pelanggan::create([
            'name_pelanggan' => $request->name_pelanggan,
            'gender' => $genderBool,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);
        
        return response()->json([
            'success' => true,
            'pelanggan' => $pelanggan
        ]);
    }

    public function show(string $id)
    {
        $pelanggan = Pelanggan::find($id);
        if(!$pelanggan){
            return redirect()->back()->with('failed', 'Pelanggan tidak ditemukan');
        }
        return view('Dashboard.Systems.Pelanggan.show', compact('pelanggan'));
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::find($id);
        if(!$pelanggan){
            return redirect()->back()->with('failed', 'pelanggan tidak ditemukan');
        }
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'pelanggan berhasil dihapus');
    }
}
