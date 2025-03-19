<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentMembership;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
            'status' => 'required|in:paid,unpaid',
        ]);

        $membership = StudentMembership::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'month' => $request->month,
                'year' => $request->year,
            ]
        );

        $membership->status = $request->status;
        $membership->save();

        return response()->json(['success' => true, 'message' => 'Stato aggiornato con successo!']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
