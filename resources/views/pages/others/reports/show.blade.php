@extends('layouts.admin')

<style>
    #map {
        height: 600px;
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
            <div class="page-header ">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Detail Signalement</h5>
                            </div>
                            <ul class="breadcrumb">
                                {{-- <li class="breadcrumb-item"><a href="">Tableau de bord</a></li> --}}
                                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Signalements</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                            
            <div class="col-md-12">
                <div class="row">
                    
                    <div class="col-md-8">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h4>Carte</h4>
                                <hr>
                                        @if ($message = Session::get('success'))
                                        <div class="alert-success text-center" style="font-size:0.5em">
                                            <p>{{ $message }}</p>
                                        </div>
                                        @endif
                                <div class="card-body">
                                    <div id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h4>Details</h4>
                                <hr>
                               @if(Auth::guard('manager')->check())
                               
                                <a class="btn btn-info btn-sm"  href="{{ route ('reports.edit' ,$report) }}"><i
                                                                class="feather icon-edit"></i> Modifier</a>
                                <a class="btn btn-danger btn-sm"  href="{{  route ('reports.show' ,$report) }}"><i
                                                                class="feather icon-edit"></i>Supprimer</a>
                                @endif
                                <div class="card-body">
                                    <div class="card">
                                        <img src='{{ $report['image'] }}' class='card-img-top' loading='lazy'>
                                    </div>
                                    <h5 class="card-title"><span class="badge bg-danger">{{ $report['type'] }}</span></h5>
                                    <p class="card-subtitle mb-2 text-muted ">{{ $report['status'] }}</p>
                                    <p class="card-text">{{ $report['description'] }}</p>    
                                    <a href="https://api.whatsapp.com/send/?text= https://www.google.com/maps/place/{{ $report['id'] }}/{{ $report['latitude'] }},{{ $report['longitude'] }}" data-action="share/whatsapp/share" target="_blank">Partager le Signalement</a>
                                </div>
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
        let map = window.L.map('map').setView([latitude, longitude], 12);

        const report = <?php echo json_encode($report); ?>;

        if(report.status=='done'){
            window.L.marker([report.latitude, report.longitude], {
                    icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div class='marker-pin'></div><i class='fa fa-dumpster awesome color-green'>",
                    iconSize: [50, 50],
                    iconAnchor: [15, 42]
        })
        }).bindPopup(report.description)
    .openPopup().addTo(map)

        window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);  

        }else{
            window.L.marker([report.latitude, report.longitude], {
                    icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div class='marker-pin'></div><i class='fa fa-dumpster awesome color-red'>",
                    iconSize: [50, 50],
                    iconAnchor: [15, 42]
        })
        }).bindPopup(report.description)
    .openPopup().addTo(map)

        window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map); 

        }
       
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
