@extends('layouts.app')

@section('content')
<div class="map-container">
    <div id="crime-map" style="height: 600px; width: 100%;"></div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h3>Recent Crime Reports</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crimeReports as $report)
                            <tr>
                                <td>
                                    @if($report->crimeType->icon)
                                        <span style="color: {{ $report->crimeType->color ?? '#000' }}">
                                            <i class="{{ $report->crimeType->icon }}"></i>
                                        </span>
                                    @endif
                                    {{ $report->crimeType->name }}
                                </td>
                                <td>{{ $report->occurred_at->format('M d, Y') }}</td>
                                <td>{{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}</td>
                                <td>
                                    <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No crime reports available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Report a Crime</h5>
                </div>
                <div class="card-body">
                    <p>Have you witnessed or been a victim of a crime? Help keep your community safe by reporting it.</p>
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">Report Now</a>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Crime Types</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach(App\Models\CrimeType::where('is_active', true)->get() as $crimeType)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    @if($crimeType->icon)
                                        <span style="color: {{ $crimeType->color ?? '#000' }}">
                                            <i class="{{ $crimeType->icon }}"></i>
                                        </span>
                                    @endif
                                    {{ $crimeType->name }}
                                </span>
                                <span class="badge rounded-pill" style="background-color: {{ $crimeType->color ?? '#6c757d' }}">
                                    {{ $crimeReports->where('crime_type_id', $crimeType->id)->count() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map
        var map = L.map('crime-map').setView([40.7128, -74.0060], 12); // Default to NYC

        // Add the OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);

        // Define crime reports data
        var crimeReports = @json($crimeReports->map(function($report) {

        }));

        // Add markers for each crime report
        var markers = [];
        var bounds = L.latLngBounds();

        crimeReports.forEach(function(report) {
            var marker = L.circleMarker([report.lat, report.lng], {
                radius: 8,
                fillColor: report.color,
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            });

            marker.bindPopup(
                '<strong>' + report.type + '</strong><br>' +
                '<small>' + report.date + '</small><br>' +
                '<p>' + report.description + '</p>' +
                '<a href="' + report.url + '" class="btn btn-sm btn-primary">View Details</a>'
            );

            marker.addTo(map);
            markers.push(marker);
            bounds.extend([report.lat, report.lng]);
        });

        // Fit the map to show all markers if there are any
        if (markers.length > 0) {
            map.fitBounds(bounds);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .map-container {
        position: relative;
        width: 100%;
        height: 600px;
        margin-bottom: 2rem;
    }

    #crime-map {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
