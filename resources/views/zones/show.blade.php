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
                                <h5 class="m-b-10">Details de la zone</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                                <li class="breadcrumb-item">Details de la zone</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12">

                    <div class="card">
                        <div class="card-body table-border-style">

                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Details</h5>
                                </div>
                                <div class="col-sm-6">
                                    <a class="btn btn-success btn-sm btn-round mb-3" href="{{ route('zones.index') }}"><i
                                            class="feather icon-plus"></i> Retour</a>
                                </div>
                            </div>

                        </div>

                        <div class="card-body table-border-style">

                            <div class="input-group input-group-button mb-3">
                                <input type="text" class="form-control" value="{{ $zone->name }}" disabled>
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="text" class="form-control" value="{{ $zone->google_map_name }}" disabled>
                            </div>

                            <div id="map" class="mb-3" style="height: 50vh;"></div>
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

            const zone = <?php echo json_encode($zone); ?>;

            zone.northeast_latitude
            zone.northeast_longitude
            zone.southwest_latitude
            zone.southwest_longitude

            let currentZonePolygonCoords = [
                [zone.northeast_latitude, zone.northeast_longitude],
                [zone.southwest_latitude, zone.northeast_longitude],
                [zone.southwest_latitude, zone.southwest_longitude],
                [zone.northeast_latitude, zone.southwest_longitude]
            ];

            var currentZonePolygon = L.polygon(currentZonePolygonCoords, {
                color: "blue"
            }).addTo(map);
            map.fitBounds(currentZonePolygon.getBounds());

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
