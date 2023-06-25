<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use DB;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index()
    {
        return view('pages/admin/doctors/index');
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;

        if ($request->search) {
            $doctors = Doctor::query()
                ->with(['user'])
                ->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('phone', 'LIKE', "%{$request->search}%")
                ->orWhere('address', 'LIKE', "%{$request->search}%")
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } else {
            $doctors = Doctor::query()
                ->with(['user'])
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $doctors
        ]);
    }

    /**
     * @return View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/admin/doctors/create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:8|max:20|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => Role::query()->where('name', Constants::$DOCTOR)->first()->id,
                'password' => bcrypt($request->password),
            ]);

            Doctor::query()
                ->create([
                    'name' => $request->name,
                    'speciality' => $request->speciality,
                    'phone' => $request->phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => Constants::$ACTIVE,
                    'user_id' => $user->id,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function show($id)
    {
        return view('pages/admin/doctors/show');
    }

    /**
     * @param $id
     * @return View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function edit($id): View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $doctor = Doctor::query()
            ->with(['user'])
            ->where('id', $id)
            ->first();

        return view('pages/admin/doctors/edit', compact('doctor'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validate fields
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'nullable|min:8|max:20',
        ]);

        try {
            DB::beginTransaction();
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

            Doctor::query()
                ->where('user_id', $id)
                ->update([
                    'name' => $request->name,
                    'speciality' => $request->speciality,
                    'phone' => $request->phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => $request->status,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $doctor = Doctor::query()->where('id', $id);

            User::query()
                ->where('id', $doctor->first()->user_id)
                ->delete();

            $doctor->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data deleted successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Data failed to delete',
                'error' => $e->getMessage()
            ]);
        }
    }
}
