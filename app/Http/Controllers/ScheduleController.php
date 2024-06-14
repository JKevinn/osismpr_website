<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index(Request $request) {
        try {
            $query = DB::table("meeting_schedule")->select("uuid", "meeting_title", "time_start", "time_end", "location", "date", "description", "status", "created_by")->orderBy("date");

            if($request->has("search")) {
                $search = $request->input("search");
                $query->where('meeting_title', 'like', '%' . $search . '%')
                ->orWhere('time_start', 'like', '%' . $search . '%')
                ->orWhere('time_end', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('created_by', 'like', '%' . $search . '%');
            }

            $schedule = $query->paginate(5);

            // Mengirim variabel 'schedule' ke view
            return view("schedule.index", compact('schedule'))->with('success', "Get data schedule successfully!");
        }
        catch(Exception $error) {
            // Log the error
            Log::error('Error fetching schedule: ' . $error->getMessage());
    
            // Return view with error message
            return view("schedule.index")->with('error', "Failed to get data schedule. Please try again later.");
        }
    }

    public function store(Request $request) {
        $request->validate([
            "meeting_title" => "required|string",
            "location" => "required|string",
            "date" => "required|date",
            "time_start" => "required|string",
            "time_end" => "required|string",
            "description" => "required|string"
        ]);

        $store = DB::table("meeting_schedule")->insert([
            "uuid" => Str::uuid(),
            "meeting_title" => $request->meeting_title,
            "location" => $request->location,
            "date" => $request->date,
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "description" => $request->description,
            "created_by" => "Kevin"
        ]);

        if($store) {
            return redirect()->back()->with("success", "Create meeting successfully!");
        } else {
            return redirect()->back()->with("failed", "Create meeting failed!");
        }
    }

    public function update(Request $request, $uuid) {
        $update = DB::table("meeting_schedule")->where("uuid", $uuid)->update([
            "meeting_title" => $request->meeting_title,
            "location" => $request->location,
            "date" => $request->date,
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "description" => $request->description,
            "status" => $request->status,
        ]);

        if($update) {
            return redirect()->back()->with("success", "Edit meeting successfully!");
        } else {
            return redirect()->back()->with("failed", "Edit meeting failed!");
        }
    }

    public function delete($uuid) {
        $delete = DB::table("meeting_schedule")->where("uuid", $uuid)->delete();

        if($delete) {
            return redirect()->back()->with("success", "Delete meeting successfully!");
        } else {
            return redirect()->back()->with("failed", "Delete meeting failed!");
        }
    }
}
