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
                                <h5 class="m-b-10">Entreprises</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Liste des companies</a></li>
                                <!-- <li class="breadcrumb-item">Entreprises</li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h5>Formulaire</h5>
                    </div>
                    <div class="card-body">
                        <h5>Enregistrement des companies</h5>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <form method="POST" action="{{ route('companies.store')}}">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i data-feather="home"></i></span>
                                        <input type="text" class="form-control" placeholder="Le nom de la company" name="name">
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback d-block" style="text-align: center">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!-- <div class="input-group mb-4">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" placeholder="L'identifiant de la company" name="slug">
                                        @if ($errors->has('slug'))
                                            <span class="invalid-feedback d-block" style="text-align: center">
                                                <strong>{{ $errors->first('slug') }}</strong>
                                            </span>
                                        @endif
                                    </div> -->
                                    <button type="submit" class="btn btn-block btn-primary mb-4">
                                        {{ ("Enregistrer") }}
                                    </button>
                                    @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection