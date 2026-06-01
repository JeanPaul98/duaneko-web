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
                                <h5 class="m-b-10">Detail Manager</h5>
                            </div>
                            <ul class="breadcrumb">
                                {{-- <li class="breadcrumb-item"><a href="">Tableau de bord</a></li> --}}
                                <li class="breadcrumb-item"><a href="{{ route('managers.index') }}">Manager</a></li>
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
                                <h4>Details du manager</h4>
                                <hr>
                                
                       
                                <div class="card-body">
                                <div class="card border border-5"  style="width: 100%; ">
                                    
                                    <div class="card-body">
                                        <h5 class="card-title breadcrumb    "> <a href="#">First_name : {{$manager->first_name}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Last_name : {{$manager->last_name}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Phone_number : {{$manager->phone_number}}</a></h5>
                                        <hr>
                                        <h5 class="card-title breadcrumb"><a href="#">Email : {{$manager->email}}</a></h5>
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
                                <h4 class="text-center"><ul class="breadcrumb"><li class="breadcrumb-item "> <h4 > Il appartient à l'entreprise  :  {{ $manager->company->name }}</h4></li></ul></h4>
                                <h4 class="text-center"><ul class="breadcrumb"><li class="breadcrumb-item "> <h4 >Il a ajouté  Agents</h4></li></ul></h4>
                                <h4 class="text-center"><ul class="breadcrumb"><li class="breadcrumb-item "> <h4 >Il a ajouté  Zones  </h4></li></ul></h4>
                                <div class="card-body">
                                <div class="row flow-offset-1">
                                    <div class="col-xs-6 col-md-4 Larger  p-3 mb-3 rounded-3">
                                    <div class="product tumbnail thumbnail-3 border bg-white  border border-5 rounded-3" style=" height:200px;" ><a href="#"><img src="" alt=""></a>
                                        <div class="caption text-center">
                                        <h3 class=""><a class="text-dark" href="">Le nombre de managers est : </a></h3>
                                        <h4 class="price text-success " style="font-size: 2rem;"> </h4>
                                        <span class="price sale"></span>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4 Larger  p-3 mb-3   rounded-3" >
                                    <div class="product tumbnail thumbnail-3 border border-5 bg-white   rounded-3" style=" height:200px;" ><a href="#"><img src="" alt=""></a>
                                        <div class="caption text-center">
                                        <h3 class=""><a class="text-dark" href="">Le nombre de agents est : </a></h3>
                                        <h4 class="price text-success " style="font-size: 2rem;"></h4>
                                        <span class="price sale"></span>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4   p-3 mb-3   rounded-3">
                                    <div class="product tumbnail thumbnail-3 border bg-white  border border-5 rounded-3" style=" height:200px;" ><a href="#"><img src="" alt=""></a>
                                        <div class="caption text-center">
                                        <h3 class=""><a class="text-dark" href="">Le nombre de zones est : </a></h3>
                                        <h4 class="price text-success " style="font-size: 2rem;"> </h4>
                                        <span class="price sale"></span>
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
        </div>
    </div>
@endsection
