@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.products') }}
@endsection
@section('css')
    <style>
        .not-found-text {
            text-align: center;
            margin: 50px 0;
            font-size: 20px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .pagination__area nav {
            display: flex;
            justify-content: center;
            padding-left: 0;
            list-style: none;
        }

        .page-item {
            margin: 0 3px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #ddd;
        }

        .page-link {
            padding: 10px 14px;
        }

        .page-link {
            color: var(--secondary-color);
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 10px 14px;
        }
    </style>
@endsection
@section('content')
   <main class="main__content_wrapper">
 <section class="breadcrumb__section breadcrumb__bg">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="breadcrumb__content text-center">
                        <h1 class="breadcrumb__content--title text-white mb-25">{{ __('main.products') }}</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items">
                                <a class="text-white" href="{{ route('home') }}">
                                    {{ __('main.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb__content--menu__items">
                                <span class="text-white">{{ __('main.products') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shop__section section--padding">
        <div class="container-fluid">
            <div class="shop__header bg__gray--color d-flex align-items-center justify-content-between mb-30">
                <button class="widget__filter--btn d-flex d-lg-none align-items-center" data-offcanvas>
                    <svg class="widget__filter--btn__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="28" d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80" />
                        <circle cx="336" cy="128" r="28" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28" />
                        <circle cx="176" cy="256" r="28" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28" />
                        <circle cx="336" cy="384" r="28" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="28" />
                    </svg>
                    <span class="widget__filter--btn__text">{{ __('main.filter') }}</span>
                </button>
                <div class="product__view--mode d-flex align-items-center">
                    <div class="product__view--mode__list">
                        <div class="product__grid--column__buttons d-flex justify-content-center">
                            <button class="product__grid--column__buttons--icons active" aria-label="product grid button"
                                data-toggle="tab" data-target="#product_grid">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 9 9">
                                    <g transform="translate(-1360 -479)">
                                        <rect id="Rectangle_5725" data-name="Rectangle 5725" width="4" height="4"
                                            transform="translate(1360 479)" fill="currentColor" />
                                        <rect id="Rectangle_5727" data-name="Rectangle 5727" width="4" height="4"
                                            transform="translate(1360 484)" fill="currentColor" />
                                        <rect id="Rectangle_5726" data-name="Rectangle 5726" width="4" height="4"
                                            transform="translate(1365 479)" fill="currentColor" />
                                        <rect id="Rectangle_5728" data-name="Rectangle 5728" width="4" height="4"
                                            transform="translate(1365 484)" fill="currentColor" />
                                    </g>
                                </svg>
                            </button>
                            <button class="product__grid--column__buttons--icons" aria-label="product list button"
                                data-toggle="tab" data-target="#product_list">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 13 8">
                                    <g id="Group_14700" data-name="Group 14700" transform="translate(-1376 -478)">
                                        <g transform="translate(12 -2)">
                                            <g id="Group_1326" data-name="Group 1326">
                                                <rect id="Rectangle_5729" data-name="Rectangle 5729" width="3"
                                                    height="2" transform="translate(1364 483)" fill="currentColor" />
                                                <rect id="Rectangle_5730" data-name="Rectangle 5730" width="9"
                                                    height="2" transform="translate(1368 483)" fill="currentColor" />
                                            </g>
                                            <g id="Group_1328" data-name="Group 1328" transform="translate(0 -3)">
                                                <rect id="Rectangle_5729-2" data-name="Rectangle 5729" width="3"
                                                    height="2" transform="translate(1364 483)" fill="currentColor" />
                                                <rect id="Rectangle_5730-2" data-name="Rectangle 5730" width="9"
                                                    height="2" transform="translate(1368 483)" fill="currentColor" />
                                            </g>
                                            <g id="Group_1327" data-name="Group 1327" transform="translate(0 -1)">
                                                <rect id="Rectangle_5731" data-name="Rectangle 5731" width="3"
                                                    height="2" transform="translate(1364 487)" fill="currentColor" />
                                                <rect id="Rectangle_5732" data-name="Rectangle 5732" width="9"
                                                    height="2" transform="translate(1368 487)" fill="currentColor" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="product__view--mode__list product__view--search d-none d-lg-block">
                        <form class="product__view--search__form" action="{{ url()->current() }}">
                            <label>
                                <input class="product__view--search__input border-0" name="search"
                                    placeholder="{{ __('main.search here') }}" type="text">
                            </label>
                            <button class="product__view--search__btn" aria-label="shop button" type="submit">
                                <svg class="product__view--search__btn--svg" xmlns="http://www.w3.org/2000/svg"
                                    width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z"
                                        fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <p class="product__showing--count">
                    {{ $products->firstItem() }}-{{ $products->lastItem() }} /
                    {{ $products->total() }} {{ __('main.products') }}
                </p>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="shop__sidebar--widget widget__area d-none d-lg-block">
                        <form action="{{ url()->current() }}" method="get">
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">{{ __('main.categories') }}</h2>
                                <ul class="widget__form--check">
                                    @foreach ($Cats as $category)
                                        <li class="widget__form--check__list">
                                            <label class="widget__form--check__label"
                                                for="check{{ $category->id }}">{{ $category->title }}</label>
                                            <input class="widget__form--check__input" id="check{{ $category->id }}"
                                                type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                onchange="this.form.submit()"
                                                {{ is_array(request('categories')) && in_array($category->id, request('categories')) ? 'checked' : '' }}
                                                {{ request('category') == $category->id ? 'checked' : '' }}>
                                            <span class="widget__form--checkmark"></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="single__widget price__filter widget__bg">
                                <h2 class="widget__title h3">{{ __('main.Price Filter') }}</h2>
                                <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                                    <div class="price__filter--group">
                                        <label class="price__filter--label" for="Filter-Price-GTE2">
                                            {{ __('main.from') }}
                                        </label>
                                        <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                            <span class="price__filter--currency">{{ __('main.currency') }} </span>
                                            <label>
                                                <input class="price__filter--input__field border-0" name="min"
                                                    type="number" placeholder="0" min="1" required
                                                    value="{{ request('min') }}">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="price__divider">
                                        <span>-</span>
                                    </div>
                                    <div class="price__filter--group">
                                        <label class="price__filter--label" for="Filter-Price-LTE2">
                                            {{ __('main.to') }}
                                        </label>
                                        <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                            <span class="price__filter--currency">{{ __('main.currency') }} </span>
                                            <label>
                                                <input class="price__filter--input__field border-0" name="max"
                                                    type="number" min="0" placeholder="250.00" required
                                                    value="{{ request('max') }}">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button class="price__filter--btn primary__btn" type="submit">
                                    {{ __('main.filter') }}
                                </button>
                            </div>
                        </form>
                        <div class="single__widget widget__bg">
                            <h2 class="widget__title h3">{{ __('main.Featured Products') }}</h2>
                            <div class="product__grid--inner">
                                @foreach ($features as $product)
                                    <div class="product__items product__items--grid d-flex align-items-center">
                                        <div class="product__items--grid__thumbnail position__relative">
                                            <a class="product__items--link" href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                                <img class="product__items--img product__primary--img"
                                                    src="{{ asset($product->image) }}" alt="product-img">
                                                @if ($product->oneImage)
                                                    <img class="product__items--img product__secondary--img"
                                                        src="{{ asset($product->oneImage?->image) }}" alt="product-img">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="product__items--grid__content">
                                            <h3 class="product__items--content__title h4">
                                                <a
                                                    href="{{ Helper::link('product.details', $product->id, $product->name) }}">
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
                                            <ul class="rating product__rating d-flex">
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="14.105"
                                                            height="14.732" viewBox="0 0 10.105 9.732">
                                                            <path data-name="star - Copy"
                                                                d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                transform="translate(0 -0.018)" fill="currentColor">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="14.105"
                                                            height="14.732" viewBox="0 0 10.105 9.732">
                                                            <path data-name="star - Copy"
                                                                d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                transform="translate(0 -0.018)" fill="currentColor">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="14.105"
                                                            height="14.732" viewBox="0 0 10.105 9.732">
                                                            <path data-name="star - Copy"
                                                                d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                transform="translate(0 -0.018)" fill="currentColor">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="14.105"
                                                            height="14.732" viewBox="0 0 10.105 9.732">
                                                            <path data-name="star - Copy"
                                                                d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                transform="translate(0 -0.018)" fill="currentColor">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </li>
                                                <li class="rating__list">
                                                    <span class="rating__list--icon">
                                                        <svg class="rating__list--icon__svg"
                                                            xmlns="http://www.w3.org/2000/svg" width="14.105"
                                                            height="14.732" viewBox="0 0 10.105 9.732">
                                                            <path data-name="star - Copy"
                                                                d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                                transform="translate(0 -0.018)" fill="currentColor">
                                                            </path>
                                                        </svg>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="shop__product--wrapper">
                        <div class="tab_content">
                            <div id="product_grid" class="tab_pane active show">
                                <div class="product__section--inner product__grid--inner">
                                    <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-2 mb--n30">
                                        @forelse ($products as $product)
                                            <div class="col mb-30">
                                                <div class="product__items">
                                                    <div class="product__items--thumbnail">
                                                        <a class="product__items--link"
                                                            href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                                            <img class="product__items--img product__primary--img"
                                                                src="{{ asset($product->image) }}" alt="product-img">
                                                            @if ($product->oneImage)
                                                                <img class="product__items--img product__secondary--img"
                                                                    src="{{ asset($product->oneImage?->image) }}"
                                                                    alt="product-img">
                                                            @endif
                                                        </a>
                                                        @if ($product->is_featured)
                                                            <div class="product__badge">
                                                                <span class="product__badge--items sale">
                                                                    {{ __('main.Sale') }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product__items--content">
                                                        <span class="product__items--content__subtitle">
                                                            {{ $product->category?->title }}
                                                        </span>
                                                        <h3 class="product__items--content__title h4">
                                                            <a
                                                                href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </h3>
                                                        <div class="product__items--price">
                                                            <span class="current__price">
                                                                {{ __('main.currency') }}
                                                                {{ $product->oneVariant?->price }}
                                                            </span>
                                                            @if ($product->oneVariant?->old_price)
                                                                <span class="price__divided"></span>
                                                                <span class="old__price">
                                                                    {{ __('main.currency') }}
                                                                    {{ $product->oneVariant?->old_price }}
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
                                                                        xmlns="http://www.w3.org/2000/svg" width="22.51"
                                                                        height="20.443" viewBox="0 0 14.706 13.534">
                                                                        <g transform="translate(0 0)">
                                                                            <g>
                                                                                <path data-name="Path 16787"
                                                                                    d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm azzal8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z"
                                                                                    transform="translate(0 -463.248)"
                                                                                    fill="currentColor">
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
                                                                <a class="product__items--action__btn view-product"
                                                                    data-open="modal1" href="javascript:void(0)"
                                                                    data-id="{{ $product->id }}"
                                                                    data-variant="{{ $product->oneVariant?->id }}">
                                                                    <svg class="product__items--action__btn--svg"
                                                                        xmlns="http://www.w3.org/2000/svg" width="25.51"
                                                                        height="23.443" viewBox="0 0 512 512">
                                                                        <path
                                                                            d="M255.66 112c-77.94 0-157.89 45.11-220.83 135.33a16 16 0 00-.27 17.77C82.92 340.8 161.8 400 255.66 400c92.84 0 173.34-59.38 221.79-135.25a16.14 16.14 0 000-17.47C428.89 172.28 347.8 112 255.66 112z"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="32" />
                                                                        <circle cx="256" cy="256" r="80"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-miterlimit="10" stroke-width="32" />
                                                                    </svg>
                                                                    <span
                                                                        class="visually-hidden">{{ __('main.quick view') }}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="not-found-text">{{ __('main.no products') }}</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div id="product_list" class="tab_pane">
                                <div class="product__section--inner">
                                    <div class="row row-cols-1 mb--n30">
                                        @forelse ($products as $product)
                                            <div class="col mb-30">
                                                <div class="product__items product__list--items d-flex">
                                                    <div class="product__items--thumbnail product__list--items__thumbnail">
                                                        <a class="product__items--link"
                                                            href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                                            <img class="product__items--img product__primary--img"
                                                                src="{{ asset($product->image) }}"
                                                                alt="{{ $product->name }}">
                                                            @if ($product->oneImage)
                                                                <img class="product__items--img product__secondary--img"
                                                                    src="{{ asset($product->oneImage?->image) }}"
                                                                    alt="{{ $product->name }}">
                                                            @endif
                                                        </a>
                                                        @if ($product->is_featured || $product->oneVariant?->old_price)
                                                            <div class="product__badge">
                                                                <span
                                                                    class="product__badge--items sale">{{ __('main.Sale') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="product__list--items__content">
                                                        <span
                                                            class="product__items--content__subtitle mb-4">{{ $product->category?->title }}</span>
                                                        <h3 class="product__list--items__content--title h4 mb-10">
                                                            <a
                                                                href="{{ Helper::link('product.details', $product->id, $product->name) }}">{{ $product->name }}</a>
                                                        </h3>
                                                        <div class="product__list--items__price mb-10">
                                                            <span class="current__price">{{ __('main.currency') }}
                                                                {{ $product->oneVariant?->price }}</span>
                                                            <span class="price__divided"></span>
                                                            @if ($product->oneVariant?->old_price)
                                                                <span class="old__price">{{ __('main.currency') }}
                                                                    {{ $product->oneVariant?->old_price }}</span>
                                                            @endif
                                                        </div>
                                                        <p
                                                            class="product__list--items__content--desc d-none d-xl-block mb-15">
                                                            {!! Helper::limit($product->description, 250) !!}
                                                        </p>
                                                        <ul class="product__items--action d-flex justify-content-between">
                                                            <li class="product__items--action__list">
                                                                <a class="product__items--action__btn add__to--cart add-to-cart"
                                                                    href="{{ Helper::link('cart', $product->id, $product->name) }}"
                                                                    data-id="{{ $product->id }}"
                                                                    data-variant="{{ $product->oneVariant?->id }}">
                                                                    <svg class="product__items--action__btn--svg"
                                                                        xmlns="http://www.w3.org/2000/svg" width="22.51"
                                                                        height="20.443" viewBox="0 0 14.706 13.534">
                                                                        <g transform="translate(0 0)">
                                                                            <g>
                                                                                <path data-name="Path 16787"
                                                                                    d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z"
                                                                                    transform="translate(0 -463.248)"
                                                                                    fill="currentColor"></path>
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
                                                                <a class="product__items--action__btn view-product"
                                                                    data-open="modal1" href="javascript:void(0)"
                                                                    data-id="{{ $product->id }}"
                                                                    data-variant="{{ $product->oneVariant?->id }}">
                                                                    <svg class="product__items--action__btn--svg"
                                                                        xmlns="http://www.w3.org/2000/svg" width="25.51"
                                                                        height="23.443" viewBox="0 0 512 512">
                                                                        <path
                                                                            d="M255.66 112c-77.94 0-157.89 45.11-220.83 135.33a16 16 0 00-.27 17.77C82.92 340.8 161.8 400 255.66 400c92.84 0 173.34-59.38 221.79-135.25a16.14 16.14 0 000-17.47C428.89 172.28 347.8 112 255.66 112z"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="32" />
                                                                        <circle cx="256" cy="256" r="80"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-miterlimit="10" stroke-width="32" />
                                                                    </svg>
                                                                    <span
                                                                        class="visually-hidden">{{ __('main.quick view') }}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="not-found-text">{{ __('main.no products') }}</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination__area bg__gray--color">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="offcanvas__filter--sidebar widget__area">
        <button type="button" class="offcanvas__filter--close" data-offcanvas="">
            <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="32" d="M368 368L144 144M368 144L144 368"></path>
            </svg> <span class="offcanvas__filter--close__text">{{ __('main.close') }}</span>
        </button>
        <div class="offcanvas__filter--sidebar__inner">
            <form action="{{ url()->current() }}" method="get">
                <div class="single__widget widget__bg">
                    <h2 class="widget__title h3">{{ __('main.categories') }}</h2>
                    <ul class="widget__form--check">
                        @foreach ($Cats as $category)
                            <li class="widget__form--check__list">
                                <label class="widget__form--check__label"
                                    for="check{{ $category->id }}">{{ $category->title }}</label>
                                <input class="widget__form--check__input" id="check{{ $category->id }}" type="checkbox"
                                    name="categories[]" value="{{ $category->id }}" onchange="this.form.submit()"
                                    {{ is_array(request('categories')) && in_array($category->id, request('categories')) ? 'checked' : '' }}
                                    {{ request('category') == $category->id ? 'checked' : '' }}>
                                <span class="widget__form--checkmark"></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="single__widget price__filter widget__bg">
                    <h2 class="widget__title h3">{{ __('main.Price Filter') }}</h2>
                    <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                        <div class="price__filter--group">
                            <label class="price__filter--label" for="Filter-Price-GTE2">
                                {{ __('main.from') }}
                            </label>
                            <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                <span class="price__filter--currency">{{ __('main.currency') }} </span>
                                <label>
                                    <input class="price__filter--input__field border-0" name="min" type="number"
                                        placeholder="0" min="1" required value="{{ request('min') }}">
                                </label>
                            </div>
                        </div>
                        <div class="price__divider">
                            <span>-</span>
                        </div>
                        <div class="price__filter--group">
                            <label class="price__filter--label" for="Filter-Price-LTE2">
                                {{ __('main.to') }}
                            </label>
                            <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                <span class="price__filter--currency">{{ __('main.currency') }} </span>
                                <label>
                                    <input class="price__filter--input__field border-0" name="max" type="number"
                                        min="0" placeholder="250.00" required value="{{ request('max') }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <button class="price__filter--btn primary__btn" type="submit">
                        {{ __('main.filter') }}
                    </button>
                </div>
            </form>
            <div class="single__widget widget__bg">
                <h2 class="widget__title h3">{{ __('main.Featured Products') }}</h2>
                <div class="product__grid--inner">
                    @foreach ($features as $product)
                        <div class="product__items product__items--grid d-flex align-items-center">
                            <div class="product__items--grid__thumbnail position__relative">
                                <a class="product__items--link" href="{{ Helper::link('product.details', $product->id, $product->name) }}">
                                    <img class="product__items--img product__primary--img"
                                        src="{{ asset($product->image) }}" alt="product-img">
                                    @if ($product->oneImage)
                                        <img class="product__items--img product__secondary--img"
                                            src="{{ asset($product->oneImage?->image) }}" alt="product-img">
                                    @endif
                                </a>
                            </div>
                            <div class="product__items--grid__content">
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
                                <ul class="rating product__rating d-flex">
                                    <li class="rating__list">
                                        <span class="rating__list--icon">
                                            <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                <path data-name="star - Copy"
                                                    d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                    transform="translate(0 -0.018)" fill="currentColor">
                                                </path>
                                            </svg>
                                        </span>
                                    </li>
                                    <li class="rating__list">
                                        <span class="rating__list--icon">
                                            <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                <path data-name="star - Copy"
                                                    d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                    transform="translate(0 -0.018)" fill="currentColor">
                                                </path>
                                            </svg>
                                        </span>
                                    </li>
                                    <li class="rating__list">
                                        <span class="rating__list--icon">
                                            <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                <path data-name="star - Copy"
                                                    d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                    transform="translate(0 -0.018)" fill="currentColor">
                                                </path>
                                            </svg>
                                        </span>
                                    </li>
                                    <li class="rating__list">
                                        <span class="rating__list--icon">
                                            <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                <path data-name="star - Copy"
                                                    d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                    transform="translate(0 -0.018)" fill="currentColor">
                                                </path>
                                            </svg>
                                        </span>
                                    </li>
                                    <li class="rating__list">
                                        <span class="rating__list--icon">
                                            <svg class="rating__list--icon__svg" xmlns="http://www.w3.org/2000/svg"
                                                width="14.105" height="14.732" viewBox="0 0 10.105 9.732">
                                                <path data-name="star - Copy"
                                                    d="M9.837,3.5,6.73,3.039,5.338.179a.335.335,0,0,0-.571,0L3.375,3.039.268,3.5a.3.3,0,0,0-.178.514L2.347,6.242,1.813,9.4a.314.314,0,0,0,.464.316L5.052,8.232,7.827,9.712A.314.314,0,0,0,8.292,9.4L7.758,6.242l2.257-2.231A.3.3,0,0,0,9.837,3.5Z"
                                                    transform="translate(0 -0.018)" fill="currentColor">
                                                </path>
                                            </svg>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    </main>
@endsection
