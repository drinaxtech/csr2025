<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParameterController extends Controller
{
    /**
     * Display a listing of the application parameters.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $parameters = Parameter::all();
        return Inertia::render('Admin/Parameters/Index', [
            'parameters' => $parameters,
        ]);
    }

    /**
     * Show the form for editing the specified parameter.
     *
     * @param  \App\Models\Parameter  $parameter
     * @return \Inertia\Response
     */
    public function edit(Parameter $parameter)
    {
        return Inertia::render('Admin/Parameters/Edit', [
            'parameter' => $parameter,
        ]);
    }

    /**
     * Update the specified parameter in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parameter  $parameter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Parameter $parameter)
    {
        $request->validate([
            'value' => 'required|string|max:255', // Adjust validation as needed
        ]);

        $parameter->update([
            'value' => $request->value,
        ]);

        return redirect()->route('admin.parameters.index')->with('success', 'Parameter updated successfully.');
    }

    // You might also need create and store methods for new parameters
}