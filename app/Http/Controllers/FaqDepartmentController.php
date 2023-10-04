<?php

namespace App\Http\Controllers;

use App\Models\FaqDepartment;
use App\Http\Requests\StoreFaqDepartmentRequest;
use App\Http\Requests\UpdateFaqDepartmentRequest;

class FaqDepartmentController extends Controller
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
     * @param  \App\Http\Requests\StoreFaqDepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFaqDepartmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FaqDepartment  $faqDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(FaqDepartment $faqDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FaqDepartment  $faqDepartment
     * @return \Illuminate\Http\Response
     */
    public function edit(FaqDepartment $faqDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFaqDepartmentRequest  $request
     * @param  \App\Models\FaqDepartment  $faqDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFaqDepartmentRequest $request, FaqDepartment $faqDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FaqDepartment  $faqDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(FaqDepartment $faqDepartment)
    {
        //
    }
}
