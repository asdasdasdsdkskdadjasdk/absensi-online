<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ZktecoApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $zktecoApiService;

    public function __construct(ZktecoApiService $zktecoApiService)
    {
        $this->zktecoApiService = $zktecoApiService;
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginId = $request->input('email');
        $password = $request->input('password');

        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                throw ValidationException::withMessages(['email' => __('auth.failed')]);
            }
        } else {
            $this->authenticateEmployeeViaApi($loginId, $password, $request);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->is_admin) {
            return redirect()->intended(route('admin.approvals.index'));
        }

        $user = Auth::user();

    // Jika user login pertama kali (profile_updated_at masih kosong)
    if (! $user->profile_updated_at) {
        return redirect()->route('profile.edit')
            ->with('info', 'Selamat datang! Silakan perbarui email dan password Anda.');
    }
    // -------------------------

        return redirect()->intended(route('absensi.create'));
    }

    protected function authenticateEmployeeViaApi(string $empCode, string $password, Request $request): void
    {
        $user = User::where('emp_code', $empCode)->first();

        if ($user) {
            if (!Hash::check($password, $user->password)) {
                throw ValidationException::withMessages(['email' => 'Password salah.']);
            }
        } else {
            $employeeData = $this->zktecoApiService->getEmployeeDetails($empCode);

            if (!$employeeData) {
                throw ValidationException::withMessages(['email' => 'Kode Karyawan tidak ditemukan.']);
            }
            
            if ($empCode !== $password) {
                throw ValidationException::withMessages(['email' => 'Password harus sama dengan Kode Karyawan untuk login pertama kali.']);
            }

            // --- BAGIAN YANG DIPERBARUI ---
            // Kita tidak lagi menyimpan department_id dan position_id
            $user = User::create([
                'emp_code'        => $empCode,
                'name'            => trim(($employeeData['first_name'] ?? '') . ' ' . ($employeeData['last_name'] ?? '')),
                'email'           => $empCode . '@gmail.com',
                'password'        => Hash::make($password),
                'is_admin'        => false,
            ]);
        }
        
        Auth::login($user, $request->boolean('remember'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

