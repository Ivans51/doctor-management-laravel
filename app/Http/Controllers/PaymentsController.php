<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{

    public function index()
    {
        return view('pages/admin/payments/index');
    }

    /**
     * @param string $id
     * @return View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    function getPayments(string $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $patients = Payment::query()
            ->where('doctor_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages/admin/patients/index')->with([
            'patients' => $patients,
        ]);
    }

    public function create()
    {
        return view('pages/admin/payments/create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('pages/admin/payments/show');
    }

    public function edit($id)
    {
        return view('pages/admin/payments/edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
