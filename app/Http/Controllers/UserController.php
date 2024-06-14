<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request) {
        try {
            $query = DB::table("users")->select("uuid", "name", "username", "nis", "rayon", "position")->orderBy("name");

            if($request->has("search")) {
                $search = $request->input("search");
                $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhere('nis', 'like', '%' . $search . '%')
                ->orWhere('rayon', 'like', '%' . $search . '%')
                ->orWhere('position', 'like', '%' . $search . '%');
            }

            $users = $query->paginate(7);

            // Mengirim variabel 'users' ke view
            if($users) {
                return view("user.index", compact('users'))->with('success', "Get data users successfully!");
            } else {
                return view("user.index")->with('failed', "Get data users failed!");
            }
        }
        catch(Exception $error) {
            // Log the error
            Log::error('Error fetching users: ' . $error->getMessage());
    
            // Return view with error message
            return view("user.index")->with('error', "Failed to get data users. Please try again later.");
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                "name" => "required|string",
                "nis" => "required|integer",
                "rayon" => "required|string",
                "position" => "required|string"
            ]);
    
            $store = DB::table("users")->insert([
                "uuid" => Str::uuid(),
                "name" => $request->name,
                "username" => $this->generate_username($request->name),
                "nis" => $request->nis,
                "rayon" => $request->rayon,
                "position" => $request->position,
                "password" => $this->generate_password()
            ]);
    
            if($store) {
                return redirect()->back()->with("success", "Create data user successfully!");
            } else {
                return redirect()->back()->with("failed", "Create data user failed!");
            }
        }
        catch(Exception $error) {
            Log::error('Error fetching users: ' . $error->getMessage());
    
            return redirect()->back()->with('error', "Failed to create data users. Please try again later.");
        }
    }

    public function generate_username($full_name) {
        $full_name = strtolower($full_name);
    
        $full_name = preg_replace('/[^a-z0-9 ]/', '', $full_name);
    
        $name_parts = explode(' ', $full_name);
    
        $username = implode('', $name_parts);
    
        return $username;
    }

    function generate_password() {
        return password_hash(123456, PASSWORD_BCRYPT);
    }

    public function update(Request $request, $uuid)
    {
        $update = DB::table("users")->where("uuid", $uuid)->update([
            "name" => $request->name,
            "username" => $request->username,
            "nis" => $request->nis,
            "rayon" => $request->rayon,
            "position" => $request->position
        ]);

        if ($update) {
            return redirect()->back()->with("success", "Edit data user successfully!");
        } else {
            return redirect()->back()->with("failed", "Edit data user failed.");
        }
    }
    
    public function delete($uuid) {
        $delete = DB::table("users")->where("uuid", $uuid)->delete();

        if($delete) {
            return redirect()->back()->with("success", "Delete data user successfully!");
        } else {
            return redirect()->back()->with("error", "Delete data user failed.");
        }
    }
}
