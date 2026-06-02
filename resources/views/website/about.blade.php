@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ $page->title }}
@endsection
@section('css')
    <style>
        .text h2,
        .text h3 {
            margin-bottom: 20px;
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
                        <h1 class="breadcrumb__content--title text-white mb-25">{{ __('main.about') }}</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items">
                                <a class="text-white" href="{{ route('home') }}">
                                    {{ __('main.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb__content--menu__items">
                                <span class="text-white">{{ __('main.about') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Start contact section -->
    <section class="contact__section section--padding">
        <div class="container">
            <div class="main__contact--area position__relative">

                <div class="contact__form">
                    <h3 class="contact__form--title mb-40">{{ __('main.get in touch') }}</h3>
                    <form class="contact__form--inner" id="send-message">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="contact__form--list mb-20">
                                    <label class="contact__form--label" for="input1">{{ __('main.full name') }}<span
                                            class="contact__form--label__star">*</span></label>
                                    <input class="contact__form--input" required name="name" id="input1"
                                        placeholder="{{ __('main.full name') }}" type="text">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="contact__form--list mb-20">
                                    <label class="contact__form--label" for="input3">{{ __('main.phone') }} <span
                                            class="contact__form--label__star">*</span></label>
                                    <input class="contact__form--input" required name="phone" id="input3"
                                        placeholder="{{ __('main.phone') }}" type="text">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="contact__form--list mb-20">
                                    <label class="contact__form--label" for="input4">{{ __('main.email') }} <span
                                            class="contact__form--label__star">*</span></label>
                                    <input class="contact__form--input" required name="email" id="input4"
                                        placeholder="{{ __('main.email') }}" type="email">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="contact__form--list mb-20">
                                    <label class="contact__form--label" for="input2">{{ __('main.subject') }} <span
                                            class="contact__form--label__star">*</span></label>
                                    <input class="contact__form--input" required name="subject" id="input2"
                                        placeholder="{{ __('main.subject') }}" type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contact__form--list mb-15">
                                    <label class="contact__form--label" for="input5">{{ __('main.message') }} <span
                                            class="contact__form--label__star">*</span></label>
                                    <textarea class="contact__form--textarea" required name="message" id="input5"
                                        placeholder="{{ __('main.message') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <button class="contact__form--btn primary__btn"
                            type="submit">{{ __('main.send message') }}</button>
                    </form>
                </div>
                <div class="contact__info border-radius-5">
                    <div class="contact__info--items">
                        <h3 class="contact__info--content__title text-white mb-15">{{ __('main.contact us') }}</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="31.568" height="31.128"
                                    viewBox="0 0 31.568 31.128">
                                    <path id="ic_phone_forwarded_24px"
                                        d="M26.676,16.564l7.892-7.782L26.676,1V5.669H20.362v6.226h6.314Zm3.157,7a18.162,18.162,0,0,1-5.635-.887,1.627,1.627,0,0,0-1.61.374l-3.472,3.424a23.585,23.585,0,0,1-10.4-10.257l3.472-3.44a1.48,1.48,0,0,0,.395-1.556,17.457,17.457,0,0,1-.9-5.556A1.572,1.572,0,0,0,10.1,4.113H4.578A1.572,1.572,0,0,0,3,5.669,26.645,26.645,0,0,0,29.832,32.128a1.572,1.572,0,0,0,1.578-1.556V25.124A1.572,1.572,0,0,0,29.832,23.568Z"
                                        transform="translate(-3 -1)" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact__info--content">
                                <p class="contact__info--content__desc text-white">
                                    {{ __('main.contact us now') }}
                                    <br> <a href="tel:{{ $Contact->phone1 }}">{{ $Contact->phone1 }}</a> <a
                                        href="tel:{{ $Contact->phone2 }}">{{ $Contact->phone2 }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="contact__info--items">
                        <h3 class="contact__info--content__title text-white mb-15">{{ __('main.email') }}</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="31.57" height="31.13"
                                    viewBox="0 0 31.57 31.13">
                                    <path id="ic_email_24px"
                                        d="M30.413,4H5.157C3.421,4,2.016,5.751,2.016,7.891L2,31.239c0,2.14,1.421,3.891,3.157,3.891H30.413c1.736,0,3.157-1.751,3.157-3.891V7.891C33.57,5.751,32.149,4,30.413,4Zm0,7.783L17.785,21.511,5.157,11.783V7.891l12.628,9.728L30.413,7.891Z"
                                        transform="translate(-2 -4)" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact__info--content">
                                <p class="contact__info--content__desc text-white">
                                    <a href="#!">{{ __('main.send us a message') }}</a>
                                    <br>
                                    <a href="mailto:{{ $Contact->email }}">{{ $Contact->email }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="contact__info--items">
                        <h3 class="contact__info--content__title text-white mb-15">{{ __('main.location') }}</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="31.57" height="31.13"
                                    viewBox="0 0 31.57 31.13">
                                    <path id="ic_account_balance_24px"
                                        d="M5.323,14.341V24.718h4.985V14.341Zm9.969,0V24.718h4.985V14.341ZM2,32.13H33.57V27.683H2ZM25.262,14.341V24.718h4.985V14.341ZM17.785,1,2,8.412v2.965H33.57V8.412Z"
                                        transform="translate(-2 -1)" fill="currentColor" />
                                </svg>
                            </div>
                            <div class="contact__info--content">
                                <span class="contact__info--content__desc text-white">
                                    {{ __('main.address') }}
                                </span>
                                <p class="contact__info--content__desc text-white">
                                    {{ $Contact->address }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="contact__info--items">
                        <h3 class="contact__info--content__title text-white mb-15">{{ __('main.follow us') }}</h3>
                        <ul class="contact__info--social d-flex">
                            @if ($Contact->whatsapp)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->whatsapp }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->facebook)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->facebook }}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->twitter)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->twitter }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->instagram)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->instagram }}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->linkedin)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->linkedin }}">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->youtube)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->youtube }}">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                            @endif
                            @if ($Contact->tiktok)
                                <li class="contact__info--social__list">
                                    <a class="contact__info--social__icon" target="_blank"
                                        href="{{ $Contact->tiktok }}">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Start contact map area -->
    <div class="contact__map--area section--padding py-0">
        <iframe class="contact__map--iframe"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3454.988331951591!2d31.43105052494005!3d30.008491420274122!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583d004daeb703%3A0xbd33f9f2d7b03d5f!2z2KfZhNix2K3Yp9io!5e0!3m2!1sar!2seg!4v1747145706934!5m2!1sar!2seg"
            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    </main>
@endsection
