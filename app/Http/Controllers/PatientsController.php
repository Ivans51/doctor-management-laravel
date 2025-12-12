<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientDoctor;
use App\Models\Role;
use App\Models\User;
use App\Models\Chat;
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

        // Add chat_id to each patient
        $patients->getCollection()->transform(function ($patient) use ($doctorId) {
            $user1 = Doctor::query()->with('user')->where('id', $doctorId)->first()?->user;
            $user2 = Patient::query()->with('user')->where('id', $patient->id)->first()?->user;

            if ($user1 && $user2) {
                $chat = Chat::query()
                    ->where(function ($query) use ($user1, $user2) {
                        $query->where('user1_id', $user1->id)
                            ->where('user2_id', $user2->id);
                    })
                    ->orWhere(function ($query) use ($user1, $user2) {
                        $query->where('user1_id', $user2->id)
                            ->where('user2_id', $user1->id);
                    })
                    ->first();

                $patient->chat_id = $chat ? $chat->id : null;
            }

            return $patient;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $patients
        ]);
    }

    /**
     * Show the form for creating a new patient resource.
     *
     * @param Request $request The HTTP request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(Request $request)
    {
        $doctors = Doctor::query()->get();
        $doctor = null;

        if ($request->query('doctorId')) {
            $doctor = Doctor::query()->find($request->query('doctorId'));
        }

        if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN) {
            return view('pages/admin/patients/create')
                ->with('doctors', $doctors)
                ->with('doctor', $doctor);
        }

        return view('pages/web/patients/create');
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
                'full_phone_number' => 'required',
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
                    'phone' => $request->full_phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => Constants::$ACTIVE,
                    'user_id' => $user->valueUuid,
                ]);

            PatientDoctor::query()
                ->create([
                    'patient_id' => $patient->valueUuid,
                    'doctor_id' => $request->doctor_id,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified patient resource.
     *
     * @param mixed $id The ID of the patient to show
     */
    public function show($id) {}

    /**
     * @param $id
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function edit($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $doctors = Doctor::query()->get();
        $patient = Patient::query()
            ->with(['user', 'doctorPatient'])
            ->where('id', $id)
            ->first();

        $doctor = null;
        if (request()->query('doctorId')) {
            $doctor = Doctor::query()->find(request()->query('doctorId'));
        }

        return Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN
            ? view('pages/admin/patients/edit', compact('patient', 'doctors', 'doctor'))
            : view('pages/web/patients/edit', compact('patient'));
    }

    /**
     * Update the specified patient resource in storage.
     *
     * This method handles updating patient information including user credentials,
     * patient details, and doctor assignments. It uses database transactions to
     * ensure data integrity and provides appropriate error handling.
     *
     * @param Request $request The HTTP request containing update data
     * @param mixed $id The ID of the patient to update
     * @return RedirectResponse Redirects back with success or error messages
     * @throws \Throwable If any error occurs during the update process
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|unique:users,email,' . $id,
                'password' => 'nullable|min:8|max:20',
                'full_phone_number' => 'required',
                'location_address-search' => 'required',
                'status' => 'required',
            ]);

            DB::beginTransaction();

            $user = User::query()->where('id', $id)->first();
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
                    'phone' => $request->full_phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => $request->status,
                ]);

            $patient = Patient::query()->where('user_id', $id)->first();

            // Handle doctor assignments
            if ($request->has('doctor_id')) {
                // Single doctor case (from hidden input)
                PatientDoctor::updateOrCreate(
                    ['patient_id' => $patient->id],
                    ['doctor_id' => $request->doctor_id]
                );
            } elseif ($request->has('doctors')) {
                // Multiple doctors case (from checkboxes)
                // First, delete existing associations
                PatientDoctor::where('patient_id', $patient->id)->delete();

                // Create new associations for each selected doctor
                foreach ($request->doctors as $doctorId) {
                    PatientDoctor::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctorId
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
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
