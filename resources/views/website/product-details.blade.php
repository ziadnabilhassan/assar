@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ $product->name }}
@endsection
@section('css')
    <style>
        .tab_pane li {
            line-height: 2.6rem;
            margin-bottom: .6rem;
            color: var(--text-gray-color);
        }

        @media only screen and (min-width: 992px) {
            .tab_pane li {
                font-size: 1.5rem;
                line-height: 2.8rem
            }
        }

        .tab_pane li:last-child {
            margin-bottom: 0
        }

        .variant__input--fieldset.colors-container input[type=radio]:checked+label {
            position: relative;
        }

        /* Add a checkmark icon */
        .variant__input--fieldset.colors-container input[type=radio]:checked+label::before {
            content: "✓";
            position: absolute;
            color: #fff;
            top: 5px;
            left: 5px;
            text-shadow: 1px 1px 5px rgb(0 0 0 / 90%)
        }

        .variant__input--fieldset .sizes-container input[type=radio]:checked+label {
            background-color: var(--secondary-color);
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <main class="main__content_wrapper">

        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">Product Details</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a class="text-white" href="index.php">Home</a>
                                </li>
                                <li class="breadcrumb__content--menu__items"><span class="text-white">Product Details</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start product details section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <h1 class="breadcrumb__content--title text-white mb-25">{{ $product->name }}</h1>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items">
                                    <a class="text-white" href="{{ route('home') }}">
                                        {{ __('main.home') }}
                                    </a>
                                </li>
                                <li class="breadcrumb__content--menu__items">
                                    <span class="text-white">{{ $product->name }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="product__details--section section--padding">
            <div class="container">
                <div class="row row-cols-lg-2 row-cols-md-2">
                    <div class="col">
                        <div class="product__details--media">
                            <div class="product__media--preview swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="product__media--preview__items main-image">
                                            <a class="product__media--preview__items--link glightbox"
                                                data-gallery="product-media-preview" href="{{ asset($product->image) }}">
                                                <img class="product__media--preview__items--img"
                                                    src="{{ asset($product->image) }}" alt="product-media-img">
                                            </a>
                                            <div class="product__media--view__icon">
                                                <a class="product__media--view__icon--link glightbox"
                                                    href="{{ asset($product->image) }}"
                                                    data-gallery="product-media-preview">
                                                    <svg class="product__media--view__icon--svg"
                                                        xmlns="http://www.w3.org/2000/svg" width="22.51" height="22.443"
                                                        viewBox="0 0 512 512">
                                                        <path
                                                            d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z"
                                                            fill="none" stroke="currentColor" stroke-miterlimit="10"
                                                            stroke-width="32"></path>
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                            stroke-miterlimit="10" stroke-width="32"
                                                            d="M338.29 338.29L448 448">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <div class="product__media--preview__items" data-color="{{ $image->color_id }}">
                                                <a class="product__media--preview__items--link glightbox"
                                                    data-gallery="product-media-preview" href="{{ asset($image->image) }}">
                                                    <img class="product__media--preview__items--img"
                                                        src="{{ asset($image->image) }}" alt="product-media-img">
                                                </a>
                                                <div class="product__media--view__icon">
                                                    <a class="product__media--view__icon--link glightbox"
                                                        href="{{ asset($image->image) }}"
                                                        data-gallery="product-media-preview">
                                                        <svg class="product__media--view__icon--svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="22.51"
                                                            height="22.443" viewBox="0 0 512 512">
                                                            <path
                                                                d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z"
                                                                fill="none" stroke="currentColor" stroke-miterlimit="10"
                                                                stroke-width="32"></path>
                                                            <path fill="none" stroke="currentColor"
                                                                stroke-linecap="round" stroke-miterlimit="10"
                                                                stroke-width="32" d="M338.29 338.29L448 448">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product__media--nav swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="product__media--nav__items">
                                            <img class="product__media--nav__items--img" src="{{ asset($product->image) }}"
                                                alt="product-nav-img">
                                        </div>
                                    </div>
                                    @foreach ($product->images as $image)
                                        <div class="swiper-slide">
                                            <div class="product__media--nav__items" data-color="{{ $image->color_id }}">
                                                <img class="product__media--nav__items--img"
                                                    src="{{ asset($image->image) }}" alt="product-nav-img">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper__nav--btn swiper-button-next"></div>
                                <div class="swiper__nav--btn swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="product__details--info">
                            <form action="#">
                                <h2 class="product__details--info__title mb-15">
                                    {{ $product->name }}
                                </h2>
                                <div class="product__details--info__price mb-10">
                                    <span class="current__price">{{ __('main.currency') }}
                                        {{ $product->oneVariant?->price }}</span>
                                    @if ($product->oneVariant?->old_price)
                                        <span class="price__divided"></span>
                                        <span class="old__price">{{ __('main.currency') }}
                                            {{ $product->oneVariant?->old_price }}</span>
                                    @endif
                                </div>
                                <p class="product__details--info__desc mb-15">
                                    {!! Helper::limit($product->description, 250) !!}
                                </p>
                                <div class="product__variant">
                                    <div class="product__variant--list mb-10">
                                        <fieldset class="variant__input--fieldset colors-container">
                                            <legend class="product__variant--title mb-8">{{ __('main.color') }} :</legend>
                                            @foreach ($uniqueColors as $color)
                                                <input class="form-check-input" id="color-id-{{ $color->id }}"
                                                    name="color" type="radio" value="{{ $color->id }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <label class="variant__color--value" for="color-id-{{ $color->id }}"
                                                    title="{{ $color->name }}"
                                                    style="background-color: {{ $color->code }}">
                                                </label>
                                            @endforeach
                                        </fieldset>
                                    </div>
                                    <div class="product__variant--list mb-15">
                                        <fieldset class="variant__input--fieldset weight">
                                            <legend class="product__variant--title mb-8">{{ __('main.size') }} :</legend>
                                            <div class="sizes-container" style="min-height: 32px; margin-bottom: 20px">
                                                <!-- Sizes will be dynamically loaded here -->
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div
                                        class="product-form product__variant--list quantity d-flex align-items-center mb-20">
                                        <div class="quantity__box">
                                            <button type="button"
                                                class="quantity__value quickview__value--quantity decrease"
                                                aria-label="quantity value" value="Decrease Value">-</button>
                                            <label>
                                                <input type="number"
                                                    class="quantity__number quickview__value--number quantity-input"
                                                    value="1" min="1" disabled />
                                            </label>
                                            <button type="button"
                                                class="quantity__value quickview__value--quantity increase"
                                                aria-label="quantity value" value="Increase Value">+</button>
                                        </div>
                                        <button class="quickview__cart--btn primary__btn add-to-cart-details"
                                            type="submit" data-id="{{ $product->id }}" data-variant="">
                                            {{ __('main.add to cart') }}
                                        </button>
                                        <button class="quickview__cart--btn primary__btn out-of-stock" type="button"
                                            style="display: none; background-color: rgb(154, 0, 0); cursor: not-allowed">
                                            {{ __('main.out of stock') }}
                                        </button>
                                    </div>
                                    <div class="product__details--info__meta">
                                        <p class="product__details--info__meta--list">
                                            <strong>{{ __('main.category') }}:</strong>
                                            <span>{{ $product->category?->title }}</span>
                                        </p>
                                        <p class="product__details--info__meta--list">
                                            <strong>{{ __('main.brand') }}:</strong>
                                            <span>{{ __('main.site') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="quickview__social d-flex align-items-center mb-15">
                                    <label class="quickview__social--title">{{ __('main.share') }}:</label>
                                    <ul class="quickview__social--wrapper mt-0 d-flex">
                                        <li class="quickview__social--list">
                                            <a class="quickview__social--icon" target="_blank"
                                                href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="7.667" height="16.524"
                                                    viewBox="0 0 7.667 16.524">
                                                    <path data-name="Path 237"
                                                        d="M967.495,353.678h-2.3v8.253h-3.437v-8.253H960.13V350.77h1.624v-1.888a4.087,4.087,0,0,1,.264-1.492,2.9,2.9,0,0,1,1.039-1.379,3.626,3.626,0,0,1,2.153-.6l2.549.019v2.833h-1.851a.732.732,0,0,0-.472.151.8.8,0,0,0-.246.642v1.719H967.8Z"
                                                        transform="translate(-960.13 -345.407)" fill="currentColor" />
                                                </svg>
                                                <span class="visually-hidden">Facebook</span>
                                            </a>
                                        </li>
                                        <li class="quickview__social--list">
                                            <a class="quickview__social--icon" target="_blank"
                                                href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ __('') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.489" height="13.384"
                                                    viewBox="0 0 16.489 13.384">
                                                    <path data-name="Path 303"
                                                        d="M966.025,1144.2v.433a9.783,9.783,0,0,1-.621,3.388,10.1,10.1,0,0,1-1.845,3.087,9.153,9.153,0,0,1-3.012,2.259,9.825,9.825,0,0,1-4.122.866,9.632,9.632,0,0,1-2.748-.4,9.346,9.346,0,0,1-2.447-1.11q.4.038.809.038a6.723,6.723,0,0,0,2.24-.376,7.022,7.022,0,0,0,1.958-1.054,3.379,3.379,0,0,1-1.958-.687,3.259,3.259,0,0,1-1.186-1.666,3.364,3.364,0,0,0,.621.056,3.488,3.488,0,0,0,.885-.113,3.267,3.267,0,0,1-1.374-.631,3.356,3.356,0,0,1-.969-1.186,3.524,3.524,0,0,1-.367-1.5v-.057a3.172,3.172,0,0,0,1.544.433,3.407,3.407,0,0,1-1.1-1.214,3.308,3.308,0,0,1-.4-1.609,3.362,3.362,0,0,1,.452-1.694,9.652,9.652,0,0,0,6.964,3.538,3.911,3.911,0,0,1-.075-.772,3.293,3.293,0,0,1,.452-1.694,3.409,3.409,0,0,1,1.233-1.233,3.257,3.257,0,0,1,1.685-.461,3.351,3.351,0,0,1,2.466,1.073,6.572,6.572,0,0,0,2.146-.828,3.272,3.272,0,0,1-.574,1.083,3.477,3.477,0,0,1-.913.8,6.869,6.869,0,0,0,1.958-.546A7.074,7.074,0,0,1,966.025,1144.2Z"
                                                        transform="translate(-951.23 -1140.849)" fill="currentColor" />
                                                </svg>
                                                <span class="visually-hidden">Twitter</span>
                                            </a>
                                        </li>
                                        <li class="quickview__social--list">
                                            <a class="quickview__social--icon" target="_blank"
                                                href="https://www.instagram.com/?url={{ url()->current() }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.497" height="16.492"
                                                    viewBox="0 0 19.497 19.492">
                                                    <path data-name="Icon awesome-instagram"
                                                        d="M9.747,6.24a5,5,0,1,0,5,5A4.99,4.99,0,0,0,9.747,6.24Zm0,8.247A3.249,3.249,0,1,1,13,11.238a3.255,3.255,0,0,1-3.249,3.249Zm6.368-8.451A1.166,1.166,0,1,1,14.949,4.87,1.163,1.163,0,0,1,16.115,6.036Zm3.31,1.183A5.769,5.769,0,0,0,17.85,3.135,5.807,5.807,0,0,0,13.766,1.56c-1.609-.091-6.433-.091-8.042,0A5.8,5.8,0,0,0,1.64,3.13,5.788,5.788,0,0,0,.065,7.215c-.091,1.609-.091,6.433,0,8.042A5.769,5.769,0,0,0,1.64,19.341a5.814,5.814,0,0,0,4.084,1.575c1.609.091,6.433.091,8.042,0a5.769,5.769,0,0,0,4.084-1.575,5.807,5.807,0,0,0,1.575-4.084c.091-1.609.091-6.429,0-8.038Zm-2.079,9.765a3.289,3.289,0,0,1-1.853,1.853c-1.283.509-4.328.391-5.746.391S5.28,19.341,4,18.837a3.289,3.289,0,0,1-1.853-1.853c-.509-1.283-.391-4.328-.391-5.746s-.113-4.467.391-5.746A3.289,3.289,0,0,1,4,3.639c1.283-.509,4.328-.391,5.746-.391s4.467-.113,5.746.391a3.289,3.289,0,0,1,1.853,1.853c.509,1.283.391,4.328.391,5.746S17.855,15.705,17.346,16.984Z"
                                                        transform="translate(0.004 -1.492)" fill="currentColor" />
                                                </svg>
                                                <span class="visually-hidden">Instagram</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Start product details tab section -->
        <section class="product__details--tab__section section--padding">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <ul class="product__details--tab d-flex mb-30">
                            <li class="product__details--tab__list active" data-toggle="tab" data-target="#description">
                                {{ __('main.description') }}
                            </li>
                        </ul>
                        <div class="product__details--tab__inner border-radius-10">
                            <div class="tab_content">
                                <div id="description" class="tab_pane active show">
                                    <div class="product__tab--content">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- related Products section -->
        <section class="product__section section--padding pt-0">
            <div class="container-fluid">
                <div class="section__heading text-center mb-50">
                    <h2 class="section__heading--maintitle">{{ __('main.Our Best Seller') }}</h2>
                </div>
                <div class="product__section--inner product__swiper--activation swiper">
                    <div class="swiper-wrapper">
                        @foreach ($relatedProducts as $product)
                            <div class="swiper-slide">
                                <div class="product__items">
                                    <div class="product__items--thumbnail">
                                        <a class="product__items--link"
                                            href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                            <img class="product__items--img product__primary--img"
                                                src="{{ asset($product->image) }}" alt="product-img">
                                            @if ($product->oneImage)
                                                <img class="product__items--img product__secondary--img"
                                                    src="{{ asset($product->oneImage?->image) }}" alt="product-img">
                                            @endif
                                        </a>
                                        <div class="product__badge">
                                            <span class="product__badge--items sale">{{ __('main.Sale') }}</span>
                                        </div>
                                    </div>
                                    <div class="product__items--content">
                                        <span class="product__items--content__subtitle">
                                            {{ $product->category?->title }}
                                        </span>
                                        <h3 class="product__items--content__title h4">
                                            <a href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <div class="product__items--price">
                                            <span class="current__price">
                                                {{ __('main.currency') }} {{ $product->oneVariant?->price }}
                                            </span>
                                            @if ($product->oneVariant?->old_price)
                                                <span class="price__divided"></span>
                                                <span class="old__price">
                                                    {{ __('main.currency') }} {{ $product->oneVariant?->old_price }}
                                                </span>
                                            @endif
                                        </div>
                                        <ul class="product__items--action d-flex justify-content-between">
                                            <li class="product__items--action__list">
                                                <a class="product__items--action__btn add__to--cart add-to-cart"
                                                    href="{{ Helper::link('cart', $product->id, $product->name) }}"
                                                    data-id="{{ $product->id }}"
                                                    data-variant="{{ $product->oneVariant?->id }}">
                                                    <svg class="product__items--action__btn--svg"
                                                        xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443"
                                                        viewBox="0 0 14.706 13.534">
                                                        <g transform="translate(0 0)">
                                                            <g>
                                                                <path data-name="Path 16787"
                                                                    d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm azzal8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z"
                                                                    transform="translate(0 -463.248)" fill="currentColor">
                                                                </path>
                                                                <path data-name="Path 16788"
                                                                    d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z"
                                                                    transform="translate(-1.191 -466.622)"
                                                                    fill="currentColor"></path>
                                                                <path data-name="Path 16789"
                                                                    d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z"
                                                                    transform="translate(-2.875 -466.622)"
                                                                    fill="currentColor"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                    <span class="add__to--cart__text"> +
                                                        {{ __('main.add to cart') }}</span>
                                                </a>
                                            </li>
                                            <li class="product__items--action__list">
                                                <a class="product__items--action__btn view-product" data-open="modal1"
                                                    href="javascript:void(0)" data-id="{{ $product->id }}"
                                                    data-variant="{{ $product->oneVariant?->id }}">
                                                    <svg class="product__items--action__btn--svg"
                                                        xmlns="http://www.w3.org/2000/svg" width="25.51" height="23.443"
                                                        viewBox="0 0 512 512">
                                                        <path
                                                            d="M255.66 112c-77.94 0-157.89 45.11-220.83 135.33a16 16 0 00-.27 17.77C82.92 340.8 161.8 400 255.66 400c92.84 0 173.34-59.38 221.79-135.25a16.14 16.14 0 000-17.47C428.89 172.28 347.8 112 255.66 112z"
                                                            fill="none" stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="32" />
                                                        <circle cx="256" cy="256" r="80" fill="none"
                                                            stroke="currentColor" stroke-miterlimit="10"
                                                            stroke-width="32" />
                                                    </svg>
                                                    <span class="visually-hidden">{{ __('main.quick view') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper__nav--btn swiper-button-next"></div>
                    <div class="swiper__nav--btn swiper-button-prev"></div>
                </div>
            </div>
        </section>
        <!-- End shipping section -->
    </main>
@endsection
@section('js')
    <script>
        $(document).ready(function() {


            // Initialize Swiper for navigation thumbnails
            const navSwiper = new Swiper(".product__media--nav", {
                loop: false, // Disable loop for precise navigation
                spaceBetween: 10,
                slidesPerView: 5,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    768: {
                        slidesPerView: 5
                    },
                    480: {
                        slidesPerView: 4
                    },
                    320: {
                        slidesPerView: 3
                    },
                    200: {
                        slidesPerView: 2
                    },
                    0: {
                        slidesPerView: 1
                    }
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                }
            });

            // Initialize Swiper for main preview
            const previewSwiper = new Swiper(".product__media--preview", {
                loop: false, // Disable loop for precise navigation
                spaceBetween: 10,
                thumbs: {
                    swiper: navSwiper
                }
            });

            // Store Swiper instance for access
            $('.product__media--preview').data('swiper', previewSwiper);

            // Handle color selection
            $('input[name="color"]').change(function() {
                const selectedColor = $(this).val();
                const productId = $('.add-to-cart-details').data('id');
                const swiper = $('.product__media--preview').data('swiper');

                // Move to slide with matching data-color or main image
                if (swiper) {
                    let slideIndex = 0; // Default to main image
                    const slides = $('.product__media--preview__items');

                    slides.each(function(index) {
                        if ($(this).data('color') == selectedColor) {
                            slideIndex = index;
                            return false; // Break loop
                        }
                    });

                    swiper.slideTo(slideIndex);
                }

                // AJAX for sizes
                $.ajax({
                    url: `/get-unique-sizes-by-color/${productId}/${selectedColor}`,
                    method: 'GET',
                    success: function(response) {
                        $('.sizes-container').empty();

                        if (response.length > 0) {
                            response.forEach(function(variant, index) {
                                const sizeId = variant.size.id;
                                const sizeName = variant.size.name;
                                const sizeItem = `
                            <input class="form-check-input" name="size" type="radio" id="size-${sizeId}"
                                value="${sizeId}" data-variant="${variant.id}"
                                data-price="${variant.price}" data-old-price="${variant.old_price}"
                                data-quantity="${variant.quantity}"
                                ${index === 0 ? 'checked' : ''}>
                            <label class="form-check-label variant__size--value" for="size-${sizeId}">
                                ${sizeName}
                            </label>
                        `;
                                $('.sizes-container').append(sizeItem);
                            });

                            // Trigger change for first size
                            $('input[name="size"]:first').trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching sizes:', error);
                    }
                });
            });

            // Handle size selection and update price + variant + stock
            $(document).on('change', 'input[name="size"]', function() {
                const price = $(this).data('price');
                const oldPrice = $(this).data('old-price');
                const variantId = $(this).data('variant');
                const quantity = $(this).data('quantity');

                // Update price display
                let priceHtml = `<span class="current__price">{{ __('main.currency') }} ${price}</span>`;
                if (oldPrice > 0) {
                    priceHtml += `
                        <span class="price__divided"></span>
                        <span class="old__price">{{ __('main.currency') }} ${oldPrice}</span>
                    `;
                }
                $('.product__details--info__price').html(priceHtml);

                // Update the "Add to Cart" button with the variant ID
                $('.add-to-cart-details').attr('data-variant', variantId);

                // Stock visibility logic
                if (quantity > 0) {
                    $('.add-to-cart-details').show();
                    $('.out-of-stock').hide();
                } else {
                    $('.add-to-cart-details').hide();
                    $('.out-of-stock').show();
                }
            });

            // Trigger initial color change
            $('input[name="color"]:checked').trigger('change');
        });
    </script>
@endsection
