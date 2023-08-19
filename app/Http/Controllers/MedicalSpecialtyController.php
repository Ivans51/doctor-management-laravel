<?php

namespace App\Http\Controllers;

use App\Models\MedicalSpecialty;
use App\Utils\Constants;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalSpecialtyController extends Controller
{
    public function index()
    {
        return view('pages/admin/medical/index');
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
            $doctors = MedicalSpecialty::query()
                ->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('description', 'LIKE', "%{$request->search}%")
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        } else {
            $doctors = MedicalSpecialty::query()
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
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/admin/medical/create');
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
                'price' => 'required|numeric',
            ]);

            MedicalSpecialty::query()
                ->create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'currency' => $request->input('currency', Constants::$CURRENCY_USD),
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Created successfully');
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
        $medical = MedicalSpecialty::query()
            ->where('id', $id)
            ->first();

        return view('pages/admin/medical/edit', compact('medical'));
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
            'price' => 'required|numeric',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();

            MedicalSpecialty::query()
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'currency' => $request->input('currency', Constants::$CURRENCY_USD),
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

            $medicalSpecialty = MedicalSpecialty::query()->where('id', $id);

            $medicalSpecialty->delete();

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
