@extends('layouts.master')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Cart Item Details</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    @php($image = $cartItem->preview_image_url ?: $cartItem->image_url)
                    @if ($image)
                        <img src="{{ asset($image) }}" class="img-fluid border" style="max-height: 320px; object-fit: contain">
                    @else
                        <div class="text-muted">No image</div>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr><th>Name</th><td>{{ $cartItem->name }}</td></tr>
                        <tr><th>Client</th><td>{{ trim(($cartItem->user->first_name ?? '') . ' ' . ($cartItem->user->last_name ?? '')) ?: '-' }}</td></tr>
                        <tr><th>Email</th><td>{{ $cartItem->user->email ?? '-' }}</td></tr>
                        <tr><th>Product</th><td>{{ $cartItem->product->name ?? '-' }}</td></tr>
                        <tr><th>Variant</th><td>#{{ $cartItem->variant_id ?? '-' }}</td></tr>
                        <tr><th>Saved Design</th><td>{{ $cartItem->design ? ($cartItem->design->name ?? '#' . $cartItem->design->id) : '-' }}</td></tr>
                        <tr><th>Quantity</th><td>{{ $cartItem->quantity }}</td></tr>
                        <tr><th>Price</th><td>LE {{ $cartItem->price }}</td></tr>
                        <tr><th>Color</th><td>{{ $cartItem->color ?? '-' }}</td></tr>
                        <tr><th>Size</th><td>{{ $cartItem->size ?? '-' }}</td></tr>
                        <tr><th>Custom Design</th><td>{{ $cartItem->is_custom_design ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Created At</th><td>{{ $cartItem->created_at }}</td></tr>
                    </table>
                </div>
            </div>
            <h5 class="mt-4">Design Data</h5>
            <pre class="p-3 bg-light border" style="white-space: pre-wrap">{{ json_encode($cartItem->design_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            <a class="btn btn-secondary" href="{{ route('cart-items.index') }}">Back</a>
        </div>
    </div>
@endsection
