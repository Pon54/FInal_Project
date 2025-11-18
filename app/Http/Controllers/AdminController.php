<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $r)
    {
        $r->validate(['username' => 'required','password' => 'required']);
        $username = $r->username;
        $password = md5($r->password); // legacy stores md5

        $admin = DB::table('admin')->where('UserName', $username)->where('Password', $password)->first();
        if($admin){
            session(['alogin' => $username]);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->with('error','Invalid Details');
    }

    public function dashboard()
    {
        if(!session('alogin')){
            return redirect('/admin');
        }
        // gather comprehensive stats for dashboard
        $vehiclesCount = \App\Models\Vehicle::count();
        $bookingsCount = \App\Models\Booking::count();
        $usersCount = \App\Models\UserLegacy::count();
        $brandsCount = \App\Models\Brand::count();
        $subscribersCount = \App\Models\Subscriber::count();
        $queriesCount = \App\Models\ContactQuery::count();
        $testimonialsCount = \App\Models\Testimonial::count();
        $recentBookings = \App\Models\Booking::with('vehicle')->orderBy('id','desc')->limit(10)->get();

        return view('admin.dashboard', compact(
            'vehiclesCount','bookingsCount','usersCount','brandsCount',
            'subscribersCount','queriesCount','testimonialsCount','recentBookings'
        ));
    }

    public function showChangePassword()
    {
        if(!session('alogin')){
            return redirect('/admin');
        }
        return view('admin.change-password');
    }

    public function changePassword(Request $request)
    {
        if(!session('alogin')){
            return redirect('/admin');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $username = session('alogin');
        $currentPasswordMd5 = md5($request->current_password);
        $newPasswordMd5 = md5($request->new_password);

        // Check current password
        $admin = DB::table('admin')
            ->where('UserName', $username)
            ->where('Password', $currentPasswordMd5)
            ->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        DB::table('admin')
            ->where('UserName', $username)
            ->update(['Password' => $newPasswordMd5]);

        return redirect()->back()->with('msg', 'Password changed successfully.');
    }

    public function logout()
    {
        session()->forget('alogin');
        return redirect('/admin')->with('msg', 'Logged out successfully.');
    }
}
