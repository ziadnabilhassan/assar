  <script src="{{ asset('website/assets/js/vendor/popper.js')}}" defer="defer"></script>
  <script src="{{ asset('website/assets/js/vendor/bootstrap.min.js')}}" defer="defer"></script>
  <script src="{{ asset('website/assets/js/plugins/swiper-bundle.min.js')}}"></script>
  <script src="{{ asset('website/assets/js/plugins/glightbox.min.js')}}"></script>

  <!-- Customscript js -->
  <script src="{{ asset('website/assets/js/script.js')}}"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- inc and dec in product details page --}}
<script>
    $(document).on('click', '.product-form .quantity__value.increase', function() {
        let $input = $(this).siblings('label').find('.quantity-input');
        $input.val(parseInt($input.val()) + 1);
    });

    // Decrease Button
    $(document).on('click', '.product-form .quantity__value.decrease', function() {
        let $input = $(this).siblings('label').find('.quantity-input');
        let current = parseInt($input.val());
        if (current > 1) {
            $input.val(current - 1);
        }
    });
</script>


{{-- Add to cart function --}}
<script>
    function addToCart(productId, variant, qty = 1) {
        $.ajax({
            url: '{{ route('cart.add') }}',
            method: 'POST',
            data: {
                id: productId,
                variant: variant,
                qty: qty,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('main.success') }}',
                    text: response.success,
                    showCancelButton: true,
                    confirmButtonText: '{{ __('main.shopping cart') }}',
                    cancelButtonText: '{{ __('main.continue shopping') }}',
                    preConfirm: () => {
                        window.location.href = '{{ route('cart') }}';
                    }
                }).then((result) => {
                    if (result.isDismissed) {
                        Swal.close();
                    }
                });

                // Update cart data(if needed)
                $.ajax({
                    method: 'GET',
                    url: '{{ route('contacts') }}',
                    success: function(response) {
                        let $response = $('<div>').html(response);
                        let cartCount = $response.find('header .cart-count').html();
                        let cartContent = $response.find('.offCanvas__minicart').html();
                        $('header .cart-count').html(cartCount);
                        $('.offCanvas__minicart').html(cartContent);
                        $('.offCanvas__minicart .btn-cart-close').on('click', function() {
                            $('.offCanvas__minicart').removeClass('active');
                            $('body').removeClass('offCanvas__minicart_active');
                        });
                    },
                    error: function() {
                        console.log('Error fetching cart data.');
                    }
                });
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.responseJSON.error,
                });
            }
        });
    }

    //  Add to cart using link
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        let productId = $(this).data('id');
        let variant = $(this).data('variant');
        addToCart(productId, variant);
    });

    //  Add to cart using form
    $(document).on('click', '.add-to-cart-details', function(e) {
        e.preventDefault();
        let qty = $('.quantity-input').val() || 1;
        let productId = $(this).attr('data-id');
        let variant = $(this).attr('data-variant');
        addToCart(productId, variant, qty);
    });
</script>

{{-- update quantity in cart --}}
<script>
    function updateQuantity($input) {
        let productId = $input.data('id');
        let quantity = parseInt($input.val());

        if (quantity < 1) {
            $input.val(1);
            return;
        }

        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'POST',
            data: {
                id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                $input.val(quantity - 1);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.responseJSON?.error || 'An error occurred.',
                });
            }
        });
    }

    // Increase Button
    $(document).on('click', '.cart-page .quantity__value.increase', function() {
        let $input = $(this).siblings('label').find('.qty-update');
        $input.val(parseInt($input.val()) + 1);
        updateQuantity($input);
    });

    // Decrease Button
    $(document).on('click', '.cart-page .quantity__value.decrease', function() {
        let $input = $(this).siblings('label').find('.qty-update');
        let current = parseInt($input.val());
        if (current > 1) {
            $input.val(current - 1);
            updateQuantity($input);
        }
    });

    // When user manually changes value
    $(document).on('change', '.qty-update', function() {
        updateQuantity($(this));
    });
</script>


{{-- remove item from cart --}}
<script>
    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();
        let productId = $(this).data('id');
        $.ajax({
            url: '{{ route('cart.remove') }}',
            method: 'POST',
            data: {
                id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                });
            }
        });
    });
</script>

{{-- quick view --}}
<script>
    $(document).ready(function() {
        $('.view-product').on('click', function(e) {
            e.preventDefault();
            $('.quickview-body').empty();
            // add loding
            $('.quickview-body').append(`
                <div class="loading-spinner text-center" style="line-height: 300px;">
                    <div class="spinner-border" role="status" style="width: 5rem; height: 5rem;">
                    </div>
                </div>
            `);
            let id = $(this).data('id');
            let variant = $(this).data('variant');
            $.ajax({
                url: '{{ route('product.quickView', '') }}/' + variant,
                method: 'GET',
                success: function(response) {
                    $('.quickview-body').html(response);
                },
                error: function() {
                    $('.quickview-body').click();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "{{ __('main.no products') }}",
                    });
                }
            });
        });
    });
</script>

{{-- quick view actions --}}
<script>
    $(document).ready(function() {
        $(document).on('click', '.image-view-url', function(e) {
            let img = $(this).attr('src');
            $('.image-view').attr('src', img);
        });
        $(document).on('change', '.variants-list', function() {
            let selectedOption = $(this).find('option:selected');
            let price = selectedOption.data('price');
            let oldPrice = selectedOption.data('old_price');
            $('.size-price').text('LE ' + price);
            if (oldPrice) {
                if ($('.size-old-price').length) {
                    $('.size-old-price').text('LE ' + oldPrice);
                } else {
                    $('.quickview-wrapper .price').append(
                        '<del class="amount size-old-price" style="color: red; font-size: 15px;">LE ' +
                        oldPrice + '</del>');
                }
            } else {
                $('.size-old-price').remove();
            }
        });
        $(document).on('click', '.plus', function() {
            let $input = $(this).siblings('.qty');
            let currentVal = parseInt($input.val());
            if (!isNaN(currentVal)) {
                $input.val(currentVal + 1);
            }
        });
        $(document).on('click', '.minus', function() {
            let $input = $(this).siblings('.qty');
            let currentVal = parseInt($input.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                $input.val(currentVal - 1);
            }
        });
    });
</script>

{{-- subscribtion --}}
<script>
    $(document).ready(function() {
        $('#subscribtion').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route('newsletter.message') }}',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        title: "Good job!",
                        text: response,
                        icon: "success"
                    });
                    $('#subscribtion')[0].reset();
                },
                error: function(error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
            });
        });
    });
</script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ __('main.success') }}',
            text: '{{ session('success') }}',
        });
    </script>
@endif
@if (session('success_msg'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ __('main.success') }}',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@yield('js')
