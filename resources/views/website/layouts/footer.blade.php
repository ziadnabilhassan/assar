<!-- Start footer section -->
<footer class="footer__section bg__black">
    <div class="container-fluid">
        <div class="main__footer d-flex justify-content-between">
            <div class="footer__widget footer__widget--width">
                <h2 class="footer__widget--title text-ofwhite h3">{{ __('main.about us') }}
                    <button class="footer__widget--button" aria-label="footer widget button">
                        <svg class="footer__widget--title__arrowdown--icon" xmlns="http://www.w3.org/2000/svg"
                            width="12.355" height="8.394" viewBox="0 0 10.355 6.394">
                            <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z"
                                transform="translate(-6 -8.59)" fill="currentColor"></path>
                        </svg>
                    </button>
                </h2>
                <div class="footer__widget--inner">
                    <p class="footer__widget--desc text-ofwhite mb-20" style="max-width: 370px">
                        {{ Helper::limit($About->text, 300) }}
                    </p>
                    <div class="footer__social">
                        <h3 class="social__title text-ofwhite h4 mb-15">{{ __('main.follow us') }}</h3>
                        <ul class="social__shear d-flex">
                            @if ($Contact->whatsapp)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank"
                                        href="{{ $Contact->whatsapp }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->facebook)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank"
                                        href="{{ $Contact->facebook }}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->twitter)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank" href="{{ $Contact->twitter }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->instagram)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank"
                                        href="{{ $Contact->instagram }}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->linkedin)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank"
                                        href="{{ $Contact->linkedin }}">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->youtube)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank" href="{{ $Contact->youtube }}">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->tiktok)
                                <li class="social__shear--list">
                                    <a class="social__shear--list__icon" target="_blank" href="{{ $Contact->tiktok }}">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer__widget--menu__wrapper d-flex footer__widget--width">
                <div class="footer__widget">
                    <h2 class="footer__widget--title text-ofwhite h3">
                        {{ __('main.My account') }}
                        <button class="footer__widget--button" aria-label="footer widget button">
                            <svg class="footer__widget--title__arrowdown--icon" xmlns="http://www.w3.org/2000/svg"
                                width="12.355" height="8.394" viewBox="0 0 10.355 6.394">
                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z"
                                    transform="translate(-6 -8.59)" fill="currentColor"></path>
                            </svg>
                        </button>
                    </h2>
                    <ul class="footer__widget--menu footer__widget--inner">
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('profile.index') }}">{{ __('main.profile') }}</a></li>
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('cart') }}">{{ __('main.shopping cart') }}</a></li>
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('products') }}">{{ __('main.shop') }}</a></li>
                    </ul>
                </div>
                <div class="footer__widget">
                    <h2 class="footer__widget--title text-ofwhite h3">
                        {{ __('main.fast links') }}
                        <button class="footer__widget--button" aria-label="footer widget button">
                            <svg class="footer__widget--title__arrowdown--icon" xmlns="http://www.w3.org/2000/svg"
                                width="12.355" height="8.394" viewBox="0 0 10.355 6.394">
                                <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z"
                                    transform="translate(-6 -8.59)" fill="currentColor"></path>
                            </svg>
                        </button>
                    </h2>
                    <ul class="footer__widget--menu footer__widget--inner">
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('about') }}">{{ __('main.about') }}</a></li>
                        <li class="footer__widget--menu__list"><a class="footer__widget--menu__text"
                                href="{{ route('contacts') }}">{{ __('main.contact us') }}</a></li>
                        @foreach ($Pages as $page)
                            <li class="footer__widget--menu__list">
                                <a class="footer__widget--menu__text"
                                    href="{{ Helper::link('page', $page->id, $page->slug) }}">
                                    {{ $page->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="footer__widget footer__widget--width" style="min-width: 320px">
                <h2 class="footer__widget--title text-ofwhite h3">
                    {{ __('main.subscribe') }}
                    <button class="footer__widget--button" aria-label="footer widget button">
                        <svg class="footer__widget--title__arrowdown--icon" xmlns="http://www.w3.org/2000/svg"
                            width="12.355" height="8.394" viewBox="0 0 10.355 6.394">
                            <path d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z"
                                transform="translate(-6 -8.59)" fill="currentColor"></path>
                        </svg>
                    </button>
                </h2>
                <div class="footer__widget--inner">
                    <p class="footer__widget--desc text-ofwhite m-0">
                        {{ __('main.subscribe text') }}
                    </p>
                    <div class="newsletter__subscribe">
                        <form class="newsletter__subscribe--form" id="subscribtion">
                            @csrf
                            <label>
                                <input class="newsletter__subscribe--input" name="email"
                                    placeholder="{{ __('main.Your Email Address') }}" type="email">
                            </label>
                            <button class="newsletter__subscribe--button" type="submit">
                                {{ __('main.subscribe') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__bottom d-flex justify-content-center align-items-center">
            <p class="copyright__content text-ofwhite m-0">Copyright © 2025 <a class="copyright__content--link"
                    href="{{ route('home') }}">Abaya Hoor</a> . All Rights Reserved. Design By
                <a class="copyright__content--link" href="https://websolla.com">Websolla</a>
            </p>
        </div>
    </div>
</footer>

<!-- End footer section -->

<!-- Quickview Wrapper -->
<div class="modal" id="modal1" data-animation="slideInUp">
    <div class="modal-dialog quickview__main--wrapper modal-lg">
        <header class="modal-header quickview__header">
            <button class="close-modal quickview__close--btn" aria-label="close modal" data-close>✕ </button>
        </header>
        <div class="quickview__inner quickview-body">
            {{-- load quickview content --}}
        </div>
    </div>
</div>
<!-- Quickview Wrapper End -->



<!-- Scroll top bar -->
<button id="scroll__top"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
            d="M112 244l144-144 144 144M256 120v292" />
    </svg></button>
