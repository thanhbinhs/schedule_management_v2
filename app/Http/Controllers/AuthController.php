<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Lấy người dùng hiện tại
            $user = Auth::user();

            // Chuyển hướng dựa trên vai trò
            if ($user->role === 'pdt') {
                return redirect()->route('pdt.departments.index')->with('success', 'Bạn đã đăng nhập thành công!');
            }
            
            if ($user->role === 'department') {
                return redirect()->route('department.professors.index')->with('success', 'Bạn đã đăng nhập thành công!');
            }

            if ($user->role === 'professor') {
                return redirect()->route('professor.schedules.index')->with('success', 'Bạn đã đăng nhập thành công!');
            }
            // Mặc định chuyển hướng đến dashboard chung
            return redirect()->intended('/dashboard')->with('success', 'Bạn đã đăng nhập thành công!');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:pdt,department,professor',
        ]);

        $account = Account::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($account);

        return redirect('/login')->with('success', 'Registration successful!');
    }

    // Xử lý đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'You have been logged out!');
    }
}
