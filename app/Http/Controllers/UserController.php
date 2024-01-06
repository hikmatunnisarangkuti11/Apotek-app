<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('name', 'ASC')->simplePaginate(5);
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'role' => 'required',      
        ]);

        //mendapatkan 3 karakter awal dari email dan nama
        $emailPrefix = substr($request->email, 0, 3);
        $namePrefix = substr($request->name, 0, 3);

        //menggabungkan kadua prefix menjadi password
        $generatedPassword = $emailPrefix . $namePrefix;

        //mengekripsikan password dengan bcrytp
        $hashedPassword = bcrypt($generatedPassword);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $hashedPassword,
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambahkan Data User!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        //mengembalikan bentuk json dikirim data yang di ambil dengan response status code 200
        // response status code api :
        // 200 -> succes/ok
        // 400 an -> error kode/validasi input user
        // 419 -> error token csrf
        // 500 an -> error server hosting
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // sudah dipilih lalu di ubah tanpa kondisi
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'role' => 'required',
            ]);
            //
            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
        ]);

        return redirect()->route('users.data')->with('success', 'Berhasil Mengubah Data User!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //cari dan hapus data
        User::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus Data User!');
    }

    public function stockData()
    {
        $user = User::orderBy('stock', 'ASC')->simplePaginate(5);
        return view('user.stock', compact('user'));

    }

    public function authLogin(request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        // simpan data dari inputan email dan password ke dalam variable untuk memudahkan pemanngilnya
        $user = $request->only(['email', 'password']);
        // attempt : mengecek kecocokan email dan password kemudian menyimpan nya ke dalam class Auth (memebri identittas data riwayat loginn ke rojectnya)
        if (auth::attempt($user)) {
            //perbedaan redirect() dan redirect()->Route ?? redirect() -> path /, Route
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with('failed', 'login gagal! silahkan coba lagi');
        }
    }

    public function logout()
    {
        //menghapus atau menghilangkan data session login
        Auth::logout();
        return redirect()->route('login');
    }

}
