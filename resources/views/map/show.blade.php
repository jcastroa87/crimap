@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>
                        @if($crimeReport->crimeType->icon)
                            <i class="{{ $crimeReport->crimeType->icon }}" style="color: {{ $crimeReport->crimeType->color ?? '#000' }}"></i>
                        @endif
                        {{ $crimeReport->crimeType->name }} Report
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div id="report-map" style="height: 400px; width: 100%; border-radius: 4px;"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Reported By:</strong> {{ $crimeReport->user->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Occurred On:</strong> {{ $crimeReport->occurred_at->format('F d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Coordinates:</strong> {{ number_format($crimeReport->latitude, 6) }}, {{ number_format($crimeReport->longitude, 6) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Reported On:</strong> {{ $crimeReport->created_at->format('F d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="description mb-4">
                        <h5>Description</h5>
                        <p>{{ $crimeReport->description }}</p>
                    </div>

                    @if(!empty($crimeReport->media_files))
                        <div class="media-files mb-4">
                            <h5>Attached Media</h5>
                            <div class="row">
                                @foreach($crimeReport->media_files as $file)
                                    <div class="col-md-4 mb-3">
                                        @if(strpos($file['mime_type'], 'image/') === 0)
                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" class="img-fluid rounded">
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-file"></i> {{ $file['original_name'] }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Map
                    </a>

                    <div class="float-end">
                        <button class="btn btn-outline-danger" onclick="reportInaccuracy()">
                            <i class="fas fa-flag"></i> Report Inaccuracy
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Report a Similar Crime</h5>
                </div>
                <div class="card-body">
                    <p>Have you witnessed a similar incident in this area? Help keep your community informed.</p>
                    <a href="{{ route('reports.create') }}?lat={{ $crimeReport->latitude }}&lng={{ $crimeReport->longitude }}&type={{ $crimeReport->crime_type_id }}" class="btn btn-primary">Report Similar Crime</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Nearby Crime Reports</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @php
                            $nearbyReports = \App\Models\CrimeReport::approved()
                                ->where('id', '!=', $crimeReport->id)
                                ->whereRaw("(
                                    6371 * acos(
                                        cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                                        sin(radians(?)) * sin(radians(latitude))
                                    )
                                ) <= 5", [$crimeReport->latitude, $crimeReport->longitude, $crimeReport->latitude])
                                ->with('crimeType')
                                ->latest('occurred_at')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($nearbyReports as $nearby)
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        @if($nearby->crimeType->icon)
                                            <i class="{{ $nearby->crimeType->icon }}" style="color: {{ $nearby->crimeType->color ?? '#000' }}"></i>
                                        @endif
                                        {{ $nearby->crimeType->name }}
                                    </h6>
                                    <small>{{ $nearby->occurred_at->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-1">{{ \Illuminate\Support\Str::limit($nearby->description, 80) }}</p>
                                <small>
                                    <a href="{{ route('reports.show', $nearby->id) }}">View details</a>
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item">No nearby crime reports found</li>
                        @endforelse
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
        var map = L.map('report-map').setView([{{ $crimeReport->latitude }}, {{ $crimeReport->longitude }}], 15);

        // Add the OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);

        // Add marker for this crime report
        L.circleMarker([{{ $crimeReport->latitude }}, {{ $crimeReport->longitude }}], {
            radius: 10,
            fillColor: "{{ $crimeReport->crimeType->color ?? '#ff0000' }}",
            color: "#000",
            weight: 1,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);

        // Add nearby reports markers
        @php
            $nearbyReports = \App\Models\CrimeReport::approved()
                ->where('id', '!=', $crimeReport->id)
                ->whereRaw("(
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) <= 5", [$crimeReport->latitude, $crimeReport->longitude, $crimeReport->latitude])
                ->with('crimeType')
                ->latest('occurred_at')
                ->limit(10)
                ->get();
        @endphp

        @foreach($nearbyReports as $nearby)
            L.circleMarker([{{ $nearby->latitude }}, {{ $nearby->longitude }}], {
                radius: 6,
                fillColor: "{{ $nearby->crimeType->color ?? '#ff0000' }}",
                color: "#000",
                weight: 1,
                opacity: 0.7,
                fillOpacity: 0.5
            }).bindPopup(
                '<strong>{{ $nearby->crimeType->name }}</strong><br>' +
                '<small>{{ $nearby->occurred_at->format('M d, Y') }}</small><br>' +
                '<p>{{ \Illuminate\Support\Str::limit($nearby->description, 100) }}</p>' +
                '<a href="{{ route('reports.show', $nearby->id) }}" class="btn btn-sm btn-primary">View Details</a>'
            ).addTo(map);
        @endforeach
    });

    function reportInaccuracy() {
        alert('Thank you for helping maintain the quality of our crime reports. This feature will be available soon.');
    }
</script>
@endpush
