@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Design Stickers</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0"> / Stickers List </span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <a class="btn d-block btn-outline-primary mb-3" href="{{ route('design-stickers.create') }}">Add Sticker</a>

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stickers as $index => $sticker)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><img src="{{ asset($sticker->image) }}" width="60" height="60" style="object-fit: contain"></td>
                                        <td>{{ $sticker->name }}</td>
                                        <td>{{ $sticker->category ?? '-' }}</td>
                                        <td>{{ $sticker->sort_order }}</td>
                                        <td>
                                            @if ($sticker->is_active)
                                                <span class="badge badge-success text-white">Active</span>
                                            @else
                                                <span class="badge badge-secondary text-white">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row" style="gap: 10px; width: 120px">
                                                <a class="btn btn-primary" style="padding: 5px 10px" href="{{ route('design-stickers.edit', $sticker->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a class="modal-effect btn btn-danger employee-delete" style="padding: 5px 10px"
                                                    data-effect="effect-slide-in-bottom" data-toggle="modal" href="#delete"
                                                    data-id="{{ $sticker->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="delete" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Delete Sticker</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">x</span></button>
                    </div>
                    <form action="{{ route('design-stickers.destroy', 'delete') }}" method="post">
                        <div class="modal-body">
                            <h6>Are you sure ?</h6>
                            <input class="id" type="hidden" name="id" value="">
                            @csrf
                            @method('delete')
                        </div>
                        <div class="modal-footer">
                            <button class="btn ripple btn-primary" type="submit">Delete</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.employee-delete').on('click', function() {
            $('#delete').find('input.id').val($(this).data('id'));
        })
    </script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
@endsection
