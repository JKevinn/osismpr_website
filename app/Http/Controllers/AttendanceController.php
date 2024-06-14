<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        
    $query = DB::table('meeting_schedule')
    ->select(
        'meeting_schedule.uuid', 
        'meeting_schedule.meeting_title', 
        'meeting_schedule.time_start', 
        'meeting_schedule.time_end', 
        'meeting_schedule.date', 
        'meeting_schedule.location',
        'meeting_schedule.status',
        DB::raw('COUNT(attendance.uuid) as attendance_count')
    )
    ->leftJoin('attendance', 'meeting_schedule.uuid', '=', 'attendance.meeting_uuid')
    ->groupBy(
        'meeting_schedule.uuid', 
        'meeting_schedule.meeting_title', 
        'meeting_schedule.time_start', 
        'meeting_schedule.time_end', 
        'meeting_schedule.date', 
        'meeting_schedule.location'
    )
    ->orderBy('meeting_schedule.date', 'desc');

        if($request->has("search")) {
            $search = $request->input("search");
            $query->where('meeting_title', 'like', '%' . $search . '%')
            ->orWhere('time_start', 'like', '%' . $search . '%')
            ->orWhere('time_end', 'like', '%' . $search . '%')
            ->orWhere('location', 'like', '%' . $search . '%')
            ->orWhere('date', 'like', '%' . $search . '%');
        }

        $meeting = $query->paginate(5);

        return view("attendance.index", compact("meeting"));
    }

    public function scanner($meeting_uuid) {
        $attendance = DB::table("attendance")->where("meeting_uuid", $meeting_uuid)->join("users", "attendance.user_uuid", "users.uuid")->join("meeting_schedule", "attendance.meeting_uuid", "meeting_schedule.uuid")->select("attendance.uuid", "users.name", "attendance.arrival_time", "attendance.status")->orderBy("attendance.arrival_time", 'desc')->get();

        if($attendance) {
            return view("attendance.scan", compact("meeting_uuid", "attendance"))->with("success", "Get data attendance successfully!");
        } else {
            return view("attendance.scan")->with("failed", "Get data attendance failed!");
        }
    }

    public function store(Request $request, $meeting_uuid) {
        $meeting_start = DB::table("meeting_schedule")->where("uuid", $meeting_uuid)->value("time_start");

        $validate = DB::table("attendance")->where([
            ["meeting_uuid", $meeting_uuid],["user_uuid", $request->uuid]
        ])->first(["meeting_uuid", "user_uuid"]);

        if($validate) {
            return redirect()->back()->with("failed", "You has been absent!");
        }


        $attendance = DB::table("users")->where("uuid", $request->uuid)->first();

        if($attendance) {
            $insertAttendance = DB::table("attendance")->insert([
                "uuid" => Str::uuid(),
                "meeting_uuid" => $meeting_uuid,
                "user_uuid" => $request->uuid,
                "arrival_time" => Carbon::now(),
                "status" => $this->getStatus($meeting_start)
            ]);

            if($insertAttendance) {
                return redirect()->back()->with("success", "Scan successfuly!");
            } else {
                return redirect()->back()->with("failed", "Scan failed!");
            }
        } else {
            return redirect()->back()->with("failed", "You're QR Code is invalid!");
        }
    }

    public function getStatus($meeting_start) {
        $time_arrive = Carbon::now();

        $hour = substr($meeting_start, 0, 2);
        $minutes = substr($meeting_start, 3, 2);
        $seconds = substr($meeting_start, 6, 2);

        // Mengubah jam, menit, dan detik menjadi integer
        $newHour = (int)$hour;
        $newMinutes = (int)$minutes;
        $newSeconds = (int)$seconds;

        // Menghitung total detik dari durasi
        $meeting_start_seconds = ($newHour * 3600) + ($newMinutes * 60) + $newSeconds;
        $time_arrive_seconds = $time_arrive->hour * 3600 + $time_arrive->minute * 60 + $time_arrive->second;

        if($time_arrive_seconds >= $meeting_start_seconds) {
            return "late";
        } else {
            return "on_time";
        }
    }

    public function listAttendance($meeting_uuid) {
        $attendance = DB::table("attendance")->where("meeting_uuid", $meeting_uuid)->join("users", "attendance.user_uuid", "users.uuid")->join("meeting_schedule", "attendance.meeting_uuid", "meeting_schedule.uuid")->select("attendance.uuid", "users.name", "attendance.arrival_time", "attendance.status")->orderBy("attendance.arrival_time")->paginate(5);

        if($attendance) {
            return view("attendance.listAttendance", compact("meeting_uuid", "attendance"))->with("success", "Get data attendance successfully!");
        } else {
            return view("attendance.listAttendance")->with("failed", "Get data attendance failed!");
        }
    }
}
