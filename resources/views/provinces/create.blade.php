@extends('layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1></h1>
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="sub-bread"><i class="fa fa-angle-right"></i> Province</li>
                <li><i class="fa fa-angle-right"></i> Patients</li>
            </ol>
        </div>

        <!-- will be used to show any messages -->
        <div class="col-md-12">
            @if (Session::has('error'))
            <div class="alert alert-info">{{ Session::get('error') }}</div>
            @endif
        </div>

        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">Add New Province</h5>
                        </div>
                        <div class="card-body">

                            <!-- if there are creation errors, they will show here -->
                            <form action="{{ route('provinces.store') }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label for="first_name">Enter Province Name:</label>
                                            <input type="text" name="province_name" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
