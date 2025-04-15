<?php

namespace App\Http\Controllers;

use App\Models\CrimeType;
use App\Models\CrimeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CrimeReportController extends Controller
{
    /**
     * Constructor that applies middleware.
     */
    // public function __construct()
    // {
    //     // Apply auth middleware only to store to allow anonymous form viewing
    //     parent::__construct();
    //     $this->middleware('auth')->only(['store']);
    // }

    /**
     * Show the form for creating a new crime report.
     * This fulfills requirement CP002: Adding a Crime Report (Pin)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $crimeTypes = CrimeType::where('is_active', true)->get();
        return view('reports.create', compact('crimeTypes'));
    }

    /**
     * Store a newly created crime report in storage.
     * This fulfills requirements CP002 and CP003: User Registration for Report Submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'crime_type_id' => 'required|exists:crime_types,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'required|string|min:10|max:1000',
            'occurred_at' => 'required|date|before_or_equal:now',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:10240',
        ]);

        // Process and store uploaded files
        $mediaFiles = [];
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('crime-reports', 'public');
                $mediaFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }

        // Create the crime report
        $crimeReport = new CrimeReport([
            'user_id' => Auth::id(),
            'crime_type_id' => $validated['crime_type_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'description' => $validated['description'],
            'occurred_at' => $validated['occurred_at'],
            'status' => 'pending', // All reports start as pending for admin review
            'media_files' => $mediaFiles,
        ]);

        $crimeReport->save();

        return redirect()->route('home')->with('success', 'Your crime report has been submitted and is pending review.');
    }
}
