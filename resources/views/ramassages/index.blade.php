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
                                <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                                <li class="breadcrumb-item">Ramassages</li>
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
                                    <h5>Ramassages</h5>
                                </div>
                                <div class="col-sm-6">
                                    <a class="btn btn-success btn-sm btn-round mb-3" href="{{ route('ramassages.create') }}"><i
                                            class="feather icon-plus"></i> Ajouter une ramassage</a>
                                </div>

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
                                            <th>Nom</th>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Description</th>
                                            <th>Agents</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ramassages as $ramassage)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $ramassage->name }}</td>
                                                <td>{{ $ramassage->date_de_ramassage }}</td>
                                                <td>{{ $ramassage->heure_de_ramassage }}</td>
                                                <td>{{ $ramassage->description }}</td>

                                                <td>
                                                    <ol>
                                                        @foreach($ramassage->agents as $element)
                                                        <li>
                                                            {{$element->full_name()}}
                                                        </li>
                                                        @endforeach
                                                    </ol>
                                                </td>
                                                <td>
                                                  <form action="{{ route('ramassages.destroy', $ramassage->id) }}" method="POST">
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('ramassages.show', $ramassage->id) }}"><i
                                                                class="feather icon-eye"></i> Detail</a>
                                                                @if(Auth::guard('manager')->check())
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('ramassages.edit', $ramassage->id) }}"><i
                                                                class="feather icon-edit"></i> Modifier</a>

                                                        @csrf
                                                        @method('DELETE')

                                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="feather icon-trash-2"></i> Supprimer</button>
                                                            @endif 
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {!! $ramassages->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


