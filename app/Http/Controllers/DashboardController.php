<?php

namespace App\Http\Controllers;

use App\Models\MeetingSchedule;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index() {
        try {
            // Menghitung jumlah upcoming meeting
            $upcomingMeeting = MeetingSchedule::where('status', 'upcoming')->count();
        
            // Menghitung jumlah completed meeting
            $completedMeeting = MeetingSchedule::where('status', 'completed')->count();
        
            // Menghitung jumlah users
            $users = User::count();
        
            // Mengirimkan data ke view dashboard
            return view("dashboard", compact("upcomingMeeting", "completedMeeting", "users"))->with("success", "Get dashboard data successfully!");
        } catch (Exception $error) {
            // Mencatat error ke log
            Log::error('Error fetching dashboard data: ' . $error->getMessage());
        
            // Mengembalikan redirect dengan pesan error
            return view("dashboard")->with('error', "Failed to get dashboard data. Please try again later.");
        }
    }
}