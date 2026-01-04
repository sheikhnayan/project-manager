<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all holidays for the authenticated user
        $holidays = UserHoliday::where('user_id', $user->id)
            ->pluck('holiday_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
        
        return view('front.holidays', [
            'user' => $user,
            'holidays' => $holidays
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $user = Auth::user();
        $holidaysAllowed = $user->holidays_allowed ?? 20;
        $date = $request->input('date');

        // Check if holiday already exists
        $holiday = UserHoliday::where('user_id', $user->id)
            ->where('holiday_date', $date)
            ->first();

        if ($holiday) {
            // Remove holiday
            $holiday->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'remaining' => $holidaysAllowed - UserHoliday::where('user_id', $user->id)->count()
            ]);
        } else {
            // Check if user has reached their limit
            $currentCount = UserHoliday::where('user_id', $user->id)->count();
            
            if ($currentCount >= $holidaysAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached your holiday limit of ' . $holidaysAllowed . ' days.'
                ], 400);
            }

            // Add holiday
            UserHoliday::create([
                'user_id' => $user->id,
                'holiday_date' => $date,
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'remaining' => $holidaysAllowed - UserHoliday::where('user_id', $user->id)->count()
            ]);
        }
    }

    public function getHolidays(Request $request)
    {
        $user = Auth::user();
        $holidaysAllowed = $user->holidays_allowed ?? 20;
        
        $holidays = UserHoliday::where('user_id', $user->id)
            ->pluck('holiday_date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        return response()->json([
            'holidays' => $holidays,
            'allowed' => $holidaysAllowed,
            'used' => count($holidays),
            'remaining' => $holidaysAllowed - count($holidays)
        ]);
    }

    public function getTeamHolidays(Request $request)
    {
        $user = Auth::user();
        
        // Get team members based on company
        $teamMembersQuery = User::where('is_archived', 0);
        if ($user->role_id != 8 && $user->company_id) {
            $teamMembersQuery->where('company_id', $user->company_id);
        }
        $teamMembers = $teamMembersQuery->get();
        
        // Get holidays for all team members
        $teamHolidays = [];
        foreach ($teamMembers as $member) {
            $holidays = UserHoliday::where('user_id', $member->id)
                ->pluck('holiday_date')
                ->map(fn($date) => $date->format('Y-m-d'))
                ->toArray();
            $teamHolidays[$member->id] = $holidays;
        }
        
        return response()->json([
            'holidays' => $teamHolidays
        ]);
    }
}
