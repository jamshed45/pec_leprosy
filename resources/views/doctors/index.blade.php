@extends('layout')


@section('content')



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>Doctors</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Doctors</li>
                <li><i class="fa fa-angle-right"></i> View Doctors</li>
            </ol>
        </div>

        <div class="col-md-12">
            <!-- will be used to show any messages -->
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
        </div> 

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">View Health Facilities</h5>
                        </div>
                        <div class="card-body">
                            @php
                                // echo "<pre>";
                                // print_r($records);
                                // echo "</pre>";
                                // die();
                            @endphp
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>ID</td>
                                            <td>Name</td>
                                            <td>Health Facility</td>
                
                                            <td>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        {{-- @php
                                            echo "<pre>";
                                            print_r($record);
                                            echo "</pre>";
                                        @endphp --}}
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $record->doctor_name }}</td>
                                                <td>{{ $record->health_facility_name }}</td>

                                                <!-- we will also add show, edit, and delete buttons -->
                                                <td>
                                                    
                                                    <a href="{{ route('doctors.edit', $record->doctor_id) }}"><i class="fa fa-edit"></i></a>
                                                    <a type="click" class="delete-rec-btn"
                                                            data-name="City"
                                                            data-msg="Are you sure You want to Delete <b>{{ $record->doctor_name }}</b>?"
                                                            data-url="{{ route('doctors.destroy', $record->doctor_id) }}"><i class="fa fa-trash-o"></i></a>

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
