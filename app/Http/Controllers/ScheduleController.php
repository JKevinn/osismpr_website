<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\MeetingSchedule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index(Request $request) {
        try {
            // Membuat query untuk memilih kolom tertentu dari model MeetingSchedule dan mengurutkannya berdasarkan tanggal
            $query = MeetingSchedule::select("uuid", "meeting_title", "time_start", "time_end", "location", "date", "description", "status", "created_by")
                ->orderBy("date");

            // Memeriksa apakah ada parameter pencarian dalam request
            if ($request->has("search")) {
                $search = $request->input("search");
                // Menambahkan kondisi pencarian ke query untuk mencari dalam kolom tertentu
                $query->where('meeting_title', 'like', '%' . $search . '%')
                      ->orWhere('time_start', 'like', '%' . $search . '%')
                      ->orWhere('time_end', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%')
                      ->orWhere('date', 'like', '%' . $search . '%')
                      ->orWhere('created_by', 'like', '%' . $search . '%');
            }
        
            // Mengeksekusi query dan melakukan paginasi dengan 5 entri per halaman
            $schedule = $query->paginate(5);
        
            // Mengirim variabel 'schedule' ke view dengan pesan sukses
            return view("schedule.index", compact('schedule'))->with('success', "Get data schedule succesful!");
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error fetching schedule: ' . $error->getMessage());
        
            // Mengembalikan view dengan pesan error
            return view("schedule.index")->with('error', "Failed to get data schedule. Please try again later.");
        }        
    }

    public function store(Request $request) {
        try {
            // Memvalidasi data yang akan disimpan
            $request->validate([
                "meeting_title" => "required|string",
                "location" => "required|string",
                "date" => "required|date",
                "time_start" => "required|string",
                "time_end" => "required|string",
                "description" => "required|string"
            ]);

            // Menyimpan data ke database menggunakan Eloquent
            $store = MeetingSchedule::create([
                "uuid" => Str::uuid(),
                "meeting_title" => $request->meeting_title,
                "location" => $request->location,
                "date" => $request->date,
                "time_start" => $request->time_start,
                "time_end" => $request->time_end,
                "description" => $request->description,
                "created_by" => "Kevin"  // Ini bisa disesuaikan sesuai kebutuhan, misalnya menggunakan data pengguna yang sedang login
            ]);

            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($store) {
                return redirect()->back()->with("success", "Create meeting succesful!");
            } else {
                return redirect()->back()->with("failed", "Create meeting failed!");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error creating meeting: ' . $error->getMessage());

            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to create meeting. Please try again later.");
        }
    }

    public function update(Request $request, $uuid) {
        try {
            // Memvalidasi data yang akan diupdate
            $validatedData = $request->validate([
                "meeting_title" => "required|string",
                "location" => "required|string",
                "date" => "required|date",
                "time_start" => "required|string",
                "time_end" => "required|string",
                "description" => "required|string",
                "status" => "required|string"
            ]);
        
            // Mengambil meeting berdasarkan UUID dan mengupdate data yang sesuai
            $meeting = MeetingSchedule::where('uuid', $uuid)->firstOrFail();
            $meeting->update([
                "meeting_title" => $validatedData['meeting_title'],
                "location" => $validatedData['location'],
                "date" => $validatedData['date'],
                "time_start" => $validatedData['time_start'],
                "time_end" => $validatedData['time_end'],
                "description" => $validatedData['description'],
                "status" => $validatedData['status'],
            ]);
        
            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($meeting->wasChanged()) {
                return redirect()->back()->with("success", "Edit meeting succesful!");
            } else {
                return redirect()->back()->with("failed", "No changes made or edit meeting failed.");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error updating meeting: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to edit meeting. Please try again later.");
        }
    }

    public function delete($uuid) {
        try {
            // Mengambil meeting dan attendance berdasarkan UUID
            $meeting = MeetingSchedule::where('uuid', $uuid)->firstOrFail();
            
            // Menghapus meeting dan attendance dengan soft delete
            $meeting->delete();
            Attendance::where('meeting_uuid', $uuid)->delete();
        
            // Mengembalikan respons atau redirect sesuai kebutuhan
            if ($meeting->trashed()) {
                return redirect()->back()->with("success", "Delete meeting succesful!");
            } else {
                return redirect()->back()->with("failed", "Delete meeting failed!");
            }
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error deleting meeting: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return redirect()->back()->with('error', "Failed to delete meeting. Please try again later.");
        }        
    }
}
