<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập và có role 'professor' hay không
        if (Auth::check() && Auth::user()->role === 'professor') {
            return $next($request);
        }

        // Nếu không phải 'professor', chuyển hướng về trang login với thông báo lỗi
        return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập vào trang này.');
    }
}