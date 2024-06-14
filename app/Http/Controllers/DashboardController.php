<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $upcomingMeeting = DB::table("meeting_schedule")->select("uuid", "status")->where("status", "upcoming")->count();
        $completedMeeting = DB::table("meeting_schedule")->select("uuid", "status")->where("status", "completed")->count();
        $users = DB::table("users")->select("uuid")->count();

        return view("dashboard", compact("upcomingMeeting", "completedMeeting", "users"))->with("success", "Get dashboard data successfully!");
    }
}