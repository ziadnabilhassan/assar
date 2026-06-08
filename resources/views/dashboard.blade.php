@extends('layouts.master')

@section('css')

<link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">

<style>

    .dashboard-card{
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: .3s;
    }

    .dashboard-card:hover{
        transform: translateY(-5px);
    }

    .dashboard-icon{
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        color: #fff;
    }

    .bg-products{
        background: linear-gradient(45deg,#4e73df,#224abe);
    }

    .bg-featured{
        background: linear-gradient(45deg,#1cc88a,#13855c);
    }

    .bg-categories{
        background: linear-gradient(45deg,#36b9cc,#258391);
    }

    .bg-reviews{
        background: linear-gradient(45deg,#f6c23e,#dda20a);
    }

    .table img{
        object-fit: cover;
        border-radius: 10px;
    }

    .welcome-box{
        border-radius: 20px;
        background: linear-gradient(45deg,#4e73df,#6f42c1);
        color: white;
        padding: 40px;
    }

</style>

@endsection

@section('content')

<div class="container-fluid mt-4">

    {{-- WELCOME --}}
    <div class="welcome-box mb-4 shadow">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h2 class="fw-bold mb-2">
                    Welcome, {{ auth()->user()->first_name }}
                </h2>

                <p class="mb-0">
                    Here’s what’s happening with your store today.
                </p>

            </div>

            <div>
                <img src="{{ asset('logo/logo.png') }}" width="120">
            </div>

        </div>

    </div>


    {{-- STATS --}}
    <div class="row">

        {{-- PRODUCTS --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card dashboard-card shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Total Products
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalProducts }}
                            </h2>

                        </div>

                        <div class="dashboard-icon bg-products">
                            <i class="las la-box"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FEATURED --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card dashboard-card shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Featured Products
                            </h6>

                            <h2 class="fw-bold text-success">
                                {{ $featuredProducts }}
                            </h2>

                        </div>

                        <div class="dashboard-icon bg-featured">
                            <i class="las la-star"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- CATEGORIES --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card dashboard-card shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Categories
                            </h6>

                            <h2 class="fw-bold text-info">
                                {{ $totalCategories }}
                            </h2>

                        </div>

                        <div class="dashboard-icon bg-categories">
                            <i class="las la-layer-group"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- REVIEWS --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card dashboard-card shadow">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Reviews
                            </h6>

                            <h2 class="fw-bold text-warning">
                                {{ $totalReviews }}
                            </h2>

                        </div>

                        <div class="dashboard-icon bg-reviews">
                            <i class="las la-comments"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- CHARTS --}}
    <div class="row">

        {{-- PIE CHART --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow dashboard-card">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0">
                        Featured Products Analysis
                    </h5>

                </div>

                <div class="card-body">

                    <div id="featuredChart"></div>

                </div>

            </div>

        </div>


        {{-- MONTHLY CHART --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow dashboard-card">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0">
                        Monthly Sales - Orders
                    </h5>

                </div>

                <div class="card-body">

                    <div id="monthlyChart"></div>

                </div>

            </div>

        </div>

    </div>


    {{-- TABLES --}}
    <div class="row">

        {{-- TOP CATEGORIES --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow dashboard-card">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0">
                        Top Categories
                    </h5>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>
                                    <th>Category</th>
                                    <th>Total Products</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($topCategories as $category)

                                    <tr>

                                        <td>
                                            {{ $category->title }}
                                        </td>

                                        <td>
                                            {{ $category->products_count }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOP COLORS --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow dashboard-card">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0">
                        Top Colors
                    </h5>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>
                                    <th>Color</th>
                                    <th>Total</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($topColors as $color)

                                    <tr>

                                        <td>
                                            {{ $color->color->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $color->total }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- LATEST PRODUCTS --}}
    <div class="card shadow dashboard-card mb-4">

        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Latest Products
            </h5>

            <span class="badge badge-primary p-2">

                Avg Rating :
                {{ $avgRating }}

            </span>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Image</th>
                            <th>Name</th>
                            <th>Created At</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($latestProducts as $product)

                            <tr>

                                <td width="100">

                                    <img
                                        src="{{ asset($product->image) }}"
                                        width="70"
                                        height="70"
                                    >

                                </td>

                                <td>
                                    {{ $product->name }}
                                </td>

                                <td>
                                    {{ $product->created_at->diffForHumans() }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@section('js')

<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>

<script>

    // =========================
    // FEATURED PIE CHART
    // =========================

    var featuredOptions = {

        series: [
            {{ $featuredProducts }},
            {{ $normalProducts }}
        ],

        chart: {
            type: 'donut',
            height: 350
        },

        labels: [
            'Featured',
            'Normal'
        ]
    };

    var featuredChart = new ApexCharts(
        document.querySelector("#featuredChart"),
        featuredOptions
    );

    featuredChart.render();



    // =========================
    // MONTHLY PRODUCTS CHART
    // =========================

    var monthlyOptions = {

        series: [{
            name: 'Products',
            data: @json($monthsCount)
        }],

        chart: {
            type: 'bar',
            height: 350
        },

        xaxis: {
            categories: @json($months)
        }
    };

    var monthlyChart = new ApexCharts(
        document.querySelector("#monthlyChart"),
        monthlyOptions
    );

    monthlyChart.render();

</script>

@endsection
