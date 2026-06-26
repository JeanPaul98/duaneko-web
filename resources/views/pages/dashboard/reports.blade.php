@extends('layouts.admin')

<style>
    #map {
        height: 600px;
    }
</style>

@section('content')
    <div class="pc-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Signalements</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                                <li class="breadcrumb-item">Signalements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function success(position) {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        let map = window.L.map('map').setView([latitude, longitude], 12);
        let marker = window.L.marker([latitude, longitude]).addTo(map);
        marker.bindPopup(`
      <div class="card">
        <img src="https://experiencelife.lifetime.life/wp-content/uploads/2021/02/Talking-Trash.jpg" class="card-img-top rounded" width="200px" height="200px" alt="...">
        <div class="card-body">
            <h5 class="card-title">Poubelle</h5>
        </div>
      </div>
      `);
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
