@extends('layout')

@section('content')
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    @php
        $user = Auth::user();
    @endphp
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header sty-one">
            <h1>TA Bills</h1>
            <ol class="breadcrumb">
              <li><a href="#">Dashboard</a></li>
              <li class="sub-bread"><i class="fa fa-angle-right"></i> TA Bills</li>
              <li><i class="fa fa-angle-right"></i> View TA Bills</li>
            </ol>
          </div>

        
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline">
                        <div class="card-header bg-blue">
                            <h5 class="text-white m-b-0">View TA Bills</h5>
                        </div>
                        <div class="card-body">
                
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>TA Bill ID</td>
                                            <td>Name</td>
                                            <td>Month</td>
                                            <td>Submit</td>
                                            <td>Verified</td>
                                            <td>Approved</td>
                                            <td>PDF</td>
                                            <td>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>ta{{ $record->ta_form_id }}</td>
                                                <td>
                                                @php
                                                    $user_name = DB::table('users')->where('id', $record->user_id)->get()->toArray(); 
                                                @endphp
                                                {{ $user_name[0]->name }}
                                                </td>
                                                <td>{{ date('F Y', strtotime($record->entry_month)) }}</td>
                                                <td>
                                                    @if($record->submit_status)
                                                    <i class="fa fa-check-circle text-success"></i><br>
                                                    <small>{{ date('jS M, Y',strtotime($record->submit_status_date)) }}</small>
                                                    @else
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($record->verify_status)
                                                    <i class="fa fa-check-circle text-success"></i><br>
                                                    <small>{{ date('jS M, Y',strtotime($record->verify_status_date)) }}</small>
                                                    @else
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($record->approve_status)
                                                    <i class="fa fa-check-circle text-success"></i><br>
                                                    <small>{{ date('jS M, Y',strtotime($record->approve_status_date)) }}</small>
                                                    @else
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                                <!-- we will also add show, edit, and delete buttons -->
                                                <td>
                                                    <a href="{{ URL::to('travelling-allowance/generate_pdf?record_id=') }}{{ $record->ta_form_id }}" class="btn btn-success mb-3">Download PDF</a>
                                                                                              </td>
                                                <td>
                                                    @if(!$record->submit_status && $user->hasRole('pec-user'))
                                                    <a href="{{ route('travelling-allowance.edit', $record->ta_form_id) }}"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                    @if($user->hasRole('pec-data-verifier'))
                                                    <a href="{{ route('travelling-allowance.edit', $record->ta_form_id) }}"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                    @if($user->hasRole('pec-admin'))
                                                    <a href="{{ route('travelling-allowance.edit', $record->ta_form_id) }}"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                   
                                                    {{-- <a type="click" class="delete-rec-btn"
                                                            data-name="Center"
                                                            data-msg="Are you sure You want to Delete <b>{{ $record->center_name }}</b>?"
                                                            data-url="{{ route('centers.destroy', $record->center_id) }}"><i class="fa fa-trash-o"></i></a> --}}

                                                    <a href="{{ route('travelling-allowance.show', $record->ta_form_id) }}"><i class="ml-2 fa fa-eye"></i></a>                

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