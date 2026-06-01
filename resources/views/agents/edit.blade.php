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
                            <h5 class="m-b-10">Managers</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('managers.create') }}">Liste des managers</a></li>
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
                    <h5>Modifier l'agent</h5>
                    <hr>
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                           
                            <form method="POST" action="{{ route('agents.update', $agent->id ) }}">
                                @csrf
                                @method('PUT')
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i data-feather="user"></i></span>
                                    <input type="text" class="form-control" name="first_name" value="{{ $agent->first_name }}">
                                </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i data-feather="user"></i></span>
                                    <input type="text" class="form-control" value="{{ $agent->last_name }}" name="last_name">
                                </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i data-feather="mail"></i></span>
                                    <input type="email" class="form-control" value="{{ $agent->email }}" name="email">
                                </div>
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i data-feather="phone"></i></span>
                                    <input type="tel" class="form-control" value="{{ $agent->phone_number }}" name="phone_number">
                                </div>
                                <button type="submit" class="btn btn-block btn-primary mb-4">
                                    {{ ("Modifier") }}
                                </button>
                                <a href="{{ route('agents.index') }}" class="btn btn-block btn-primary mb-4">Annuler</a>
                            </form>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        



    </div>
</div>
@endsection