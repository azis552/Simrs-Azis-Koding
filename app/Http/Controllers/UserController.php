<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function logincheck(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->with('error', 'Login anda gagal');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function index()
    {
        $users = User::latest()->get();
        return view('DataUser.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'email' => '',
            'role' => 'required'
        ]);

        $simpan = User::create($validate);
        return redirect()->route('users.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = DB::table('users')
            ->leftJoin('mapping_users', 'users.id', '=', 'mapping_users.id_users')
            ->leftJoin('pegawai', 'mapping_users.id_pegawai', '=', 'pegawai.id')
            ->where('users.id', $id)
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email',
                'mapping_users.id as mapping_id',
                'pegawai.nama as pegawai_nama',
                'pegawai.no_ktp',
                'pegawai.id as pegawai_id'

            )
            ->first();

        $pegawai = DB::table('pegawai')->select('id', 'nama', 'no_ktp')->get();
        return view('DataUser.show', compact('user', 'pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function mapping(Request $request)
    {
        $validate = $request->validate([
            'id_users' => 'required',
            'id_pegawai' => 'required'
        ]);
        // Cek apakah sudah ada mapping untuk user ini
        $existingMapping = DB::table('mapping_users')->where('id_users', $validate['id_users'])->first();

        if ($existingMapping) {
            // Update mapping yang sudah ada
            DB::table('mapping_users')->where('id', $existingMapping->id)->update([
                'id_pegawai' => $validate['id_pegawai']
            ]);
        } else {
            // Buat mapping baru
            DB::table('mapping_users')->insert($validate);
        }

        return redirect()->route('users.index')->with('success', 'Mapping berhasil disimpan.');
    }
}
