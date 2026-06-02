<div class="row row-cols-lg-2 row-cols-md-2">
    <div class="col-md-6">
        <div class="quickview__gallery quickview__swiper--activation swiper">
            <img class="quickview__thumb--img" src="{{ asset($product->image) }}" alt="image">
        </div>
    </div>
    <div class="col-md-6">
        <div class="quickview__info">
            <form action="#">
                <h2 class="product__details--info__title mb-10">{{ $product->name }}</h2>
                <div class="product__details--info__price mb-10">
                    <span class="current__price">
                        {{ __('main.currency') }} {{ $product->oneVariant?->price }}
                    </span>
                    @if ($product->oneVariant?->old_price)
                        <span class="old__price">
                            {{ __('main.currency') }} {{ $product->oneVariant?->old_price }}
                        </span>
                    @endif
                </div>
                <p class="product__details--info__desc mb-10">
                    {!! Helper::limit($product->description, 200) !!}
                </p>
                <div class="product__variant">
                    <div class="product__variant--list mb-15">
                        <div class="color-box">
                            <span class="color-name">- {{ __('main.color') }}:</span>
                            <span class="color-value">
                                {{ $product->oneVariant?->color?->name }}
                            </span>
                        </div>
                        <div class="size-box">
                            <span class="size-name">- {{ __('main.size') }}:</span>
                            <span class="size-value">
                                {{ $product->oneVariant?->size?->name }},
                            </span>
                        </div>
                        <div class="category-box">
                            <span class="category-name">- {{ __('main.category') }}:</span>
                            <span class="category-value">
                                {{ $product->category?->title }}
                            </span>
                        </div>
                        <div class="more-colors-sizes">
                            <a href="{{ Helper::link('product.details', $product->id, $product->name) }}"
                                class="fw-bold fs-3" style="color: var(--secondary-color)">
                                - {{ __('main.show more colors and sizes') }}
                                <i class="fa-solid fa-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                    <div class="quickview__variant--list quantity d-flex align-items-center mb-15">
                        @if ($product->oneVariant?->quantity)
                            <button class="primary__btn quickview__cart--btn add-to-cart mx-0"
                                data-id="{{ $product->id }}" data-variant="{{ $product->oneVariant?->id }}">
                                {{ __('main.add to cart') }}
                            </button>
                        @else
                            <button class="primary__btn quickview__cart--btn mx-0" type="button"
                                data-id="{{ $product->id }}" data-variant="{{ $product->oneVariant?->id }}"
                                style="background: #8a0000; color: white; cursor: not-allowed;">
                                {{ __('main.out of stock') }}
                            </button>
                        @endif
                    </div>
                </div>
                <div class="quickview__social d-flex align-items-center">
                    <label class="quickview__social--title">{{ __('main.share') }}:</label>
                    <ul class="quickview__social--wrapper mt-0 d-flex">
                        <li class="quickview__social--list">
                            <a class="quickview__social--icon" target="_blank" href="https://www.facebook.com/">
                                <i class="fab fa-facebook-f"></i>
                                <span class="visually-hidden">Facebook</span>
                            </a>
                        </li>
                        <li class="quickview__social--list">
                            <a class="quickview__social--icon" target="_blank" href="https://twitter.com/">
                                <i class="fab fa-twitter"></i>
                                <span class="visually-hidden">Twitter</span>
                            </a>
                        </li>
                        <li class="quickview__social--list">
                            <a class="quickview__social--icon" target="_blank" href="https://www.instagram.com/">
                                <i class="fab fa-instagram"></i>
                                <span class="visually-hidden">Instagram</span>
                            </a>
                        </li>
                        <li class="quickview__social--list">
                            <a class="quickview__social--icon" target="_blank" href="https://www.youtube.com/">
                                <i class="fab fa-youtube"></i>
                                <span class="visually-hidden">Youtube</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>
