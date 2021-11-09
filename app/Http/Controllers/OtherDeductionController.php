<?php

namespace App\Http\Controllers;

use App\Models\OtherDeduction;
use Illuminate\Http\Request;
use App\Http\Requests\DeductionStoreRequest;

class OtherDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeductionStoreRequest $request)
    {
        $deduction = OtherDeduction::create($request->validated());

        return redirect()->route('deducciones.index')->with(['message' => 'Prestamo agregado con éxito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OtherDeduction  $otherDeduction
     * @return \Illuminate\Http\Response
     */
    public function show(OtherDeduction $otherDeduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OtherDeduction  $otherDeduction
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherDeduction $otherDeduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OtherDeduction  $otherDeduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherDeduction $otherDeduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OtherDeduction  $otherDeduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherDeduction $otherDeduction)
    {
        //
    }
}
