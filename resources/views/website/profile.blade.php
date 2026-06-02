@extends('website.layouts.master')
@section('title')
    {{ __('main.site') }} | {{ __('main.profile') }}
@endsection
@section('css')
    <style>
        .section--padding .form-control {
            font-size: 16px;
        }

        .accordion-button {
            font-family: "Inter", sans-serif;
            font-size: 16px;
            font-weight: 500;
            line-height: 24px;
            letter-spacing: 0px;
            color: #667085;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            height: 100%;
            width: 100%;
            /* padding: 0px 20px; */
            text-decoration: none;
            -webkit-transition: all 300ms ease-in-out;
            transition: all 300ms ease-in-out;
            color: var(--theme-color);
        }

        .accordion-button i {
            margin-right: 12px;
            height: 24px;
            width: 24px;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .accordion .form-control {
            padding: 12px 15px;
            border-radius: 12px;
            box-shadow: none;
            transition: var(--transition);
        }

        .accordion-button:not(.collapsed) {
            background: transparent;
            color: var(--theme-color);
        }

        .user-name {
            color: var(--theme-color);
        }
    </style>
@endsection
@section('content')
    <section class="breadcrumb__section breadcrumb__bg">
        <div class="container">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="breadcrumb__content text-center">
                        <h1 class="breadcrumb__content--title text-white mb-25">{{ __('main.profile') }}</h1>
                        <ul class="breadcrumb__content--menu d-flex justify-content-center">
                            <li class="breadcrumb__content--menu__items">
                                <a class="text-white" href="{{ route('home') }}">
                                    {{ __('main.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb__content--menu__items">
                                <span class="text-white">{{ __('main.profile') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section--padding">
        <div class="container form__wrapper ">
            <div class="row mb-5">
                <!-- Accordion Headers in col-3 -->
                <div class="col-lg-4">
                    <div class="accordion category-sidebar__inner" id="sidebarAccordion">
                        <!-- Dashboard -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingDashboard">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#dashboardContent" aria-expanded="true"
                                    aria-controls="dashboardContent">
                                    <i class="fa-solid fa-home"></i>
                                    {{ __('main.dashboard') }}
                                </button>
                            </h2>
                        </div>
                        <!-- Profile Information -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingProfile">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#profileContent" aria-expanded="false" aria-controls="profileContent">
                                    <i class="fa-solid fa-user"></i>
                                    {{ __('main.profile information') }}
                                </button>
                            </h2>
                        </div>
                        <!-- Address -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAddress">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#addressContent" aria-expanded="false" aria-controls="addressContent">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ __('admin.address') }}
                                </button>
                            </h2>
                        </div>
                        <!-- Orders -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOrders">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#ordersContent" aria-expanded="false" aria-controls="ordersContent">
                                    <i class="fa-solid fa-bell"></i>
                                    {{ __('main.orders') }}
                                </button>
                            </h2>
                        </div>
                        <!-- Change Password -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPassword">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#passwordContent" aria-expanded="false" aria-controls="passwordContent">
                                    <i class="fa-solid fa-key"></i>
                                    {{ __('main.change password') }}
                                </button>
                            </h2>
                        </div>
                        <!-- Logout -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLogout">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#logoutContent" aria-expanded="false" aria-controls="logoutContent">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    {{ __('main.logout') }}
                                </button>
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Accordion Content in col-9 -->
                <div class="col-lg-8">
                    <div class="accordion" id="contentAccordion">
                        <!-- Dashboard Content -->
                        <div id="dashboardContent" class="accordion-collapse collapse show"
                            aria-labelledby="headingDashboard" data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <div class="profile__card">
                                    <div class="profile__card__content">
                                        <h2 class="title">{{ __('main.welcome back') }} <span
                                                class="user-name">{{ $user->first_name }}</span></h2>
                                        <p class="desc">
                                        <p>{{ __('main.you can manage your profile from here') }}</p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Profile Content -->
                        <div id="profileContent" class="accordion-collapse collapse" aria-labelledby="headingProfile"
                            data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <form action="{{ route('profile.info.update') }}" method="post">
                                    @csrf
                                    <div class="row account_login_form g-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">{{ __('main.first name') }}</label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="{{ $user->first_name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="last_name">{{ __('main.last name') }}</label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="{{ $user->last_name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="phone">{{ __('main.phone') }}</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    value="{{ $user->phone }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email">{{ __('main.email') }}</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit"
                                                class="primary__btn minicart__button--link w-100 text-center mt-4">
                                                {{ __('main.send') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Address Content -->
                        <div id="addressContent" class="accordion-collapse collapse" aria-labelledby="headingAddress"
                            data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <form action="{{ route('profile.address.update') }}" method="post">
                                    @csrf
                                    <div class="row gap-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="delivery">{{ __('main.delivery') }}</label>
                                                <select name="delivery_id" id="delivery" required
                                                    class="delivery_select form-control">
                                                    @foreach ($deliveries as $delivery)
                                                        <option value="{{ $delivery->id }}"
                                                            {{ $delivery->id == $user->address?->delivery_id ? 'selected' : '' }}>
                                                            {{ $delivery->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="city">{{ __('main.state/city') }}</label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    value="{{ $user->address?->city }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address">{{ __('main.address') }}</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    value="{{ $user->address?->address }}" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit"
                                                class="primary__btn minicart__button--link w-100 text-center mt-4">
                                                {{ __('main.send') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Orders Content -->
                        <div id="ordersContent" class="accordion-collapse collapse" aria-labelledby="headingOrders"
                            data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('main.order code') }}</th>
                                                <th>{{ __('main.date') }}</th>
                                                <th>{{ __('main.shipping') }}</th>
                                                <th>{{ __('main.total') }}</th>
                                                <th>{{ __('main.status') }}</th>
                                                <th>{{ __('main.details') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->code }}</td>
                                                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                    <td>LE {{ $order->shipping }}</td>
                                                    <td>LE {{ $order->total }}</td>
                                                    <td>{{ $order->status }}</td>
                                                    <td>
                                                        <a href="{{ route('profile.order.show', $order->id) }}">
                                                            <div class="fa fa-eye"></div>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Password Content -->
                        <div id="passwordContent" class="accordion-collapse collapse" aria-labelledby="headingPassword"
                            data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <form action="{{ route('profile.password.update') }}" method="POST">
                                    @csrf
                                    <div class="row gap-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="currentPassword">{{ __('main.current password') }}</label>
                                                <input type="password" class="form-control" id="currentPassword"
                                                    placeholder="Enter current password" required name="current_password">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="newPassword">{{ __('main.new password') }}</label>
                                                <input type="password" class="form-control" id="newPassword"
                                                    placeholder="Enter new password" required name="password">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="confirmPassword">{{ __('main.confirm password') }}</label>
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    placeholder="Confirm new password" required
                                                    name="password_confirmation">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit"
                                                class="primary__btn minicart__button--link w-100 text-center mt-4">
                                                {{ __('main.change password') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Logout Content -->
                        <div id="logoutContent" class="accordion-collapse collapse" aria-labelledby="headingLogout"
                            data-bs-parent="#contentAccordion">
                            <div class="accordion-body">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit"
                                        class="primary__btn minicart__button--link w-100 text-center mt-4">{{ __('main.logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    @error('incorrect password')
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $message }}',
            });
        </script>
    @enderror
@endsection
