@extends('layout')


@section('content')



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>Provinces</h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Provinces</li>
                <li><i class="fa fa-angle-right"></i> View Provinces</li>
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
                            <h5 class="text-white m-b-0">View Provinces</h5>
                        </div>
                        <div class="card-body">
                            @php
                                // print_r($records);
                                // die();
                            @endphp
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>ID</td>
                                            <td>Name</td>
                                            <td>Division</td>
                                            <td>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $record->province_name }}</td>
                                                <td>
                                                    @php
                                                        $divisions = DB::table('division')
                                                            ->where('province_id',  $record->province_id)
                                                            ->get()
                                                            ->toArray();
                                                        echo "<small>";
                                                            foreach($divisions as $division)
                                                            { 
                                                                echo $division->division_name.' - ';
                                                            }
                                                        echo "</small>";
                                                    @endphp
                                                </td>
                                                <!-- we will also add show, edit, and delete buttons -->
                                                <td>
                                                    
                                                    <a href="{{ route('provinces.edit', $record->province_id) }}"><i class="fa fa-edit text-success mx-1"></i></a>
                                                    <a type="click" class="delete-rec-btn mx-1 text-danger"
                                                            data-name="province"
                                                            data-msg="Are you sure You want to Delete <b>{{ $record->province_name }}</b>?"
                                                            data-url="{{ route('provinces.destroy', $record->province_id) }}"><i class="fa fa-trash-o"></i></a>

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
