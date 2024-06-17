<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\MeetingSchedule;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        try {
            // Membuat query untuk mengambil data meeting schedule dan jumlah attendance
            $query = MeetingSchedule::select(
                    'meeting_schedules.uuid', 
                    'meeting_schedules.meeting_title', 
                    'meeting_schedules.time_start', 
                    'meeting_schedules.time_end', 
                    'meeting_schedules.date', 
                    'meeting_schedules.location',
                    'meeting_schedules.status'
                )
                ->withCount('attendances') // Menghitung jumlah attendance
                ->orderBy('meeting_schedules.date', 'desc');
    
            // Filter berdasarkan pencarian
            if ($request->has("search")) {
                $search = $request->input("search");
                $query->where('meeting_title', 'like', '%' . $search . '%')
                      ->orWhere('time_start', 'like', '%' . $search . '%')
                      ->orWhere('time_end', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%')
                      ->orWhere('date', 'like', '%' . $search . '%');
            }
    
            // Paginasi hasil query
            $meetings = $query->paginate(5);
    
            // Mengirimkan variabel 'meetings' ke view attendance.index
            return view("attendance.index", compact("meetings"))->with('success', "Get meeting data succesful!");
        } catch (Exception $error) {
            // Log error
            Log::error('Error fetching meeting data: ' . $error->getMessage());
    
            // Mengirimkan redirect dengan pesan error
            return view("attendance.index")->with('error', "Failed to get meeting data. Please try again later.");
        }
    }

    public function scanner($meeting_uuid) {
        try {
            // Mengambil data attendance menggunakan Eloquent
            $attendance = Attendance::where("meeting_uuid", $meeting_uuid)
                ->join("users", "attendance.user_uuid", "=", "users.uuid")
                ->join("meeting_schedule", "attendance.meeting_uuid", "=", "meeting_schedule.uuid")
                ->select("attendance.uuid", "users.name", "attendance.arrival_time", "attendance.status")
                ->orderBy("attendance.arrival_time", 'desc')
                ->get();
            
            if ($attendance) {
                return view("attendance.scan", compact("meeting_uuid", "attendance"))
                    ->with("success", "Get data attendance succesful!");
            } else {
                return view("attendance.scan")->with("failed", "Get data attendance failed!");
            }
        } catch (Exception $error) {
            // Log error
            Log::error('Error fetching attendance data: ' . $error->getMessage());
    
            // Mengirimkan redirect dengan pesan error
            return view("attendance.scan")->with('error', "Failed to get attendance data. Please try again later.");
        }
    }

    public function store(Request $request, $meeting_uuid) {
        try {
            // Mengambil waktu mulai pertemuan dari MeetingSchedule
            $meetingStart = MeetingSchedule::where("uuid", $meeting_uuid)->value("time_start");
    
            // Validasi apakah pengguna sudah melakukan kehadiran
            $attendanceExists = Attendance::where("meeting_uuid", $meeting_uuid)
                ->where("user_uuid", $request->uuid)
                ->exists();
    
            if ($attendanceExists) {
                return redirect()->back()->with("failed", "You have already marked your attendance!");
            }
    
            // Mengambil data pengguna berdasarkan uuid
            $user = User::where("uuid", $request->uuid)->first();

            if ($user) {
                // Menyimpan kehadiran pengguna
                $insertAttendance = Attendance::create([
                    "uuid" => Str::uuid(),
                    "meeting_uuid" => $meeting_uuid,
                    "user_uuid" => $request->uuid,
                    "arrival_time" => Carbon::now(),
                    "status" => $this->getStatus($meetingStart)
                ]);
    
                if ($insertAttendance) {
                    return redirect()->back()->with("success", "Scan successful!");
                } else {
                    return redirect()->back()->with("failed", "Scan failed!");
                }
            } else {
                return redirect()->back()->with("failed", "Your QR Code is invalid!");
            }
        } catch (Exception $error) {
            // Tangkap kesalahan jika terjadi
            Log::error('Error scanning attendance: ' . $error->getMessage());
    
            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->with("error", "Failed to scan attendance. Please try again later.");
        }
    }

    public function getStatus($meeting_start) {
        // Waktu saat ini
        $time_arrive = Carbon::now();

        // Memecah waktu mulai pertemuan menjadi jam, menit, dan detik
        $hour = (int) substr($meeting_start, 0, 2);
        $minutes = (int) substr($meeting_start, 3, 2);
        $seconds = (int) substr($meeting_start, 6, 2);

        // Menghitung total detik dari waktu mulai pertemuan
        $meeting_start_seconds = ($hour * 3600) + ($minutes * 60) + $seconds;

        // Menghitung total detik dari waktu kedatangan
        $time_arrive_seconds = $time_arrive->hour * 3600 + $time_arrive->minute * 60 + $time_arrive->second;

        // Membandingkan waktu kedatangan dengan waktu mulai pertemuan
        if ($time_arrive_seconds >= $meeting_start_seconds) {
            return "late";
        } else {
            return "on_time";
        }
    }

    public function listAttendance($meeting_uuid) {
        try {
            // Mengambil data kehadiran dengan menggunakan Eloquent
            $attendance = Attendance::where('meeting_uuid', $meeting_uuid)
                ->join('users', 'attendance.user_uuid', '=', 'users.uuid')
                ->join('meeting_schedule', 'attendance.meeting_uuid', '=', 'meeting_schedule.uuid')
                ->select('attendance.uuid', 'users.name', 'attendance.arrival_time', 'attendance.status')
                ->orderBy('attendance.arrival_time')
                ->paginate(5);
    
            // Mengirim data kehadiran ke view
            return view('attendance.listAttendance', compact('meeting_uuid', 'attendance'))
                ->with('success', 'Get data attendance succesful!');
        } catch (Exception $error) {
            // Tangkap kesalahan jika terjadi
            Log::error('Error fetching attendance: ' . $error->getMessage());
    
            // Redirect dengan pesan kesalahan jika gagal mengambil data kehadiran
            return view('attendance.listAttendance')
                ->with('error', 'Failed to get data attendance. Please try again later.');
        }
    }
}
