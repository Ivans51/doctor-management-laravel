<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientsController extends Controller
{

    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    function getPatients(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $patients = Patient::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages/admin/patients/index')->with([
            'patients' => $patients,
        ]);
    }

    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $patients = Patient::query()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages/admin/patients/index')->with([
            'patients' => $patients,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexLimit(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;

        $admins = Patient::query()
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $admins
        ]);
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;

        $users = Patient::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('email', 'LIKE', "%{$request->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $users
        ]);
    }

    public function create()
    {
        return view('pages/admin/patients/create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('pages/admin/patients/show');
    }

    public function edit($id)
    {
        return view('pages/admin/patients/edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $doctor = Doctor::query()->where('id', $id);

            User::query()->where('id', $doctor->first()->user_id)->delete();

            $doctor->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete');
        }
    }
}
