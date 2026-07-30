@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6 xl:col-span-7">
      <x-ecommerce.ecommerce-metrics :metrics="$metrics" />
      <x-ecommerce.monthly-sale :data="$ramassagesByMonth" />
    </div>
    <div class="col-span-12 xl:col-span-5">
        <x-ecommerce.monthly-target
          :rate="$completionRate"
          :total="$totalThisMonth"
          :completed="$completedThisMonth"
        />
    </div>

    <div class="col-span-12">
      <x-ecommerce.statistics-chart :ramassages="$ramassagesByMonth" :users="$usersByMonth" />
    </div>

    <div class="col-span-12">
      <x-users.users-table :users="$users" :roleFilter="$roleFilter" :statusFilter="$statusFilter" :search="$search" />
    </div>
  </div>
@endsection
