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
              <h5 class="m-b-10">Agents</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Tableau de bord</a></li>
              <li class="breadcrumb-item"><a href="{{ route ('agents.index')}}">Liste des agents</a></li>
              <!-- <li class="breadcrumb-item">Agents</li> -->
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
          <h5 class="">Enregistrement un agent</h5>
          <hr>
          <div class="row">
          <div class="col-xl-12 col-md-12">
          <form method="POST" action="{{ route ('agents.store')}}">
              @csrf  
              <div class="input-group mb-3">
                <span class="input-group-text"><i data-feather="user"></i></span>
                  <input type="text" class="form-control" placeholder="Prenom" name="first_name"
                    autocomplete="name" autofocus>
                    @if ($errors->has('first_name'))
                    <span class="invalid-feedback d-block" style="text-align: center">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                    @endif
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i data-feather="user"></i></span>
                  <input type="text" class="form-control" placeholder="Nom" name="last_name"
                    autocomplete="name">
                    @if ($errors->has('last_name'))
                      <span class="invalid-feedback d-block" style="text-align: center">
                        <strong>{{ $errors->first('last_name') }}</strong>
                      </span>
                    @endif
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i data-feather="mail"></i></span>
                <input type="email" class="form-control" placeholder="Adresse e-mail"
                  name="email">
                  @if ($errors->has('email'))
                    <span class="invalid-feedback d-block" style="text-align: center">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text"><i data-feather="phone"></i></span>
                <input type="tel" class="form-control" placeholder="Numero Telephone"
                  name="phone_number">
                  @if ($errors->has('phone_number'))
                    <span class="invalid-feedback d-block" style="text-align: center">
                      <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
                  @endif
              </div>
              <div class="input-group mb-4">
                <span class="input-group-text"><i data-feather="lock"></i></span>
                <input type="password" class="form-control" placeholder="Mot de passe"
                  name="password">
                  @if ($errors->has('password'))
                    <span class="invalid-feedback d-block" style="text-align: center">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
              </div>
              <div class="input-group mb-4">
                <span class="input-group-text"><i data-feather="lock"></i></span>
                <input type="password" class="form-control"
                  placeholder="Confirmation de Mot de passe" name="password_confirmation">
                  @if ($errors->has('password'))
                    <span class="">
                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                  @endif
            </div>  
            
            <button type="submit" class="btn btn-primary btn-block mb-4">
              {{ "Enregistrer" }}
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