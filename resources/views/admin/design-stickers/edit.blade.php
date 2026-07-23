@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Edit Design Sticker</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('design-stickers.update', $sticker->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="dropify" data-height="200" name="image" data-default-file="{{ asset($sticker->image) }}" />
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name <span class="font-weight-bold text-danger">*</span></label>
                            <input class="form-control" required name="name" value="{{ old('name', $sticker->name) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category</label>
                            <input class="form-control" name="category" value="{{ old('category', $sticker->category) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input class="form-control" type="number" min="0" name="sort_order" value="{{ old('sort_order', $sticker->sort_order) }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <label class="ckbox mb-0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $sticker->is_active))><span>Active</span></label>
                    </div>
                </div>
                <button type="submit" class="btn d-block btn-outline-primary w-100 mt-4">Update</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
@endsection
