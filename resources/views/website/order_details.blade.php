@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.order details') }}
@endsection
@section('css')
    <style>
        .quantity-wrapper {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            background: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 2px 4px;
            cursor: pointer;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:disabled {
            cursor: default;
            background: #e0e0e0;
        }

        .quantity-input {
            text-align: center;
            width: 60px;
            margin: 0 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .quantity-input {
            max-width: 100%;
            width: 80px;
            text-align: center
        }

        .table-responsive {
            margin-top: 20px;
        }

        .img-holder {
            display: flex;
            align-items: center;
        }

        .mt-product-table .qyt-form,
        .mt-product-table .product-name,
        .mt-product-table .price,
        .mt-product-table .fa-close {
            padding: 0 !important;
        }

        td strong,
        .mt-product-table .fa-close {
            padding: 0 !important;
            margin: 0 !important;
        }

        td {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <section class="breadcrumb__section breadcrumb__bg">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="breadcrumb__content text-center">
                        <h1 class="breadcrumb__content--title text-white mb-25">{{ __('main.order details') }}</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items">
                                <a class="text-white" href="{{ route('home') }}">
                                    {{ __('main.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb__content--menu__items">
                                <span class="text-white">{{ __('main.order details') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-product-table">
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('main.image') }}</th>
                            <th>{{ __('main.product') }}</th>
                            <th>{{ __('main.price') }}</th>
                            <th>{{ __('main.quantity') }}</th>
                            <th>{{ __('main.size') }}</th>
                            <th>{{ __('main.color') }}</th>
                            <th>{{ __('main.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->products as $product)
                            <tr>
                                <td>
                                    <div class="img-holder">
                                        <img src="{{ asset($product->product?->image) }}" alt="image"
                                            class="img-thumbnail" style="max-width: 100px;">
                                    </div>
                                </td>
                                <td>
                                    <div class="product-name">{{ $product->name }}</div>
                                </td>
                                <td>
                                    <strong class="price">
                                        LE {{ number_format($product->price, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <strong class="quantity">
                                        {{ $product->quantity }}
                                    </strong>
                                </td>
                                <td>
                                    <strong class="size">
                                        {{ $product->size ?? 'N/A' }}
                                    </strong>
                                </td>
                                <td>
                                    <strong class="color">
                                        {{ $product->color }}
                                    </strong>
                                </td>
                                <td>
                                    <strong class="price">
                                        LE
                                        {{ $product->total_price }}
                                    </strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
