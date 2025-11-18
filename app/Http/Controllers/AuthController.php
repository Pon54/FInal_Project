<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLegacy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'fullname' => 'required',
            'emailid' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'FullName' => $r->fullname,
            'EmailId' => $r->emailid,
            'ContactNo' => $r->mobileno ?? null,
            // legacy used md5; we store bcrypt but keep field name
            'Password' => bcrypt($r->password),
        ]);

        $r->session()->flash('msg', 'Registration successful. You can login now.');
        return redirect()->back();
    }

    public function login(Request $r)
    {
        $r->validate(['email' => 'required|email','password' => 'required']);
        $user = User::where('EmailId', $r->email)->first();
        if ($user) {
            $stored = $user->Password;
            $input = $r->password;
            $ok = false;

            // If stored password looks like bcrypt/argon (starts with $2y$/$2a$/$2b$), use Hash::check
            if (Str::startsWith($stored, ['$2y$','$2a$','$2b$','$argon$'])) {
                $ok = Hash::check($input, $stored);
            }
            // If stored password looks like legacy md5 (32 hex chars), compare md5
            elseif (preg_match('/^[0-9a-f]{32}$/i', $stored)) {
                if (md5($input) === $stored) {
                    $ok = true;
                    // upgrade legacy md5 to bcrypt for better security
                    $user->Password = bcrypt($input);
                    $user->save();
                    // flash a message for migration visibility and log it
                    $r->session()->flash('msg', 'Password migrated to secure hash for ' . $user->EmailId);
                    Log::info('Upgraded legacy MD5 password to bcrypt for user: ' . $user->EmailId);
                }
            }
            else {
                // last resort: try PHP password_verify, wrapped to avoid exceptions
                try {
                    $ok = password_verify($input, $stored);
                } catch (\Throwable $e) {
                    $ok = false;
                }
            }

            if ($ok) {
                // Use Laravel's built-in authentication
                Auth::login($user);
                $r->session()->flash('msg', 'Logged in successfully');
                return redirect()->back();
            }
        }
        $r->session()->flash('error', 'Invalid credentials');
        return redirect()->back();
    }

    public function forgot(Request $r)
    {
        // placeholder: implement reset flow with tokens/emails
        $r->session()->flash('error', 'Password reset not implemented yet.');
        return redirect()->back();
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        $r->session()->flash('msg', 'Logged out successfully.');
        return redirect('/');
    }
}
