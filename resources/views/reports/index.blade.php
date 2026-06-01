@extends('layouts.admin')

<style>
    #map {
        height: 700px;
    }

    .custom-div-icon i.awesome {
        margin: 12px auto;
        font-size: 22px;
    }

    .color-red {
        color: red !important;
        /* font-size: px !important; */
    }

    .color-green {
        color: green !important;
        /* font-size: px !important; */
    }
</style>

@section('content')
<div class="pc-container">
    <div class="pcoded-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Signalements</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="#!">Signalements</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">

                        <div class="card-body">
                            <div id="map"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

<script>
    function success(position) {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        let map = window.L.map('map').setView([latitude, longitude], 13);

        const reports = <?php echo json_encode($reports); ?>;

        const zones = <?php echo json_encode($zones); ?>;
        // icon = L.divIcon({
        //     className: 'custom-div-icon',
        //     html: "<div class='marker-pin'></div><i class='fa fa-dumpster awesome color-red'>",
        //     iconSize: [50, 50],
        //     iconAnchor: [15, 42] 
        // });
        let coordinates = [];
        for (let index = 0; index < zones.length; index++) {
            let zone = zones[index];

            coordinates = [
                [zone.northeast_latitude, zone.northeast_longitude],
                [zone.southwest_latitude, zone.northeast_longitude],
                [zone.southwest_latitude, zone.southwest_longitude],
                [zone.northeast_latitude, zone.southwest_longitude]
            ];

            var polygon = L.polygon(coordinates, {
                color: zone.color
            }).addTo(map);
            map.fitBounds(polygon.getBounds());
            console.log(reports);
            for (let index = 0; index < reports.length; index++) {
                let report = reports[index];

                if (polygon.getBounds().contains([report.latitude, report.longitude])) {
                    if (report.status == 'done') {

                        window.L.marker(
                                [report.latitude, report.longitude], {
                                    icon: L.divIcon({
                                        className: 'custom-div-icon',
                                        html: "<div class='marker-pin'></div><i class='fa fa-dumpster awesome color-green'>",
                                        iconSize: [50, 50],
                                        iconAnchor: [15, 42]
                                    })
                                })
                            .bindPopup(
                                `<div class='card' style='width: 18rem;'>
                                    <img src='${report.image}' class='card-img-top' alt='${report.description}' loading='lazy'>
                                    <div class="card-body">
                                        <h5 class='card-title'><span class="badge bg-danger">${report.type}</span></h5>
                                        <p class='card-text'>${report.description}</p>
                                        <a class="btn btn-primary btn-sm" href="{{ url('/reports/${report.id}')}}"><i
                                                                                class="feather icon-eye"></i> Detail</a>
                                        
                                    </div>
                                </div>`
                            ).addTo(map);
                    } else {
                        window.L.marker(
                                [report.latitude, report.longitude], {
                                    icon: L.divIcon({
                                        className: 'custom-div-icon',
                                        html: "<div class='marker-pin'></div><i class='fa fa-dumpster awesome color-red'>",
                                        iconSize: [50, 50],
                                        iconAnchor: [15, 42]
                                    })
                                })
                            .bindPopup(
                                `<div class='card' style='width: 18rem;'>
                                <img src='${report.image}' class='card-img-top' alt='${report.description}' loading='lazy'>
                                <div class="card-body">
                                    <h5 class='card-title'><span class="badge bg-danger">${report.type}</span></h5>
                                    <p class='card-text'>${report.description}</p>
                                    <a class="btn btn-primary btn-sm" href="{{ url('/reports/${report.id}') }}"><i
                                                                            class="feather icon-eye"></i> Detail</a>
                                    
                                </div>
                            </div>`
                            ).addTo(map);
                    }
                }

            }


        }

        window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
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
</script>