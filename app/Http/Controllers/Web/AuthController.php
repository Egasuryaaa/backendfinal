<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Check if user has valid Bearer token
        $token = $this->getTokenFromRequest($request);
        if ($token && $this->isValidToken($token)) {
            return redirect()->route('fishmarket');
        }
        
        return view('auth.login');
    }

    public function showRegisterForm(Request $request)
    {
        // Check if user has valid Bearer token
        $token = $this->getTokenFromRequest($request);
        if ($token && $this->isValidToken($token)) {
            return redirect()->route('fishmarket');
        }
        
        return view('auth.register');
    }

    /**
     * Get token from request (cookie, header, or parameter)
     */
    private function getTokenFromRequest(Request $request)
    {
        // Check Authorization header
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }

        // Check cookie
        if ($request->hasCookie('auth_token')) {
            return $request->cookie('auth_token');
        }

        // Check URL parameter
        if ($request->has('token')) {
            return $request->get('token');
        }

        return null;
    }

    /**
     * Validate if token is still valid
     */
    private function isValidToken($token)
    {
        try {
            $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            
            if (!$accessToken || !$accessToken->tokenable) {
                return false;
            }

            // Check if token is expired
            if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
                $accessToken->delete();
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('fishmarket'))->with('success', 'Login berhasil!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|min:10|max:15',
            'password' => 'required|string|min:6|confirmed',
            'agree_terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 2 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'phone.max' => 'Nomor telepon maksimal 15 digit',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'agree_terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            // Auto login after registration
            Auth::login($user);

            return redirect()->route('fishmarket')->with('success', 'Registrasi berhasil! Selamat datang di IwakMart.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.')
                ->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    public function logout(Request $request)
    {
        \Log::info('Logout request received', [
            'user' => auth()->user() ? auth()->user()->email : 'Not authenticated',
            'request_headers' => $request->headers->all(),
            'request_method' => $request->method(),
            'is_ajax' => $request->ajax()
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('Logout completed successfully');

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil!',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
