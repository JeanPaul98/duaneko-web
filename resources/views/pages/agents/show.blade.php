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
                                <h5 class="m-b-10">Detail de l'agent</h5>
                            </div>
                            <ul class="breadcrumb">
                                {{-- <li class="breadcrumb-item"><a href="">Tableau de bord</a></li> --}}
                                <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agent</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                <div class="col-md-4">
                        <div class="card mt-5 ">
                            <div class="card-header">
                                <h4>Details de l'agent</h4>
                                <hr>
                                
                       
                                <div class="card-body">
                                <div class="card border border-5" style="width: 100%; ">
                                    
                                    <div class="card-body">
                                        <h5 class="card-title breadcrumb    "> <a href="#">First_name : {{$agent->first_name}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Last_name : {{$agent->last_name}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Phone_number : {{$agent->phone_number}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Email : {{$agent->email}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb">Statut :
                                            @if ($agent->status === 'validated')
                                                <span class="badge bg-success">Validé</span>
                                            @elseif ($agent->status === 'rejected')
                                                <span class="badge bg-danger">Rejeté</span>
                                            @else
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @endif
                                        </h5>
                                        <hr>

                                    </div>
                                    </div> 
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h4>Plus de Détails</h4>
                                <hr>
                                <h4 class="text-center"><ul class="breadcrumb"><li class="breadcrumb-item "> <h4 > Il appartient à l'entreprise  :  {{ $agent->company->name }}</h4></li></ul></h4>
                                <h4 class="text-center"><ul class="breadcrumb"><li class="breadcrumb-item "> <h4 >Il est impliqué sur :  {{ $compt_ramassage }} Ramassages </h4></li></ul></h4>
                                <div class="card-body">
                                    <div class="row flow-offset-1">
                                        <div class="col-md-12">
                                            <H6 class="text-success">La liste de ses ramassages : {{ $compt_ramassage }}</H6>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="white-space: nowrap;">No</th>
                                                            <th style="white-space: nowrap;">name</th>
                                                            <th style="white-space: nowrap;">date_de_ramassage</th>
                                                            <th style="white-space: nowrap;">heure_de_ramassage</th>
                                                            <th style="white-space: nowrap;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($ramassages as $ramassage)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $ramassage->name }}</td>
                                                                <td>{{ $ramassage->date_de_ramassage }}</td>
                                                                <td>{{ $ramassage->heure_de_ramassage }}</td>
                                                                <td style="white-space: nowrap;">
                                                                  <form id="{{ $ramassage->id }}" action="{{ route('ramassages.destroy', $ramassage->id) }}" method="POST">
                                                                        <a class="btn btn-primary btn-sm"
                                                                            href="{{ route('ramassages.show', $ramassage->id) }}"><i
                                                                                class="feather icon-eye"></i> </a>
                                                                        @csrf 
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {!! $ramassages->links() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
@endsection
