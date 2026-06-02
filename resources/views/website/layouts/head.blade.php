<title>@yield('title', 'Yeni Moda')</title>
<!-- favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('logo/favicon.png') }}">
<!-- css -->
  <link rel="stylesheet" href="{{ asset('website/assets/css/plugins/swiper-bundle.min.css')}}">
  <link rel="stylesheet" href="{{ asset('website/assets/css/plugins/glightbox.min.css')}}">
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">

  <!-- Plugin css -->
  <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/bootstrap.min.css')}}">

  <!-- Custom Style CSS -->
  <link rel="stylesheet" href="{{ asset('website/assets/css/style.css')}}">

<style>
    .breadcrumb__bg {
        background-image: url('{{ asset($Setting->main_image) }}');
    }
</style>

@yield('css')

@if (app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{ asset('website/assets/css/style-rtl.css') }}">
@endif
