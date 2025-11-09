{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>


  <div class="mx-2">
     <div class="row">
        {{-- Number of Items --}}
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Number of Item</h5>
                    <h2 class="card-text">{{ $itemsCount }}</h2>
                    <div class="text-right mt-auto">
                        <a href="{{ route('item.index') }}" class="btn btn-primary">Check Item</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Number of Item Categories --}}
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Number of Item Category</h5>
                    <h2 class="card-text">{{ $itemCategoryCount }}</h2>
                    <div class="text-right mt-auto">
                        <a href="{{ route('item_category.index') }}" class="btn btn-primary">Check Item Category</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Number of Users --}}
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Number of User</h5>
                    <h2 class="card-text">{{ $userCount }}</h2>
                    <div class="text-right mt-auto">
                        <a href="{{ route('menu.user') }}" class="btn btn-primary">Check User</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Number of Roles --}}
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Number of Role</h5>
                    <h2 class="card-text">{{ $roleCount }}</h2>
                    <div class="text-right mt-auto">
                        <a href="{{ route('menu.role') }}" class="btn btn-primary">Check Role</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Number of Menus --}}
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Number of Menu</h5>
                    <h2 class="card-text">{{ $menuCount }}</h2>
                    <div class="text-right mt-auto">
                        <a href="{{ route('menu') }}" class="btn btn-primary">Check Menu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>

        {{-- <pre>
        {{ print_r($stockIn, true) }}
        </pre> --}}

    <div class="d-flex">
        <div class="container ">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Stock Overview</h3>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" style="height:250px; min-height:250px"></canvas>
                </div>
            </div>
        </div>


        <div class="container ">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Stock In vs Stock Out (per date)</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyStockChart" style="height:250px; min-height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>


@stop
@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
  <script>
    document.addEventListener("DOMContentLoaded", function(){
        var ctx = document.getElementById('stockChart').getContext('2d');

        var stockChart = new Chart(ctx, {
            type:'bar',
            data: {
                labels: {!!  json_encode($items) !!},
                datasets:[{
                    label: 'Stock In',
                    data: {!! json_encode($stockIn) !!},
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,1)',
                    borderWidth: 1
                },
                 {
                    label: 'Stock Out',
                    data: {!! json_encode($stockOut) !!},
                    backgroundColor: 'rgba(255,99,132,0.9)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1
                }],
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                scales:{
                    y:{beginAtZero: true}
                }
            }
        })

    })
  </script>

  <script>
      document.addEventListener("DOMContentLoaded", function () {
        var context = document.getElementById('dailyStockChart').getContext('2d');

        var dailyStockChart = new Chart(context,{
            type:'line',
            data:{
                labels: {!! json_encode($dates) !!},
                   datasets: [
                    {
                        label: 'Stock In',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,1)',
                        fill: false,
                        data: {!! json_encode($stockInData) !!},
                    },
                    {
                        label: 'Stock Out',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        fill: false,
                        data: {!! json_encode($stockOutData) !!},
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


      });
  </script>


@stop
