<?php

namespace App\Http\Controllers;

use App\Models\ComplainStatusLog;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintStatusLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        dd($request->all());
        $complaint = Complaint::findorfail($request->id);
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
    public function show(ComplainStatusLog $complainStatusLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ComplainStatusLog $complainStatusLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ComplainStatusLog $complainStatusLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComplainStatusLog $complainStatusLog)
    {
        //
    }
}
