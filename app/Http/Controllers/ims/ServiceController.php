<?php

namespace App\Http\Controllers\ims;

use App\Models\ims\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Service::query();

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('hsn_code', 'like', "%{$search}%");
        }

        $services = $query->get();
        return view('ims.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ims.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('ims.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('ims.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
        ]);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
