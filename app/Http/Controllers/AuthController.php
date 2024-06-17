<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            // Memvalidasi input username dan password
            $validatedData = $request->validate([
                "username" => "required|string",
                "password" => "required|string"
            ]);

            // Mengambil user berdasarkan username
            $user = User::where("username", $validatedData['username'])->first();
        
            if ($user) {
                // Memeriksa kecocokan password
                if (Hash::check($validatedData['password'], $user->password)) {
                    // Melakukan login menggunakan Auth
                    Auth::login($user);
        
                    // Redirect ke halaman dashboard setelah login
                    return redirect()->intended('')->with('success', 'Login successful!');
                } else {
                    // Password tidak cocok
                    return redirect()->back()->with('failed', 'Login failed, please try again!');
                }
            } else {
                // Username tidak ditemukan
                return redirect()->back()->with("failed", "The username you entered is incorrect!");
            }
        } catch (ValidationException $e) {
            // Jika terjadi kesalahan validasi
            return redirect()->back()->withErrors($e->errors())->with('error', 'Validation error. Please check your input.');
        } catch (Exception $error) {
            // Jika terjadi kesalahan lainnya
            Log::error('Error during login: ' . $error->getMessage());
        
            return redirect()->back()->with('error', 'Login failed. Please try again later.');
        }        
    }
}
