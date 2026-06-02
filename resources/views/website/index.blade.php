@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.home') }}
@endsection
@section('css')
    <style>
        .banner__items--thumbnail {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')
    <main class="main__content_wrapper">
        <!-- Start slider section -->
        <section class="hero__slider--section">
            <div class="hero__slider--inner hero__slider--activation swiper">
                <div class="hero__slider--wrapper swiper-wrapper">
                      @foreach ($sliders as $slider)
                    <div class="swiper-slide "  >
                        <div class="hero__slider--items home1__slider--bg" style="background-image: url('{{ asset($slider->image) }}'); aspect-ratio: 3/1.1;">
                            <div class="container-fluid">
                                <div class="hero__slider--items__inner">
                                    <div class="row row-cols-1">
                                        <div class="col">
                                            <div class="slider__content">
                                                <p class="slider__content--desc desc1 mb-15"><img
                                                        class="slider__text--shape__icon"
                                                        src="{{asset('website/assets/img/icon/text-shape-icon.png')}}" alt="text-shape-icon"> </p>
                                                <h2 class="slider__content--maintitle h1">{{ $slider->title }}</h2>
                                                    <p>{{ $slider->text }}</p>
                                                <a class="slider__btn primary__btn" href="{{ route('products') }}">{{ __('main.products') }}
                                                    <svg class="primary__btn--arrow__icon"
                                                        xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2"
                                                        viewBox="0 0 6.2 6.2">
                                                        <path
                                                            d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z"
                                                            transform="translate(-4 -4)" fill="currentColor"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
                <div class="swiper__nav--btn swiper-button-next"></div>
                <div class="swiper__nav--btn swiper-button-prev"></div>
            </div>
        </section>
        <!-- End slider section -->
        <br></br>
        <!-- Start banner section -->
         <section class="banner__section banner__style2 section--padding">
        <div class="section__heading text-center mb-35">
            <h2 class="section__heading--maintitle style2">{{ __('main.shop now') }}</h2>
        </div>
        <div class="container-fluid">
            <div class="row mb--n28">
                @foreach ($banners as $banner)
                    <div class="col-md-6 col-lg-4 mb-28">
                        <div class="banner__items position__relative">
                            <a class="banner__items--thumbnail" href="{{  route('products') }}">
                                <img class="banner__items--thumbnail__img" src="{{ asset($banner->image) }}"
                                    alt="banner-img">
                                <div class="banner__items--content style2">
                                    <h3 class="banner__items--content__title style2">{{ __('main.shop now') }}</h3>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
        <!-- End banner section -->

        <!-- Start product section -->
        <section class="product__section section--padding pt-0">
            <div class="container-fluid">
                <div class="section__heading text-center mb-35">
                    <h2 class="section__heading--maintitle"></h2>
                </div>
                <ul class="product__tab--one product__tab--primary__btn d-flex justify-content-center mb-50">
                    @foreach ($categories as $index => $category)
                    <li class="product__tab--primary__btn__list {{ $index == 0 ? 'active' : '' }}" data-toggle="tab" data-target="#cat-{{ $category->id }}">
                        {{$category->title  }} </li>

                    @endforeach
                </ul>
                <div class="tab_content">
                    @foreach ($categories as $index => $category)
                    <div id="cat-{{ $category->id }}" class="tab_pane {{ $index == 0 ? 'show active' : '' }}">
                        <div class="product__section--inner">
                            <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                                   @foreach ($category->products as $product)
                                <div class="col mb-30">
                                    <div class="product__items ">
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
                                                <span class="add__to--cart__text"> + {{ __('main.add to cart') }}</span>
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
                                                        stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
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
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End product section -->

        <!-- Start banner section -->
         <section class="banner__section section--padding pt-0">
        <div class="container-fluid">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="banner__section--inner position__relative overflow-hidden" style="border-radius: 10px">
                        <a class="banner__items--thumbnail display-block" href="{{ $Setting->banner_url }}">
                            <img class="banner__items--thumbnail__img banner__img--height__md display-block"
                                src="{{ asset($Setting->banner_image) }}" alt="banner-img">
                            <div class="banner__content--style2">
                                <h2 class="banner__content--style2__title text-white">
                                    {{ __('main.Need amazing dresses?') }}
                                </h2>
                                <span class="primary__btn">
                                    {{ __('main.shop now') }}
                                    <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg"
                                        width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                        <path
                                            d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z"
                                            transform="translate(-4 -4)" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- End banner section -->


        <!-- Start product section -->
        <section class="product__section section--padding pt-0">
            <div class="container-fluid">
                <div class="section__heading text-center mb-50">
                    <h2 class="section__heading--maintitle">{{ __('main.our best products') }}</h2>
                </div>
                <div class="product__section--inner product__swiper--activation swiper">
                    <div class="swiper-wrapper">
                         @foreach ($features as $product)
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
                                                <span class="add__to--cart__text"> + {{ __('main.add to cart') }}</span>
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
                                                        stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
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
        <!-- End product section -->



        <!-- Start testimonial section -->
        <section class="testimonial__section section--padding pt-0">
            <div class="container-fluid">
                <div class="section__heading text-center mb-40">
                    <h2 class="section__heading--maintitle">Our Clients Say</h2>
                </div>
                <div class="testimonial__section--inner testimonial__swiper--activation swiper">
                    <div class="swiper-wrapper">
                             @foreach ($reviews as $review)
                        <div class="swiper-slide">
                            <div class="testimonial__items--style2 testimonial__items text-center">
                                <div class="testimonial__items--style2__thumbnail mb-10">
                                    <img class="testimonial__items--style2__thumbnail--img border-radius-50"
                                        src="{{ asset($review->image) }}" alt="testimonial-img"
                                        style="width: 70px; height: 70px; object-fit: cover">
                                </div>
                                <div class="testimonial__items--content">
                                    <h2 class="testimonial__items--title text-dark h3">{{ $review->name }}</h2>
                                    <p class="testimonial__items--desc style2 text-dark">
                                        {{ $review->text }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    </div>
                    <div class="testimonial__pagination swiper-pagination"></div>
                </div>
            </div>
        </section>
        <!-- End testimonial section -->



<div class="newsletter__popup" data-animation="slideInUp">
    <div id="boxes" class="newsletter__popup--inner">
        <button class="newsletter__popup--close__btn" aria-label="search close button">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512">
                <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="32" d="M368 368L144 144M368 144L144 368"></path>
            </svg>
        </button>
        <div class="box newsletter__popup--box d-flex align-items-center">
            <div class="newsletter__popup--thumbnail">
                <img class="newsletter__popup--thumbnail__img display-block"
                    src="{{ asset('website/assets/img/banner/newsletter-popup-thumb2.jpeg') }}"
                    alt="newsletter-popup-thumb">
            </div>
            <div class="newsletter__popup--box__right">
                <h2 class="newsletter__popup--title">Join Our Newsletter</h2>
                <div class="newsletter__popup--content">
                    <label class="newsletter__popup--content--desc">Enter your email address to subscribe our
                        notification of our new post &amp; features by email.</label>
                    <div class="newsletter__popup--subscribe" id="frm_subscribe">

                        <form class="newsletter__subscribe--form" id="subscribtion">
                            @csrf
                            @csrf
                            <input class="newsletter__popup--subscribe__input" name="email"
                                placeholder="{{ __('main.Your Email Address') }}" type="email">

                            <button class="newsletter__popup--subscribe__btn" type="submit">
                                {{ __('main.subscribe') }}</button>
                        </form>
                        <div class="newsletter__popup--footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>
@endsection
@section('js')
@endsection
