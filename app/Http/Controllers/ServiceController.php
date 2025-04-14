<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

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
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
        ]);

        // Create a new service
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
        ]);

        // Redirect back with a success message
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        // Return the view with the specific service data.
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'hsn_code' => 'required|max:20',
            'gst_percentage' => 'required|numeric',
        ]);

        // Update the service with the new data.
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'hsn_code' => $request->hsn_code,
            'gst_percentage' => $request->gst_percentage,
        ]);

        // Redirect back with a success message.
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete the service.
        $service->delete();

        // Redirect back with a success message.
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
