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
                            <h5 class="m-b-10">Ramassages</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Ramassages</a></li>
                            <li class="breadcrumb-item">Créer un ramassage</li>
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
                        <form action="{{ route('ramassages.store') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-button mb-3">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nom du ramassage">
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="date" id="date_de_ramassage" name="date_de_ramassage" class="form-control">
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="time" id="heure_de_ramassage" name="heure_de_ramassage" class="form-control">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="3" name="description" id="description" placeholder="Description"></textarea>
                            </div>

                            <div class="form-group row  d-flex">
                                <label class="col-3 col-form-label">Cocher les agents à affecter</label>
                                <div class="col-9">
                                    @foreach ($agents as $agent)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="agents[]" value="{{ $agent->id }}" id="agent-{{ $agent->id }}">
                                        <label class="form-check-label" for="customCheckinlh1">
                                            {{ $agent->full_name() }}
                                        </label>
                                    </div>
                                    @endforeach
                                    
                                    <section class="tags form-control" id="tags" name="agents[]" multiple="multiple">

                                    </section>

                                </div>
                            </div>

                            <div id="map" class="mb-3" style="height: 50vh;"></div>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">



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

            const reports = <?php echo json_encode($reports); ?>;
            const zones = <?php echo json_encode($zones); ?>;




            icon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#4838cc;' class='marker-pin'></div><i class='fa fa-dumpster awesome'>",
                iconSize: [50, 50],
                // iconAnchor: [15, 42]
            });

            for (let index = 0; index < zones.length; index++) {
                const zone = zones[index];

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

                for (let index = 0; index < reports.length; index++) {
                    let report = reports[index];
                    if (currentZonePolygon.getBounds().contains([report.latitude, report.longitude])) {
                        window.L.marker(
                                [report.latitude, report.longitude], {
                                    icon: icon
                                })
                            .addTo(map);
                    }


                }

            }

            window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker;

            function onMapClick(e) {

                if (marker) {
                    map.removeLayer(marker);
                }
                marker = new L.Marker(e.latlng).addTo(map);

                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            }
            map.on('click', onMapClick);



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