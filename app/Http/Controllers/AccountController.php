<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    // Hiển thị danh sách tài khoản
    public function index()
    {
        $accounts = Account::all();
        return response()->json($accounts);
    }

    // Hiển thị thông tin tài khoản cụ thể
    public function show($username)
    {
        $account = Account::find($username);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }
        return response()->json($account);
    }

    // Tạo tài khoản mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:accounts,username',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['pdt', 'department', 'professor'])],
        ]);

        $account = Account::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json($account, 201);
    }

    // Cập nhật tài khoản
    public function update(Request $request, $username)
    {
        $account = Account::find($username);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $validated = $request->validate([
            'password' => 'sometimes|string|min:6',
            'role' => ['sometimes', Rule::in(['pdt', 'department', 'professor'])],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $account->update($validated);
        return response()->json($account);
    }

    // Xóa tài khoản
    public function destroy($username)
    {
        $account = Account::find($username);
        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $account->delete();
        return response()->json(['message' => 'Account deleted successfully']);
    }
}
