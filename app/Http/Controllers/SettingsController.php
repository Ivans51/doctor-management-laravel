<?php

namespace App\Http\Controllers;

use App\Models\DoctorMedicalSpecialty;
use App\Models\MedicalSpecialty;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function getSettings(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user()->doctor;

        $specialties = MedicalSpecialty::query()
            ->get();

        $mySpecialties = MedicalSpecialty::query()
            ->whereHas('doctorMedicalSpecialty', function ($query) use ($user) {
                $query->where('doctor_id', $user->id);
            })
            ->get();

        return view('pages/web/settings/index')->with([
            'user' => $user,
            'medicalSpecialties' => $specialties,
            'myMedicalSpecialties' => $mySpecialties,
        ]);
    }

    public function getChangePassword(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user()->doctor;

        return view('pages/web/settings/change-password')->with([
            'user' => $user,
        ]);
    }

    public function getNotifications(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user()->doctor;

        return view('pages/web/settings/notifications')->with([
            'user' => $user,
            'notifications' => [],
        ]);
    }

    public function getReviews(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user()->doctor;

        return view('pages/web/settings/reviews')->with([
            'user' => $user,
            'reviews' => [],
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function updateProfileDoctor(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'address_address-search' => 'required',
                'specialties' => 'required',
            ]);

            DB::beginTransaction();

            $specialties = $request->specialties;

            $doctor = Auth::user()->doctor;
            $doctor->update([
                'name' => $request->name,
                'address' => $request->{'address_address-search'},
            ]);

            $user = Auth::user();
            $user->update([
                'email' => $request->email,
            ]);

            // Update doctor medical specialty only if there is a change
            DoctorMedicalSpecialty::query()
                ->where('doctor_id', $doctor->id)
                ->delete();

            foreach ($specialties as $specialty) {
                DoctorMedicalSpecialty::query()->create([
                    'doctor_id' => $doctor->id,
                    'medical_specialty_id' => $specialty,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Profile updated successfully');

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'password' => 'required|min:8|max:20|confirmed',
            ]);

            $user = User::query()->find(Auth::user()->id);
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back()->with('success', 'Password changed successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
