<?php

namespace App\Http\Controllers;

use App\Models\CrimeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    /**
     * Show the crime map with approved crime reports.
     * This fulfills requirement CP001: Viewing the Crime Map
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all approved crime reports
        $crimeReports = CrimeReport::with(['crimeType', 'user'])
            ->approved()
            ->latest('occurred_at')
            ->get();

        return view('map.index', compact('crimeReports'));
    }

    /**
     * Show detailed information about a specific crime report.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $crimeReport = CrimeReport::with(['crimeType', 'user'])
            ->approved()
            ->findOrFail($id);

        return view('map.show', compact('crimeReport'));
    }
}
