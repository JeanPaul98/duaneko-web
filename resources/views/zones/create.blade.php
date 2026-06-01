@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Zones</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Zones</a></li>
                                <li class="breadcrumb-item">Créer une zone</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12">

                    <div class="card">

                        <div class="card-header">
                            <h5>Créer une zone</h5>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Oops!</strong> Veuillez remplir tous les champs svp.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <form action="{{ route('zones.store') }}" method="POST">
                                @csrf
                                <div class="input-group input-group-button mb-3">
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Nom de la zone">
                                </div>
                                <div class="input-group input-group-button mb-3">
                                    <input type="color" id="color" name="color" class="form-control"
                                        placeholder="Couleur de la zone">
                                </div>
                                <div id="map" class="mb-3" style="height: 50vh;"></div>
                                <input type="hidden" id="northeast_latitude" name="northeast_latitude">
                                <input type="hidden" id="northeast_longitude" name="northeast_longitude">
                                <input type="hidden" id="southwest_latitude" name="southwest_latitude">
                                <input type="hidden" id="southwest_longitude" name="southwest_longitude">
                                <input type="hidden" id="google_map_name" name="google_map_name">

                                <button type="submit" class="btn  btn-primary">Enregistrez</button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    window.onload = function() {

        function success(position) {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            let map = window.L.map('map').setView([latitude, longitude], 12);
            window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // https://github.com/perliedman/leaflet-control-geocoder
            var geocoder = window.L.Control.geocoder({
                    defaultMarkGeocode: false
                })
                .on('markgeocode', function(e) {

                    console.log(e);

                    var bbox = e.geocode.bbox;
                    var poly = window.L.polygon([
                        bbox.getSouthEast(),
                        bbox.getNorthEast(),
                        bbox.getNorthWest(),
                        bbox.getSouthWest()
                    ]).addTo(map);

                    document.getElementById('southwest_latitude').value = bbox._southWest.lat;
                    document.getElementById('southwest_longitude').value = bbox._southWest.lng;
                    document.getElementById('northeast_latitude').value = bbox._northEast.lat;
                    document.getElementById('northeast_longitude').value = bbox._northEast.lng;
                    document.getElementById('google_map_name').value = e.geocode.name;

                    map.fitBounds(poly.getBounds());
                })
                .addTo(map);
        }

        function error() {
            let map = document.getElementById("map");
            let p = document.createElement("p");
            let textDescription = document.createTextNode("Impossible de récupérer votre position.");
            p.appendChild(textDescription);
            map.appendChild(p);
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error);
        }
    };
</script>
