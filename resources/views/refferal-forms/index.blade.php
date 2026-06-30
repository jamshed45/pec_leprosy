@extends('layout')

@section('content')
<?php $user = Auth::user(); ?>

<div class="content-wrapper">

    <div class="content-header sty-one">
        <h1>Refferal Patients</h1>
        <ol class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li class="sub-bread"><i class="fa fa-angle-right"></i> Refferal Patients</li>
            <li><i class="fa fa-angle-right"></i> Patient's List</li>
        </ol>
    </div>

    @include('user_center_section')

    <div class="col-md-12">
        @if (Session::has('success'))
            <div class="alert alert-info">{{ Session::get('success') }}</div>
        @endif
    </div>

    <div class="content">
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-outline">
                    <div class="card-header bg-blue">
                        <h5 class="text-white m-b-0">Refferal Patient's List</h5>
                    </div>

                    <div class="card-body">

                        {{-- SEARCH FORM --}}
                        <form id="searchForm" method="GET" class="mb-3" style="max-width:400px;">
                            <div class="input-group">
                                <input type="text" name="search" id="customSearch"
                                       class="form-control"
                                       placeholder="Search..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">

                            <table id="refTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lot's Name</th>
                                        <th>Date & Record ID</th>
                                        <th>Patient Info</th>
                                        <th>Information</th>
                                        <th>Cat. Book Number</th>
                                        <th>Verified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    let search = "{{ request('search') }}";

    let table = $('#refTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: '{{ route("get-refferal-forms") }}',
            data: function (d) {
                d.search_custom = search; // PASS SEARCH STRING TO BACKEND
            }
        },
        columns: [
            { data: 'no', orderable: false, searchable: false },
            { data: 'lots_name', name: 'user.name' },
            { data: 'date_record', name: 'entry_at' },
            { data: 'patient_info', name: 'full_name' },
            { data: 'information', name: 'referByCategory.category_name' },
            { data: 'cat_book_number', name: 'cat_book_number' },
            { data: 'verified', orderable: false, searchable: false },
            { data: 'actions', orderable: false, searchable: false },
        ]
    });

});
</script>
@endsection
