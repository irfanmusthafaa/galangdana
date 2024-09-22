<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFundraisingWithDrawalRequest;
use App\Models\Fundraising;
use App\Models\FundraisingWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FundraisingWithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $fundraisingWithdrawals = FundraisingWithdrawal::orderByDesc('id')->get();
        return view('admin.fundraising_withdrawals.index', compact('fundraisingWithdrawals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFundraisingWithDrawalRequest $request, Fundraising $fundraising)
    {
        //cek apakah pernah melakukan with drawal sebelumnya
        $hasRequestDrawal = $fundraising->withDrawals()->exists();

        if ($hasRequestDrawal) {
            return redirect()->route('admin.fundraisings.show', $fundraising);
        }
        DB::transaction(function () use ($request, $fundraising) {

            $validated = $request->validated();

            $validated['fundraiser_id'] = Auth::user()->fundraiser->id;
            $validated['has_received'] = false;
            $validated['has_sent'] = false;
            $validated['amount_requested'] = $fundraising->totalReachedAmount();
            $validated['amount_received'] = 0;
            $validated['proof'] = 'proofs/deliveryDummy.png';

            $fundraising->withDrawals()->create($validated);
        });

        return redirect()->route('admin.my-withdrawals');
    }

    /**
     * Display the specified resource.
     */
    public function show(FundraisingWithdrawal $fundraisingWithdrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FundraisingWithdrawal $fundraisingWithdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FundraisingWithdrawal $fundraisingWithdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FundraisingWithdrawal $fundraisingWithdrawal)
    {
        //
    }
}
