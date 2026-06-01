@extends('layouts.app')

@section('content')

<div class="login-form">
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="row align-items-center ">
                    <div class="col-md-12">
                        <div class="card-body">
                        <div class="card-header bg-success">{{ $title ?? "" }} {{ __('Login') }}</div> 

                            <div class="text-center">

                                <img src="{{ asset('admin/assets/images/logo-dark.svg') }}" alt="" class="img-fluid mb-4">
                                <h4 class="mb-3 f-w-400">Se connecter</h4>
                            </div>
                    
                            <form method="POST" action="{{ route('manager.login') }}">
                                @csrf
                                <div class="mb-3">
                                <div class="text-danger">
                                @if($errors)
                                    @error('email')
                                     <p  >Le champ email est obligatoire </p>  
                                    @enderror
                                @endif
                                </div>
                                    <!-- <span class="input-group-text"><i data-feather="mail"></i></span> -->
                                    <input type="email" class="form-control" placeholder="Adresse e-mail" name="email" value={{old('email')}}>
                                    
                                </div>
                                <div class="mb-4">
                                <div class="text-danger">
                                @if($errors)
                                    @error('password')
                                     <p  >Le champ password est obligatoire </p>  
                                    @enderror
                                @endif
                                </div>
                               
                                    <!-- <span class="input-group-text"><i data-feather="lock"></i></span> -->
                                    <input type="password" class="form-control" placeholder="Mot de passe" name="password" value={{old('password')}}>
                                </div>
                                <div class="form-group text-left mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Enregistrer les informations d'identification
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-block btn-primary mb-4">
                                    {{ ("Se connecter") }}
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



