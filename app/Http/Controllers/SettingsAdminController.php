<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsAdminController extends Controller
{
    public function getSettings(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();

        return view('pages/admin/settings/index')->with([
            'user' => $user,
        ]);
    }

    public function getChangePassword(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();

        return view('pages/admin/settings/change-password')->with([
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function updatePatient(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . Auth::id(),
            ]);

            DB::beginTransaction();

            $user = Auth::user();
            $user->update([
                'email' => $request->email,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Profile updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
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
                'current_password' => 'required',
                'password' => 'required|min:8|max:20|confirmed|different:current_password',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back()->with('success', 'Password changed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
