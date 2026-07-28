<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Models\User;
use App\Models\ActivityLog;
use App\Mail\LoginOtpMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $settings = Setting::getSettings();
        return view('admin.auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Check if credentials are valid without logging in
        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();

            // Check if OTP is enabled in env
            if (env('ENABLE_OTP_LOGIN', false)) {
                $this->generateAndSendOtp($user);
                
                $request->session()->put('otp_user_id', $user->id);
                $request->session()->put('otp_remember', $remember);
                
                return redirect()->route('admin.login.otp')->with('success', 'Please check your email for the OTP code.');
            }

            // Standard login if OTP is disabled
            Auth::login($user, $remember);
            $request->session()->regenerate();

            ActivityLog::create([
                'action' => 'Login',
                'model_type' => User::class,
                'model_id' => $user->id,
                'user_id' => $user->id,
                'description' => 'User logged in to the admin panel.',
                'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
            ]);

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login');
        }

        $settings = Setting::getSettings();
        return view('admin.auth.verify-otp', compact('settings'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        $userId = $request->session()->get('otp_user_id');
        $cachedOtp = Cache::get('login_otp_' . $userId);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.']);
        }

        // OTP is correct
        $user = User::find($userId);
        $remember = $request->session()->get('otp_remember', false);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        
        // Clean up
        Cache::forget('login_otp_' . $userId);
        $request->session()->forget(['otp_user_id', 'otp_remember']);

        ActivityLog::create([
            'action' => 'Login',
            'model_type' => User::class,
            'model_id' => $user->id,
            'user_id' => $user->id,
            'description' => 'User logged in to the admin panel via OTP.',
            'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
        ]);

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
    }

    public function resendOtp(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        $this->generateAndSendOtp($user);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    private function generateAndSendOtp(User $user)
    {
        $cacheKey = 'login_otp_' . $user->id;
        
        // Reuse existing active OTP or generate a new one
        if (Cache::has($cacheKey)) {
            $otp = Cache::get($cacheKey);
        } else {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // Cache OTP for 5 minutes
            Cache::put($cacheKey, $otp, now()->addMinutes(5));
        }

        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            // Log error or ignore if mail is not configured yet
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::create([
                'action' => 'Logout',
                'model_type' => User::class,
                'model_id' => $user->id,
                'user_id' => $user->id,
                'description' => 'User logged out of the admin panel.',
                'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
