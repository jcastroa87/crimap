@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Report a Crime</h4>
                </div>

                <div class="card-body">
                    @guest
                        <div class="alert alert-info mb-4">
                            <strong>Note:</strong> You can fill out this form anonymously, but you will need to
                            <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a>
                            before submitting your report.
                        </div>
                    @endguest

                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <h5>Location Information</h5>
                            <div id="report-map" style="height: 400px; width: 100%; border-radius: 4px;"></div>
                            <div class="text-muted mt-2">
                                Click on the map to pinpoint the location of the incident
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                           id="latitude" name="latitude" value="{{ old('latitude', request('lat')) }}"
                                           readonly required>
                                    @error('latitude')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                           id="longitude" name="longitude" value="{{ old('longitude', request('lng')) }}"
                                           readonly required>
                                    @error('longitude')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Crime Details</h5>

                            <div class="form-group mb-3">
                                <label for="crime_type_id">Crime Type</label>
                                <select class="form-control @error('crime_type_id') is-invalid @enderror"
                                        id="crime_type_id" name="crime_type_id" required>
                                    <option value="">-- Select Crime Type --</option>
                                    @foreach($crimeTypes as $type)
                                        <option value="{{ $type->id }}"
                                            @if(old('crime_type_id', request('type')) == $type->id) selected @endif
                                            data-color="{{ $type->color }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('crime_type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="occurred_at">When did it happen?</label>
                                <input type="datetime-local" class="form-control @error('occurred_at') is-invalid @enderror"
                                       id="occurred_at" name="occurred_at" value="{{ old('occurred_at') }}" required>
                                @error('occurred_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="5" required
                                          placeholder="Please provide a detailed description of what happened...">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text text-muted">
                                    Minimum 10 characters. Please be detailed and accurate.
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Supporting Evidence</h5>
                            <div class="form-group">
                                <label for="media_files">Upload Photos or Documents (Optional)</label>
                                <input type="file" class="form-control @error('media_files') is-invalid @enderror"
                                       id="media_files" name="media_files[]" multiple>
                                @error('media_files')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text text-muted">
                                    Accepted formats: JPEG, PNG, PDF, DOC, DOCX. Maximum 10MB per file.
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                       type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I confirm that the information provided is accurate to the best of my knowledge.
                                </label>
                                @error('terms')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary" @guest disabled @endguest>
                                Submit Report
                            </button>

                            @guest
                                <div class="alert alert-warning mt-3">
                                    <strong>You must be logged in to submit a report.</strong>
                                    <div class="mt-2">
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
                                        <a href="{{ route('register') }}" class="btn btn-sm btn-secondary">Register</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Reporting Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Be accurate and truthful in your report</li>
                        <li>Provide as much detail as possible</li>
                        <li>Include date, time, and exact location</li>
                        <li>Attach photos or documents if available</li>
                        <li>Avoid including personal identifiable information of others</li>
                        <li>Reports are reviewed before appearing on the map</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Need Immediate Help?</h5>
                </div>
                <div class="card-body">
                    <p>If this is an emergency or a crime in progress, please contact emergency services immediately.</p>
                    <div class="d-grid">
                        <a href="tel:911" class="btn btn-danger">Call Emergency Services (911)</a>
                    </div>
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
        var defaultLat = {{ old('latitude', request('lat', 40.7128)) }};
        var defaultLng = {{ old('longitude', request('lng', -74.0060)) }};

        var map = L.map('report-map').setView([defaultLat, defaultLng], 13);

        // Add the OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);

        // Add marker if coordinates already exist
        var marker;
        if (defaultLat != 40.7128 || defaultLng != -74.0060) {
            marker = L.marker([defaultLat, defaultLng]).addTo(map);
        }

        // Update marker and form fields on map click
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Update form fields
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);

            // Update or create marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Update marker color based on selected crime type
            var crimeTypeSelect = document.getElementById('crime_type_id');
            if (crimeTypeSelect.selectedIndex > 0) {
                var selectedOption = crimeTypeSelect.options[crimeTypeSelect.selectedIndex];
                var color = selectedOption.getAttribute('data-color') || '#ff0000';

                // For future enhancement: update marker icon based on crime type
            }
        });

        // Try to get user's current location for a better starting point
        if (navigator.geolocation && !marker) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;

                map.setView([userLat, userLng], 15);
            }, function(error) {
                console.log("Error getting user location:", error);
            });
        }
    });
</script>
@endpush
