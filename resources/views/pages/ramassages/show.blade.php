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
                            <li class="breadcrumb-item">Modifier le ramassage {{ $ramassage -> name}}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12">

                <div class="card">

                    <div class="card-header">
                    </div>

                    <div class="card-body">
                        <form action="" method="">

                            <div class="input-group input-group-button mb-3">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nom du ramassage" value="{{ $ramassage->name }}" disabled>
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="date" id="date_de_ramassage" name="date_de_ramassage" class="form-control" value="{{ $ramassage->date_de_ramassage }}" disabled>
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="time" id="heure_de_ramassage" name="heure_de_ramassage" class="form-control" value="{{ $ramassage->heure_de_ramassage }}" disabled>
                            </div>
                            <div class=" form-group">
                                <textarea class="form-control" rows="3" name="description" id="description" placeholder="Description" disabled>{{ $ramassage->description }}</textarea>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label fw-bold text-decoration-underline">Liste des agents affectés au ramassage : </label>
                                <div class="col-9">
                                    <ol class="list-group list-group-light list-group-numbered">
                                        @foreach ($ramassage->agents as $agent)
                                        <li class="list-group-item d-flex justify-content-between align-items-start bg-primary text-white">
                                            <div class="form-check">
                                                <label class="form-check-label fw-bold" for="customCheckinlh1">
                                                    {{ $agent->full_name() }}
                                                </label>
                                            </div>
                                        </li>
                                        @endforeach

                                    </ol>
                                </div>
                            </div>

                            <div id="map" class="mb-3" style="height: 50vh;" disabled></div>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">

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
            const coord = <?php echo json_encode($ramassage); ?>;

            var lat = coord.latitude; // Valeur de la latitude
            var long = coord.longitude; // Valeur de la longitude
            
            let map = window.L.map('map').setView([lat, long], 17);

            window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker;


            marker = L.marker([lat, long]).addTo(map);


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