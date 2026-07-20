@extends('layouts.admin')

@section('content')

<div class="pc-container">
    <div class="pcoded-content">
        <div class="page-header d-block">
            <div class="row align-items-center">
                <div class="col-md-10">
                   <div class="page-header-title">
                        <h5 class="m-b-10">Liste des entreprises</h5>
                    </div>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="">Entreprises</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-xl-12 col-md-12">

                    <div class="card">
                        <div class="card-body table-border-style">

                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Zones</h5>
                                </div>
                                <div class="col-sm-6">
                                    <a class="btn btn-success btn-sm btn-round mb-3" href="{{ route('companies.create') }}"><i
                                            class="feather icon-plus"></i> Ajouter une zone</a>
                                </div>

                                <!-- @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif -->

                            </div>

                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"><a href="#">Id</a></th>
                                        <th scope="col"><a href="#">Nom</a></th>
                                        <th scope="col"><a href="#">Slug</a></th>
                                        <th scope="col"><a href="#">Details</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($companies as $company)
                                        <tr>
                                            <th scope="row">{{ ++$i }}</th>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->slug }}</td>
                                                <td style="white-space: nowrap;">
                                                <form action="{{ route('companies.destroy', $company->id) }}" id="{{ $company }}" method="POST">
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('companies.show', $company) }}"><i
                                                                class="feather icon-eye"></i> Detail</a>
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('companies.edit', $company) }}"><i
                                                                class="feather icon-edit"></i> Modifier</a>

                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" onclick="if(confirm('Voulez-vous vraiment supprimer cette entreprise ?'));{document.getElementById('{{ $company->id }}').submit()}"><i
                                                                class="feather icon-trash-2"></i>Supprimer</button>

                                                    </form>
                                            </td>
                                        </tr>
                                @endforeach
                                </tbody>
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                            </table>
                            {!! $companies->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        
    </div>
</div>

@endsection

