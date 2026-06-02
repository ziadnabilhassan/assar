<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Author" content="Web Solla">
    <meta name="author" content="websolla.com">
    <meta name="keywords" content="{{ $Setting->keywords }}">
    <meta name="description" content="{{ $Setting->description }}">
    @include('website.layouts.head')
</head>

<body>

  <!-- Start preloader -->
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="L" class="letters-loading">
                        B
                    </span>

                    <span data-text-preloader="O" class="letters-loading">
                        R
                    </span>

                    <span data-text-preloader="A" class="letters-loading">

                    A
                    </span>

                    <span data-text-preloader="D" class="letters-loading">
                        N
                    </span>

                    <span data-text-preloader="I" class="letters-loading">
                        D
                    </span>

                    <span data-text-preloader="N" class="letters-loading">
                        O
                    </span>


                </div>
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>
    <!-- End preloader -->
    @include('website.layouts.nav')

    <main class="main__content_wrapper">
        @yield('content')
    </main>

    @include('website.layouts.footer')
    @include('website.layouts.footer-scripts')
</body>

</html>
