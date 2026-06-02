@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.shopping cart') }}
@endsection
@section('css')
@endsection
@section('content')
    <main class="main__content_wrapper">

         <section class="breadcrumb__section breadcrumb__bg">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="breadcrumb__content text-center">
                        <h1 class="breadcrumb__content--title text-white mb-25">{{ __('main.shopping cart') }}</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items">
                                <a class="text-white" href="{{ route('home') }}">
                                    {{ __('main.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb__content--menu__items">
                                <span class="text-white">{{ __('main.shopping cart') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cart__section section--padding">
        <div class="container-fluid">
            <div class="cart__section--inner">
                @if (Cart::getContent()->count())
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="cart__table">
                                <table class="cart__table--inner">
                                    <thead class="cart__table--header">
                                        <tr class="cart__table--header__items">
                                            <th class="cart__table--header__list">{{ __('main.product') }}</th>
                                            <th class="cart__table--header__list">{{ __('main.price') }}</th>
                                            <th class="cart__table--header__list">{{ __('main.quantity') }}</th>
                                            <th class="cart__table--header__list">{{ __('main.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="cart__table--body">
                                        @foreach (Cart::getContent()->sortBy('price') as $item)
                                            <tr class="cart__table--body__items">
                                                <td class="cart__table--body__list">
                                                    <div class="cart__product d-flex align-items-center">
                                                        <button class="cart__remove--btn remove-item"
                                                            aria-label="remove item" type="button"
                                                            data-id="{{ $item->id }}">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" width="16px" height="16px">
                                                                <path
                                                                    d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z" />
                                                            </svg>
                                                        </button>
                                                        <div class="cart__thumbnail">
                                                            <a
                                                                href="{{ Helper::link('product.details', $item->attributes->product_id, $item->name[app()->getLocale()]) }}">
                                                                <img class="border-radius-5"
                                                                    src="{{ asset($item->attributes->image) }}"
                                                                    alt="{{ $item->name[app()->getLocale()] }}">
                                                            </a>
                                                        </div>
                                                        <div class="cart__content">
                                                            <h4 class="cart__content--title">
                                                                <a
                                                                    href="{{ Helper::link('product.details', $item->attributes->product_id, $item->name[app()->getLocale()]) }}">
                                                                    {{ $item->name[app()->getLocale()] }}
                                                                </a>
                                                            </h4>
                                                            <span class="cart__content--variant">{{ __('main.color') }}:
                                                                {{ $item->attributes->color[app()->getLocale()] }}</span>
                                                            <span class="cart__content--variant">{{ __('main.size') }}:
                                                                {{ $item->attributes->size }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <span class="cart__price">{{ __('main.currency') }}
                                                        {{ $item->price }}</span>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <div class="quantity__box cart-page">
                                                        <button type="button"
                                                            class="quantity__value quickview__value--quantity decrease"
                                                            aria-label="decrease quantity" value="Decrease Value">-</button>
                                                        <label>
                                                            <input type="number"
                                                                class="quantity__number quickview__value--number qty-update"
                                                                value="{{ $item->quantity }}"
                                                                data-id="{{ $item->id }}" disabled min="1" />
                                                        </label>
                                                        <button type="button"
                                                            class="quantity__value quickview__value--quantity increase"
                                                            aria-label="increase quantity" value="Increase Value">+</button>
                                                    </div>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <span class="cart__price end">{{ __('main.currency') }}
                                                        {{ $item->price * $item->quantity }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart__summary border-radius-10">
                                <div class="cart__summary--total mb-20">
                                    <table class="cart__summary--total__table">
                                        <tbody>
                                            <tr class="cart__summary--total__list">
                                                <td class="cart__summary--total__title text-left">
                                                    {{ __('main.subtotal') }}</td>
                                                <td class="cart__summary--amount text-right">{{ __('main.currency') }}
                                                    {{ Cart::getTotal() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="cart__summary--footer">
                                    <p class="cart__summary--footer__desc">
                                        {{ __('main.shipping') }}: {{ __('main.in checkout page') }}
                                    </p>
                                    <ul class="d-flex justify-content-between">
                                        <li></li>
                                        <li>
                                            <a class="cart__summary--footer__btn primary__btn checkout"
                                                href="{{ route('checkout') }}">
                                                {{ __('main.check out') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p style="font-size: 40px; color:#3b0056; text-align: center; margin-bottom: 40px">
                        {{ __('main.cart empty') }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    </main>
@endsection
