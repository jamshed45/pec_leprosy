@extends('layout')

@section('content')
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>Health Facilities</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Health Facilities</li>
                <li><i class="fa fa-angle-right"></i> Add Health Facility</li>
            </ol>
        </div>


        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">View Health Facilities</h5>
                        </div>
                        <div class="card-body">
              
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>ID</td>
                                            <td>Name</td>
                                            <td>Tehsil</td>
                                            <td>District</td>
                                            <td>Division</td>
                                            <td>Province</td>
                                            <td>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($records as $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $record->health_facility_name }}</td>
                                                <td>{{ $record->tehsil_name }}</td>
                                                <td>{{ $record->district_name }}</td>
                                                <td>{{ $record->division_name }}</td>
                                                <td>{{ $record->province_name }}</td>
                                                <!-- we will also add show, edit, and delete buttons -->
                                                <td>
{{-- 
                                                    <a href="{{ route('cities.edit', $record->health_facility_id) }}"><i
                                                            class="fa fa-edit"></i></a>
                                                    <a type="click" class="delete-rec-btn" data-name="City"
                                                        data-msg="Are you sure You want to Delete <b>{{ $record->health_facility_name }}</b>?"
                                                        data-url="{{ route('cities.destroy', $record->health_facility_id) }}"><i
                                                            class="fa fa-trash-o"></i></a> --}}

                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form id="delete-form" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
