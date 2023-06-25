<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class
UserController extends Controller
{
    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('pages.admin.admins.index');
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchUser(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;
        $id = Role::query()->where('name', Constants::$ADMIN)->first()->id;

        if ($request->search) {
            $users = User::query()
                ->where('role_id', $id)
                ->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%")
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } else {
            $users = User::query()
                ->where('role_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $users
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        return response()->json($user);
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return view('pages.admin.admins.create');
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): Factory|View|Application
    {
        $user = User::query()->findOrFail($id);
        return view('pages.admin.admins.edit', compact('user'));
    }

    /**
     * Create user and password with bcrypt
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:20|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => Role::query()->where('name', Constants::$ADMIN)->first()->id,
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
            'name' => 'required|min:3',
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
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::query()->where('id', $id);

            Doctor::query()->where('user_id', $id)->delete();
            Patient::query()->where('user_id', $id)->delete();

            $user->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'User failed to delete',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}
