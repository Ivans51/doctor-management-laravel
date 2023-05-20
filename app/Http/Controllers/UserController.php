<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create user and password with bcrypt
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3|max:20',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:20',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => Role::query()->where('name', 'admin')->first()->id,
            'password' => bcrypt($request->password),
        ]);
        return redirect()->back()->with('success', 'User created successfully');
    }

    /**
     * Update user and check is fiel password exist with bcrypt
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validate fields
        $request->validate([
            'name' => 'required|min:3|max:20',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'nullable|min:8|max:20',
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }
        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchUser(Request $request): JsonResponse
    {
        $users = User::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('email', 'LIKE', "%{$request->search}%")
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $users
        ]);
    }

}
