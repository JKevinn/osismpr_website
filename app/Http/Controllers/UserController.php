<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request) {
        try {
            // Membuat query untuk memilih kolom tertentu dari tabel 'users' dan mengurutkannya berdasarkan nama
            $query = User::select('uuid', 'name', 'username', 'nis', 'rayon', 'position')->orderBy("name");
        
            // Memeriksa apakah ada parameter pencarian dalam request
            if ($request->has("search")) {
                $search = $request->input("search");
                // Menambahkan kondisi pencarian ke query untuk mencari dalam kolom 'name', 'username', 'nis', 'rayon', dan 'position'
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('username', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%')
                      ->orWhere('rayon', 'like', '%' . $search . '%')
                      ->orWhere('position', 'like', '%' . $search . '%');
            }
        
            // Mengeksekusi query dan melakukan paginasi dengan 7 entri per halaman
            $users = $query->paginate(7);
        
            // Mengirim variabel 'users' ke view jika data berhasil diambil
            if ($users) {
                return view("user.index", compact('users'))->with('success', "Get data users successful!");
            } else {
                // Jika tidak ada data yang ditemukan, mengirim view dengan pesan gagal
                return view("user.index")->with('failed', "Get data users failed!");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error fetching users: ' . $error->getMessage());
        
            // Mengembalikan view dengan pesan error
            return view("user.index")->with('error', "Failed to get data users. Please try again later.");
        }        
    }

    public function store(Request $request) {
        try {
            // Validasi data request yang masuk
            $request->validate([
                "name" => "required|string",
                "nis" => "required|integer",
                "rayon" => "required|string",
                "position" => "required|string"
            ]);
        
            // Membuat entri baru dalam tabel 'users' menggunakan Eloquent
            $store = User::create([
                "uuid" => Str::uuid(), // Membuat UUID baru untuk kolom 'uuid'
                "name" => $request->name, // Mengambil nilai 'name' dari request
                "username" => $this->generate_username($request->name), // Membuat username berdasarkan nama
                "nis" => $request->nis, // Mengambil nilai 'nis' dari request
                "rayon" => $request->rayon, // Mengambil nilai 'rayon' dari request
                "position" => $request->position, // Mengambil nilai 'position' dari request
                "password" => Hash::make("123456") // Menghasilkan password dan menyimpannya
            ]);
        
            // Memeriksa apakah entri berhasil disimpan
            if($store) {
                // Jika berhasil, redirect kembali dengan pesan sukses
                return redirect()->back()->with("success", "Create data user successful!");
            } else {
                // Jika gagal, redirect kembali dengan pesan gagal
                return redirect()->back()->with("failed", "Create data user failed!");
            }
        } catch (Exception $error) {
            // Jika terjadi kesalahan, catat pesan error di log
            Log::error('Error creating user: ' . $error->getMessage());
        
            // Redirect kembali dengan pesan error
            return redirect()->back()->with('error', "Failed to create data user. Please try again later.");
        }        
    }

    public function generate_username($full_name) {
        // Mengubah nama lengkap menjadi huruf kecil
        $full_name = strtolower($full_name);

        // Menghapus semua karakter selain huruf kecil, angka, dan spasi
        $full_name = preg_replace('/[^a-z0-9 ]/', '', $full_name);

        // Memecah nama lengkap menjadi bagian-bagian berdasarkan spasi
        $name_parts = explode(' ', $full_name);

        // Menggabungkan semua bagian nama menjadi satu string tanpa spasi
        $username = implode('', $name_parts);

        // Mengembalikan username yang dihasilkan
        return $username;
    }

    public function update(Request $request, $uuid)
    {
        try {
            // Memvalidasi data yang akan diupdate
            $validatedData = $request->validate([
                'name' => 'required|string',
                'username' => 'required|string',
                'nis' => 'required|integer',
                'rayon' => 'required|string',
                'position' => 'required|string',
            ]);
        
            // Melakukan update data pengguna berdasarkan UUID
            $user = User::where('uuid', $uuid)->firstOrFail();
            $user->name = $validatedData['name'];
            $user->username = $validatedData['username'];
            $user->nis = $validatedData['nis'];
            $user->rayon = $validatedData['rayon'];
            $user->position = $validatedData['position'];
            $user->save();
        
            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($user->wasChanged()) {
                return redirect()->back()->with("success", "Edit data user successful!");
            } else {
                return redirect()->back()->with("failed", "No changes made or edit data user failed.");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error updating user: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to update data user. Please try again later.");
        }
    }

    public function editProfile(Request $request, $uuid) {
        try {
            // Memvalidasi data yang akan diupdate
            $validatedData = $request->validate([
                'name' => 'required|string',
                'nis' => 'required|integer',
                'rayon' => 'required|string',
                'password' => 'string'
            ]);
        
            // Melakukan update data pengguna berdasarkan UUID
            $user = User::where('uuid', $uuid)->firstOrFail();
            $user->name = $validatedData['name'];
            $user->nis = $validatedData['nis'];
            $user->rayon = $validatedData['rayon'];
            if($request->password) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();
        
            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($user->wasChanged()) {
                return redirect()->back()->with("success", "Edit profile successful!");
            } else {
                return redirect()->back()->with("failed", "No changes made or edit profile failed.");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error updating user: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to update data user. Please try again later.");
        }
    }
    
    public function delete($uuid) {
        try {
            // Menghapus data pengguna berdasarkan UUID dengan soft delete
            $user = User::where('uuid', $uuid)->firstOrFail();
            $user->delete();
        
            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($user->trashed()) {
                return redirect()->back()->with("success", "Delete data user successful!");
            } else {
                return redirect()->back()->with("error", "Delete data user failed.");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error deleting user: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to delete data user. Please try again later.");
        }        
    }
}
