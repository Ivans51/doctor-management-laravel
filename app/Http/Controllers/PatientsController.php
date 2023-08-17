<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDoctor;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use Auth;
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
     * @param Request $request
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $doctorId = $request->query('doctorId', '');
        return view('pages/admin/patients/index', compact('doctorId'));
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $doctorId = $request->query('doctorId', '');
        return $this->getPatients($request, $doctorId);
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByDoctor(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $doctorId = Doctor::query()
            ->where('user_id', $userId)
            ->first()
            ->id;

        return $this->getPatients($request, $doctorId);
    }

    /**
     * @param Request $request
     * @param string $doctorId
     * @return JsonResponse
     */
    private function getPatients(Request $request, string $doctorId): JsonResponse
    {
        $limit = $request->query('limit') ?? 10;

        if ($request->query('search')) {
            $patients = Patient::query()
                ->when($doctorId, function ($query, $doctorId) {
                    $query->whereHas('doctorPatient', function ($query) use ($doctorId) {
                        $query->where('doctor_id', $doctorId);
                    });
                })
                ->with([
                    'doctorPatient',
                    'doctorPatient.doctor',
                ])
                ->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('phone', 'LIKE', "%{$request->search}%")
                ->orWhere('address', 'LIKE', "%{$request->search}%")
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } else {
            $patients = Patient::query()
                ->when($doctorId, function ($query, $doctorId) {
                    $query->whereHas('doctorPatient', function ($query) use ($doctorId) {
                        $query->where('doctor_id', $doctorId);
                    });
                })
                ->with([
                    'doctorPatient',
                    'doctorPatient.doctor',
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $patients
        ]);
    }

    public function create()
    {
        $doctors = Doctor::query()->get();

        return Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN
            ? view('pages/admin/patients/create')->with('doctors', $doctors)
            : view('pages/web/patients/create');
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
                'phone_number' => 'required',
                'location_address-search' => 'required',
                'doctor_id' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => Role::query()->where('name', Constants::$PATIENT)->first()->id,
                'password' => bcrypt($request->password),
            ]);

            $patient = Patient::query()
                ->create([
                    'name' => $request->name,
                    'phone' => $request->phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => Constants::$ACTIVE,
                    'user_id' => $user->id,
                ]);

            PatientDoctor::query()
                ->create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $request->doctor_id,
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

    }

    /**
     * @param $id
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function edit($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $doctors = Doctor::query()->get();
        $patient = Patient::query()
            ->with(['user'])
            ->where('id', $id)
            ->first();

        return Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN
            ? view('pages/admin/patients/edit', compact('patient', 'doctors'))
            : view('pages/web/patients/edit', compact('patient'));
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            // validate fields
            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|unique:users,email,' . $id,
                'password' => 'nullable|min:8|max:20',
                'phone_number' => 'required',
                'location_address-search' => 'required',
                'status' => 'required',
            ]);

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

            Patient::query()
                ->where('user_id', $id)
                ->update([
                    'name' => $request->name,
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

            $patient = Patient::query()->where('id', $id);

            User::query()
                ->where('id', $patient->first()->user_id)
                ->delete();

            $patient->delete();

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
            ], 400);
        }
    }
}
