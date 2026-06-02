@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.check out') }}
@endsection
@section('css')
    <style>
        .header__section,
        .footer__section {
            display: none;
        }
    </style>
@endsection
@section('content')
     <div class="checkout__page--area">
        <div class="container">
            <div class="checkout__page--inner d-flex">
                <div class="main checkout__mian">
                    <header class="main__header checkout__mian--header mb-30">
                        <h1 class="main__logo--title">
                            <a class="logo logo__left mb-20" href="{{ route('home') }}">
                                <img src="{{ asset('logo/logo.png') }}" alt="logo" style="width: 150px">
                            </a>
                        </h1>
                        <details class="order__summary--mobile__version">
                            <summary class="order__summary--toggle border-radius-5">
                                <span class="order__summary--toggle__inner">
                                    <span class="order__summary--toggle__icon">
                                        <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <span class="order__summary--toggle__text show">
                                        <span>{{ __('main.order details') }}</span>
                                        <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg"
                                            class="order-summary-toggle__dropdown" fill="currentColor">
                                            <path
                                                d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z">
                                            </path>
                                        </svg>
                                    </span>
                                    <span class="order__summary--final__price checkout-total">
                                        {{ __('main.currency') }} {{ Cart::getTotal() }}
                                    </span>
                                </span>
                            </summary>
                            <div class="order__summary--section">
                                <div class="cart__table checkout__product--table">
                                    <table class="summary__table">
                                        <tbody class="summary__table--body">
                                            @foreach (Cart::getContent()->sortBy('price') as $item)
                                                <tr class=" summary__table--items">
                                                    <td class=" summary__table--list">
                                                        <div class="product__image two  d-flex align-items-center">
                                                            <div class="product__thumbnail border-radius-5">
                                                                <a href="#!"><img class="border-radius-5"
                                                                        src="{{ asset($item->attributes->image) }}"
                                                                        alt="cart-product"></a>
                                                                <span class="product__thumbnail--quantity">
                                                                    {{ $item->quantity }}
                                                                </span>
                                                            </div>
                                                            <div class="product__description">
                                                                <h3 class="product__description--name h4"><a href="#!">
                                                                        {{ $item->name[app()->getLocale()] }}
                                                                    </a>
                                                                </h3>
                                                                <span class="product__description--variant"
                                                                    style="color: #000 !important">
                                                                    {{ __('main.color') }}:
                                                                    {{ $item->attributes->color[app()->getLocale()] }} <br>
                                                                    {{ __('main.size') }}:
                                                                    {{ $item->attributes->size }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=" summary__table--list">
                                                        <span class="cart__price">
                                                            {{ __('main.currency') }} {{ $item->price }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="checkout__total">
                                    <table class="checkout__total--table">
                                        <tbody class="checkout__total--body">
                                            <tr class="checkout__total--items">
                                                <td class="checkout__total--title text-left">{{ __('main.subtotal') }}
                                                </td>
                                                <td class="checkout__total--amount text-right">
                                                    {{ __('main.currency') }} {{ Cart::getTotal() }}
                                                </td>
                                            </tr>
                                            <tr class="checkout__total--items">
                                                <td class="checkout__total--title text-left">{{ __('main.shipping') }}</td>
                                                <td class="checkout__total--calculated__text text-right shipping-cost">
                                                    free
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="checkout__total--footer">
                                            <tr class="checkout__total--footer__items">
                                                <td
                                                    class="checkout__total--footer__title checkout__total--footer__list text-left">
                                                    {{ __('main.total') }} </td>
                                                <td
                                                    class="checkout__total--footer__amount checkout__total--footer__list text-right checkout-total">
                                                    {{ __('main.currency') }} {{ Cart::getTotal() }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </details>
                        <nav>
                            <ol class="breadcrumb checkout__breadcrumb d-flex">
                                <li class="breadcrumb__item breadcrumb__item--completed d-flex align-items-center">
                                    <a class="" href="{{ route('home') }}">{{ __('main.home') }}</a>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007"
                                        height="16.831" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path>
                                    </svg>
                                </li>
                                <li class="breadcrumb__item breadcrumb__item--completed d-flex align-items-center">
                                    <a class="" href="{{ route('cart') }}">{{ __('main.cart') }}</a>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007"
                                        height="16.831" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path>
                                    </svg>
                                </li>
                                <li class="breadcrumb__item breadcrumb__item--completed d-flex align-items-center">
                                    <a class="breadcrumb__link" href="#!">{{ __('main.check out') }}</a>
                                </li>
                            </ol>
                        </nav>
                    </header>
                    <main class="main__content_wrapper">
                        <form action="{{ route('checkout') }}" method="post" id="place-order">
                            <div class="checkout__content--step section__shipping--address">
                                <div class="section__header mb-25">
                                    <h3 class="section__header--title">{{ __('main.billing details') }}</h3>
                                </div>
                                <div class="section__shipping--address__content">
                                    <div class="row">
                                        <!-- First Name -->
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5"
                                                        name="first_name" required value="{{ $user->first_name ?? '' }}"
                                                        placeholder="{{ __('main.first name') }}" type="text">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Last Name -->
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" name="last_name"
                                                        required value="{{ $user->last_name ?? '' }}"
                                                        placeholder="{{ __('main.last name') }}" type="text">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" name="phone"
                                                        required value="{{ $user->phone ?? '' }}"
                                                        placeholder="{{ __('main.phone') }}" type="text">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Delivery / Country -->
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list checkout__input--select select">
                                                <label class="checkout__select--label"
                                                    for="country">{{ __('main.delivery') }}</label>
                                                <select class="checkout__input--select__field border-radius-5"
                                                    name="delivery" id="country" required>
                                                    @foreach ($deliveries as $delivery)
                                                        <option value="{{ $delivery->id }}"
                                                            data-cost="{{ $delivery->cost }}"
                                                            @if ($user) {{ $delivery->id == $user?->address?->delivery_id ? 'selected' : '' }} @endif>
                                                            {{ $delivery->name }} - ({{ $delivery->cost }}
                                                            {{ __('main.currency') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- City -->
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" name="city"
                                                        required value="{{ $user?->address?->city ?? '' }}"
                                                        placeholder="{{ __('main.state/city') }}" type="text">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" name="address"
                                                        required value="{{ $user?->address?->address ?? '' }}"
                                                        placeholder="{{ __('main.address') }}" type="text">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Note -->
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <textarea name="note" style="height: unset" class="checkout__input--field border-radius-5" rows="3"
                                                        placeholder="{{ __('main.message') }}"></textarea>
                                                </label>
                                            </div>
                                        </div>
                                        @csrf
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__content--step__footer d-flex align-items-center">
                                <button type="submit" data-loading-text="{{ __('main.loading') }}"
                                    class="continue__shipping--btn primary__btn border-radius-5 theme-btn confirm">
                                    {{ __('main.confirm') }}
                                </button>
                            </div>
                        </form>
                    </main>
                    <footer class="main__footer checkout__footer" dir="ltr">
                        <p class="copyright__content">
                            Copyright © 2025
                            <a class="copyright__content--link text__primary" href="{{ route('home') }}">
                                {{ __('main.site') }}
                            </a> . All Rights Reserved.Design By
                            <a class="copyright__content--link text__primary" href="https://websolla.com">
                                Websolla
                            </a>
                        </p>
                    </footer>
                </div>

                <aside class="checkout__sidebar sidebar">
                    <div class="cart__table checkout__product--table">
                        <table class="cart__table--inner">
                            <tbody class="cart__table--body">
                                @foreach (Cart::getContent()->sortBy('price') as $item)
                                    <tr class="cart__table--body__items">
                                        <td class="cart__table--body__list">
                                            <div class="product__image two d-flex align-items-center">
                                                <div class="product__thumbnail border-radius-5">
                                                    <a href="#!"><img class="border-radius-5"
                                                            src="{{ asset($item->attributes->image) }}"
                                                            alt="cart-product"></a>
                                                    <span
                                                        class="product__thumbnail--quantity">{{ $item->quantity }}</span>
                                                </div>
                                                <div class="product__description">
                                                    <h3 class="product__description--name h4"><a
                                                            href="#!">{{ $item->name[app()->getLocale()] }}</a>
                                                    </h3>
                                                    <span class="product__description--variant"
                                                        style="color: #000 !important">
                                                        {{ __('main.color') }}:
                                                        {{ $item->attributes->color[app()->getLocale()] }} <br>
                                                        {{ __('main.size') }}: {{ $item->attributes->size }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__table--body__list">
                                            <span class="cart__price">{{ __('main.currency') }}
                                                {{ $item->price }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="checkout__total">
                        <table class="checkout__total--table">
                            <tbody class="checkout__total--body">
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">{{ __('main.subtotal') }} </td>
                                    <td class="checkout__total--amount text-right">
                                        {{ __('main.currency') }} {{ Cart::getTotal() }}
                                    </td>
                                </tr>
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">{{ __('main.shipping') }}</td>
                                    <td class="checkout__total--calculated__text text-right shipping-cost">free</td>
                                </tr>
                            </tbody>
                            <tfoot class="checkout__total--footer">
                                <tr class="checkout__total--footer__items">
                                    <td class="checkout__total--footer__title checkout__total--footer__list text-left">
                                        {{ __('main.total') }}
                                    </td>
                                    <td
                                        class="checkout__total--footer__amount checkout__total--footer__list text-right checkout-total">
                                        {{ __('main.currency') }} {{ Cart::getTotal() }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            function updateShippingCost() {
                var selectedOption = $('#country option:selected');
                var shippingCost = parseFloat(selectedOption.data('cost'));
                var subTotal = {{ $subtotalAfterDiscount }};
                var total = shippingCost + subTotal;

                var formattedShippingCost = '{{ __('main.currency') }} ' + shippingCost.toFixed(2);
                var formattedTotal = '{{ __('main.currency') }} ' + total.toFixed(2);

                $('.shipping-cost').text(formattedShippingCost);
                $('.checkout-total').text(formattedTotal);
            }
            updateShippingCost();
            $('#country').change(function() {
                updateShippingCost();
            });

            // submit form
            $("#place-order").on("submit", function(e) {
                let $button = $(".theme-btn.confirm");
                if ($button.prop("disabled")) {
                    e.preventDefault(); // Prevent multiple submissions
                    return;
                }
                $button.prop("disabled", true).html(
                    `${$button.data("loading-text")} <i class="fas fa-spinner fa-spin"></i>`
                );
            });
        });
    </script>
@endsection
