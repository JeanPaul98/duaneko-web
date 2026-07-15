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
                                <h5 class="m-b-10">agents</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                                <li class="breadcrumb-item">agents</li>
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
                                    <h5>agents</h5>
                                </div>
                                @if(Auth::guard('manager')->check())
                                <div class="col-sm-6">
                                    <a class="btn btn-success btn-sm btn-round mb-3" href="{{ route('agents.create') }}"><i
                                            class="feather icon-plus"></i> Ajouter une agent</a>
                                </div>
                                @endif
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif

                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Téléphone</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agents as $agent)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $agent->first_name }}</td>
                                                <td>{{ $agent->last_name }}</td>
                                                <td style="white-space: nowrap;">{{ $agent->phone_number }}</td>
                                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $agent->email }}">
                                                    {{ $agent->email }}
                                                </td>
                                                <td style="white-space: nowrap;">
                                                  <form id="{{ $agent->id }}" action="{{ route('agents.destroy', $agent) }}" method="POST">
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('agents.show', $agent) }}"><i
                                                                class="feather icon-eye"></i> Detail</a>
                                                        @if(Auth::guard('manager')->check())        
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('agents.edit', $agent) }}"><i
                                                                class="feather icon-edit"></i> Modifier</a>

                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" onclick="if(confirm('Voulez-vous vraiment supprimer cette entreprise ?'));{document.getElementById('{{ $agent->id }}').submit()}"><i
                                                                class="feather icon-trash-2"></i>Supprimer</button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {!! $agents->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

