@extends('layouts.master')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Saved Design Details</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    @if ($savedDesign->preview_image)
                        <img src="{{ $savedDesign->preview_image }}" class="img-fluid border" style="max-height: 320px; object-fit: contain">
                    @else
                        <div class="text-muted">No preview image</div>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr><th>Name</th><td>{{ $savedDesign->name ?? '-' }}</td></tr>
                        <tr><th>Client</th><td>{{ trim(($savedDesign->user->first_name ?? '') . ' ' . ($savedDesign->user->last_name ?? '')) ?: '-' }}</td></tr>
                        <tr><th>Email</th><td>{{ $savedDesign->user->email ?? '-' }}</td></tr>
                        <tr><th>Product</th><td>{{ $savedDesign->product->name ?? '-' }}</td></tr>
                        <tr><th>Base Design</th><td>{{ $savedDesign->desgin->name ?? '-' }}</td></tr>
                        <tr><th>Sticker IDs</th><td>{{ $savedDesign->sticker_ids ? implode(', ', $savedDesign->sticker_ids) : '-' }}</td></tr>
                        <tr><th>Created At</th><td>{{ $savedDesign->created_at }}</td></tr>
                    </table>
                </div>
            </div>
            <h5 class="mt-4">Design Data</h5>
            <pre class="p-3 bg-light border" style="white-space: pre-wrap">{{ json_encode($savedDesign->design_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            <a class="btn btn-secondary" href="{{ route('saved-designs.index') }}">Back</a>
        </div>
    </div>
@endsection
