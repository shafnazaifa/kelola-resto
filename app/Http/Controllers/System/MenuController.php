<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::all();
        return view('Dashboard.Systems.Menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Dashboard.Systems.Menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name_menu' => 'required|string|min:3',
            'harga' => 'required|numeric|min_digits:3',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $menu = Menu::create($request->all());
        return redirect()->route('menu.index')->with('success', 'menu berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
    */
    public function show(string $id)
    {
        $menu = Menu::find($id);
        if(!$menu){
            return redirect()->route('menu.index')->with('failed', 'menu tidak ditemukan');
        }
        return view('Dashboard.Systems.Menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::find($id);
        if(!$menu){
            return redirect()->route('menu.index')->with('failed', 'menu tidak ditemukan');
        }
        return view('Dashboard.Systems.Menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menu = Menu::find($id);
        if(!$menu){
            return redirect()->back()->with('failed', 'menu tidak ditemukan');
        }

        $validator = Validator::make($request->all(),[
            'name_menu' => 'required|string|min:3',
            'harga' => 'required|numeric|min_digits:3',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $menu = Menu::update($request->all());
        // $menu = Menu::fill($request->all());
        // $menu->save();
        return redirect()->route('menu.index')->with('success', 'menu berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::find($id);
        if(!$menu){
            return redirect()->back()->with('failed', 'menu tidak ditemukan');
        }
        $menu->delete();
        return redirect()->route('menu.index')->with('success', 'menu berhasil dihapus');
    }

    /**
     * Get menus data as JSON for AJAX requests
     */
    public function getMenusData()
    {
        $menus = Menu::select('id', 'name_menu', 'harga')->get();
        return response()->json($menus);
    }
}
