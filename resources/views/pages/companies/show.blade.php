@extends('layouts.admin')

<style>
   
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

                <div class="row">
                    
                        <div class="col-sm-6">
                            <div class="card prod-p-card background-pattern">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-0">
                                        <div class="col">
                                            <h6 class="m-b-5">Total Profit Managers</h6>
                                            <h3 class="m-b-0">{{ $compt_manager}} </h3>
                                        </div>
                                        <div class="col-auto">
                                            <i class="material-icons-two-tone text-primary">airline_seat_recline_extra</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card prod-p-card bg-primary background-pattern-white">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-0">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Total Profit Agents</h6>
                                            <h3 class="m-b-0 text-white">{{$compt_agent}}</h3>
                                        </div>
                                        <div class="col-auto">
                                            <i class="material-icons-two-tone text-white">group</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card prod-p-card bg-primary background-pattern-white">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-0">
                                        <div class="col">
                                            <h6 class="m-b-5 text-white">Total zone</h6>
                                            <h3 class="m-b-0 text-white">{{$compt_zone}}</h3>
                                        </div>
                                        <div class="col-auto">
                                            <i class="material-icons-two-tone text-white">vpn_lock</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card prod-p-card background-pattern">
                                <div class="card-body">
                                    <div class="row align-items-center m-b-0">
                                        <div class="col">
                                            <h6 class="m-b-5">Nombre de ramassages</h6>
                                            <h3 class="m-b-0">0 </h3>
                                        </div>
                                        <div class="col-auto">
                                            <i class="material-icons-two-tone text-primary">cleaning_services</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="col-md-12">
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="card mt-2">
                                <div class="card-header">
                                <h4>Liste des managers</h4>
                                <hr>
                                        
                                <div class="table-responsive">
                                    <table class="table table-hover m-b-0  ab">
                                    <thead>
                                        <tr>
                                            <th>First_name</th>
                                            <th>Last_name</th>
                                            <th>Email</th>
                                            <th>Phone_number</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($managers as $manager)
                                        <tr>
                                            <td>{{ $manager->first_name }}</td>
                                            <td>{{ $manager->last_name }}</td>
                                            <td>
                                                <div><label class="badge bg-light-warning">{{ $manager->email }}</label></div>
                                            </td>
                                            <td>{{ $manager->phone_number }}</td>  
                                        </tr>
                                    
                                        @endforeach

                                    </tbody>
                                </table>
                                </div>
                                {!! $managers->links() !!}
                                    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mt-2">
                                <div class="card-header">
                                <h4>Liste des agents</h4>
                                <hr>
                                <div class="table-responsive">
                                <table class="table table-hover m-b-0  cd">
                                    <thead>
                                        <tr>
                                        <th>First_name</th>
                                            <th>Last_name</th>
                                            <th>Email</th>
                                            <th>Phone_number</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agents as $agent)
                                        <tr>
                                            <td>{{$agent->first_name}}</td>
                                            <td>{{ $agent->last_name }}</td>
                                            <td>
                                                <div><label class="badge bg-light-danger">{{$agent->email}}</label></div>
                                            </td>
                                            <td>{{$agent->phone_number}}</td>
                                        </tr>
                                       @endforeach
                                        
                                    </tbody>
                                </table>
                                </div>
                               <h5 class="text-center mt-2">{!! $agents->links() !!}</h5> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                            <div class="card mt-2">
                                <div class="card-header">
                                    <h4>Liste des zones</h4>
                                    <hr>
                                    <div class="dataTable-container">
                                        <table class="table dataTable-table" id="pc-dt-simple">
                                            <thead>
                                                <tr>
                                                    <th data-sortable="" style="width: 26.431%;">Name</th>
                                                    <th data-sortable="" style="width: 9.93266%;">slug</th>
                                                    <th data-sortable="" style="width: 25.9259%;">Désignation google</th>
                                                    
                                                </tr>
                                            </thead>
                                                <tbody>
                                                    @foreach ($zones as $zone)
                                                    <tr>
                                                        <td>{{ $zone->name }}</td>
                                                        <td>{{ $zone->slug }}</td>
                                                        <td>{{ $zone->google_map_name }}</td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    
                                </div>
                            </div>
                            
                    </div>
                
                </div>
            </div>
            
                
                
        </div>
    </div>

@endsection


