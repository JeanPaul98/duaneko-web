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
                        <form action="{{ route('ramassages.update',$ramassage->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group input-group-button mb-3">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nom du ramassage" value="{{ $ramassage->name }}">
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="date" id="date_de_ramassage" name="date_de_ramassage" class="form-control" value="{{ $ramassage->date_de_ramassage }}">
                            </div>
                            <div class="input-group input-group-button mb-3">
                                <input type="time" id="heure_de_ramassage" name="heure_de_ramassage" class="form-control" value="{{ $ramassage->heure_de_ramassage }}">
                            </div>
                            <div class=" form-group">
                                <textarea class="form-control" rows="3" name="description" id="description" placeholder="Description" value="{{ $ramassage->description }}"></textarea>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label">Inline</label>
                                <div class="col-9">
                                    @foreach ($agents as $agent)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="agents[]" value="{{ $agent->id }}" id="agent-{{ $agent->id }}" {{ $ramassage->agents->contains($agent->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customCheckinlh1">
                                            {{ $agent->full_name() }}
                                        </label>
                                    </div>
                                    @endforeach


                                </div>
                            </div>

                            <div id="map" class="mb-3" style="height: 50vh;"></div>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">



                            <button type="submit" class="btn  btn-primary">Modifiez</button>
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

            let marker;

            const coord = <?php echo json_encode($ramassage); ?>;

            var lat = coord.latitude; // Valeur de la latitude
            var long = coord.longitude; // Valeur de la longitude

            marker = L.marker([lat, long]).addTo(map);

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